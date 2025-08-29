<?php

namespace App\Http\Controllers\Financeiro;

use Auth;
use Input;
use Flash;
use Redirect;
use DateTime;
use Session;

use App\Model\Financeiro\Agendamento;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Financeiro\AgendamentoRepository;
use App\Repositories\Financeiro\LancamentoBancarioRepository;
use App\Repositories\Cadastro\ContaContabelRepository;
use App\Repositories\Cadastro\CentroResultadoRepository;
use App\Repositories\Cadastro\ProjetoRepository;
use App\Repositories\Cadastro\CorrecaoFinanceiraRepository;
use App\Repositories\Cadastro\FavorecidoRepository;
use App\Repositories\Cadastro\BancoRepository;
use App\Repositories\Cadastro\FormaFinanceiraRepository;
use App\Repositories\Cadastro\DescontoRepository;

use App\Exceptions\Validation\ValidationException;

class AgendamentoController extends Controller
{

    //
    protected $agendamentoRepository;
    protected $planoContaRepository;
    protected $centroResultadoRepository;
    protected $projetoRepository;
    protected $favorecidoRepository;
    protected $correcaoFinanceiraRepository;
    protected $bancoRepository;
    protected $lancamentoBancarioRepository;
    protected $formaFinanceiraRepository;
    protected $descontoRepository;

    protected $per_page;

    public function __construct(
            AgendamentoRepository $agendamento, 
            ContaContabelRepository $plano,
            ProjetoRepository $projeto,
            CentroResultadoRepository $centro,
            FavorecidoRepository $favorecido,
            CorrecaoFinanceiraRepository $correcao,
            BancoRepository $banco,
            LancamentoBancarioRepository $lancamento,
            FormaFinanceiraRepository $forma,
            DescontoRepository $desconto

    ) {
        $this->agendamentoRepository = $agendamento;
        $this->planoContaRepository = $plano;
        $this->centroResultadoRepository = $centro;
        $this->projetoRepository = $projeto;
        $this->favorecidoRepository = $favorecido;
        $this->correcaoFinanceiraRepository = $correcao;
        $this->bancoRepository = $banco;
        $this->lancamentoBancarioRepository = $lancamento;
        $this->formaFinanceiraRepository = $forma;
        $this->descontoRepository = $desconto;

        // Páginação Padrão
        $this->per_page = config('versus.per_page');

        // verifica login
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        set_time_limit(900);

        if($request->input('session') ) {
            $request->session()->forget('filtros');
            $request->session()->forget('per_page');
        }
        /* FILTROS
         **************************************************************************
         */
        $where = null;
        $status = null;
        
        $filtros = Input::get('filtros');
        if(!empty($filtros))
            $request->session()->put('filtros', $filtros);
        else {
            if($request->session()->has('filtros'))
                $filtros = $request->session()->get('filtros');
        }

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'agendamentos.data_vencimento';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'DESC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;

        if(isset($filtros['id']) && !empty($filtros['id'])) {
            $id = trim($filtros['id']);
            $where['agendamentos.id'] = $id;
        } else {

            if(isset($filtros['data_vencimento']) and !empty($filtros['data_vencimento'])) {
                $datas = explode(" - ", $filtros['data_vencimento']);
                $where['data_vencimento'] = ['whereBetweenDate', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];

            }
            if(isset($filtros['data_competencia']) && !empty($filtros['data_competencia'])) {
                $datas = explode(" - ", $filtros['data_competencia']);
                $where['data_competencia'] = ['whereBetweenDate', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];
            }
            if(isset($filtros['favorecido_id']) && !empty($filtros['favorecido_id'])) {
                $where['favorecido_id'] = $filtros['favorecido_id'];
            }
            if(isset($filtros['tipo_movimento']) && !empty($filtros['tipo_movimento'])) {
                $where['tipo_movimento'] = $filtros['tipo_movimento'];
            }
            if(isset($filtros['valor_titulo']) && !empty($filtros['valor_titulo'])) {
                $valor = decimalFormat($filtros['valor_titulo']);
                if($valor > 0)
                    $where['valor_titulo'] = $valor;
            }
            if(isset($filtros['valor_saldo']) && !empty($filtros['valor_saldo'])) {
                 $valor = decimalFormat($filtros['valor_saldo']);
                if($valor > 0)
                    $where['valor_saldo'] = $valor;
            }

            if(Input::get('status')) {
                
                $status = Input::get('status');

                if(in_array('B', $status))
                    $where['bordero_id'] = ['!=', null];

                if(in_array('A', $status) && in_array('P', $status) && !in_array('L', $status)) {
                    $where['valor_saldo'] = ['>', 0];
                }
                else if(in_array('L', $status) && in_array('P', $status) && !in_array('A', $status)) {
                    $where['valor_saldo'] = ['whereRaw', 'valor_saldo != valor_titulo'];
                }
                else if(in_array('L', $status) && in_array('A', $status) && !in_array('P', $status)) {
                    echo "oi";
                    $where['valor_saldo'] = ['whereRaw', 'valor_saldo = valor_titulo or valor_saldo = 0'];
                }
                else if(in_array('A', $status) && !in_array('P', $status) && !in_array('L', $status)) {
                    $where['valor_saldo'] = ['whereRaw', 'valor_saldo = valor_titulo'];
                }
                else if(in_array('L', $status) && !in_array('P', $status) && !in_array('A', $status)) {
                    $where['valor_saldo'] = 0;
                }
                else if(in_array('P', $status) && !in_array('L', $status) && !in_array('A', $status)) {
                    $where['valor_saldo'] = ['whereRaw', 'valor_saldo < valor_titulo and valor_saldo > 0'];
                } 
                else {
                    
                }

            }
        }
        
        /*
        $agendamentos = $this->agendamentoRepository->all();
        foreach ($agendamentos as $key => $agendamento) {
            
            if($agendamento->valor_saldo < 0)
                echo "Agendamento[".$agendamento->id."] inconsistente.<br>";
            

        }*/


        /*  PÁGINAÇÃO
         ***************************************************************************
         */
        $per_page = Input::get('per_page');
        if(isset($per_page) && is_numeric($per_page))
            $request->session()->put('per_page', $per_page);
        else
            $per_page = $request->session()->get('per_page');

        if($per_page !== null)
            $agendamentos = $this->agendamentoRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $agendamentos = $this->agendamentoRepository->paginate($page, $this->per_page, $orderBy, $where);

        $favorecidos = $this->favorecidoRepository->lists();

        return view('financeiro.agendamento.index', [
            'agendamentos' => $agendamentos,
            'favorecidos' => $favorecidos,
            'status'    => $status,
            'filtros' => $filtros,
            'orderBy' => $orderBy,
            'per_page'  => $per_page
        ]);
    }

    public function ajustaAgendamento() {
        echo "agendamentos ajustado";
        
        echo count($agendamentos);
        
        echo "agendamentos ajustado";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($tipo)
    {
        $planosContas = $this->planoContaRepository->all();
        $centrosResultados = $this->centroResultadoRepository->all();
        $projetos = $this->projetoRepository->all();
        $favorecidos = $this->favorecidoRepository->lists();
        $correcoesFinanceira = $this->correcaoFinanceiraRepository->all();

        $tipoMovimento = mb_strtoupper($tipo);

        return view('financeiro.agendamento.form', compact(
            'planosContas', 'favorecidos', 'centrosResultados', 'projetos', 'tipoMovimento', 'correcoesFinanceira'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       try {

            $id = $request->get('id');
            if($id)
                $this->agendamentoRepository->update($id, Input::all());
            else {
                $agendamento = $this->agendamentoRepository->create(Input::all());
                $id = $agendamento->id;
            }

            Flash::success('Agendamento salvo com sucesso!');
            
            if($request['action'] == 'send_new')
                return Redirect::action('Financeiro\AgendamentoController@create', array('tipo' => strtolower( Input::get('tipo_movimento'))) )->with('message', 'Success');
            elseif($request['action'] == 'send_baixa')
                return Redirect::action('Financeiro\AgendamentoController@baixa', array('id' => $id) )->with('message', 'Success');
            else
                return Redirect::action('Financeiro\AgendamentoController@index', array('tipo' => strtolower( Input::get('tipo_movimento'))) )->with('message', 'Success');

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\AgendamentoController@create', array('tipo' => strtolower( Input::get('tipo_movimento'))) )->withErrors($e->getErrors())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $agendamento = $this->agendamentoRepository->find($id);
        
        $planosContas = $this->planoContaRepository->all();
        $centrosResultados = $this->centroResultadoRepository->all();
        $projetos = $this->projetoRepository->all();
        $favorecidos = $this->favorecidoRepository->lists();
        $correcoesFinanceira = $this->correcaoFinanceiraRepository->all();

        $tipoMovimento = $agendamento->tipo_movimento;

        return view('financeiro.agendamento.form', compact(
            'agendamento', 'planosContas', 'favorecidos', 'centrosResultados', 'projetos', 'tipoMovimento', 'correcoesFinanceira'
        ));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function baixa(Request $request, $id)
    {
        $agendamento = $this->agendamentoRepository->find($id);
        $bancos = $this->bancoRepository->lists();
        $formasFinanceira = $this->formaFinanceiraRepository->lists();
        $descontos = $this->descontoRepository->lists();


        /* Calcula Juros e Multas */

        $data_vencimento = new DateTime(date('Y-m-d'));
        $diff = $data_vencimento->diff( new DateTime($agendamento->data_vencimento) );
        $diasDiferenca = $diff->days;
        $estaVencido = $diff->invert;

        $percetualMulta = 0;
        $percetualJuros = 0;
        $valorSaldo = $agendamento->valor_saldo;

        $valorMulta     = round(0,2);
        $valorJuros     = round(0,2);
        $valorDesconto  = round(0,2);

        if(isset($agendamento->correcao_financeira)) {
            $percetualMulta = $agendamento->correcao_financeira->aliquota_multa;
            $percetualJuros = $agendamento->correcao_financeira->aliquota_juros;
            $limiteMulta = $agendamento->correcao_financeira->limite_multa;

            // calcula a multa máxima
            $valorMultaLimite   = round( $valorSaldo * ($limiteMulta/100), 2);


            if($estaVencido == 1 && $diasDiferenca>0) {
            
                // periodo de multa
                $periodoMulta = $agendamento->correcao_financeira->periodo_multa;
                switch ($periodoMulta) {
                    case '1':
                        $tempoMulta = $diasDiferenca;
                        break;
                    case '2':
                        $tempoMulta = ceil($diasDiferenca/30); // Perído Mensal da Multa
                        break;
                    case '3':
                        $tempoMulta = ceil($diasDiferenca/360); // Perído Anual da Multa
                        break;
                    default:
                        # code...
                        break;
                }

                // periodo de juros
                $periodoJuros = $agendamento->correcao_financeira->periodo_juros;
                switch ($periodoJuros) {
                    case '1':
                        $tempoJuros = $diasDiferenca;
                        break;
                    case '2':
                        $tempoJuros = ceil($diasDiferenca/30); // Perído Mensal da Juros
                        break;
                    case '3':
                        $tempoJuros = ceil($diasDiferenca/360); // Perído Anual da Juros
                        break;
                    default:
                        # code...
                        break;
                }
            
                $valorMulta     = round( $valorSaldo * ( ($percetualMulta*$tempoMulta)/100), 2);
                $valorJuros     = round( $valorSaldo * ( ($percetualJuros*$tempoJuros)/100), 2);
                $valorDesconto  = round( 0,2);

                //Limite o valor da Multa
                if($valorMulta > $valorMultaLimite)
                    $valorMulta = $valorMultaLimite;
            }
            
        }

        $valorLancamento  = round( $valorSaldo + $valorMulta + $valorJuros - $valorDesconto , 2);

        return view('financeiro.agendamento.baixa', compact(
            'agendamento', 'bancos', 'valorMulta', 'valorJuros', 'valorDesconto', 'valorLancamento', 'diasDiferenca', 'estaVencido', 'formasFinanceira', 'descontos'
        )); 
    }

    public function storeBaixa(Request $request) {

        try {
            //
            $agendamento = $this->agendamentoRepository->find(Input::get('agendamento_id'));
            $formaFinanceira = $this->formaFinanceiraRepository->find(Input::get('forma_financeira_id'));
            
            $attributes = Input::all();
            

            // atualiza o saldo
            $valorLancamento = decimalFormat($attributes['valor_lancamento']);
            $valorMulta = $attributes['valor_multa'] = decimalFormat($attributes['valor_multa']);
            $valorJuros = $attributes['valor_juros'] = decimalFormat($attributes['valor_juros']);
            $valorDesconto = $attributes['valor_desconto'] = decimalFormat($attributes['valor_desconto']);
            
            $success = false;
            if($agendamento) {

                $attributes['valor_lancamento'] = ( ($valorLancamento + $valorDesconto) - ($valorMulta + $valorJuros)  );
                
                $attributes['favorecido_id'] = $agendamento->favorecido->id;
                $attributes['numero_titulo'] = $agendamento->numero_titulo;
                $attributes['numero_parcela'] = $agendamento->numero_parcela;
                $attributes['tipo_movimento'] = $agendamento->tipo_movimento;
                $attributes['tipo_baixa'] = "AGD";

                // faz a liquidação de acordo a Forma Financeira
                if($formaFinanceira->liquida == 'S')
                    $attributes['data_liquidacao'] = convertDateEn($attributes['data_lancamento']);
                

                $success = $this->lancamentoBancarioRepository->create($attributes);
                
                if(is_bool($success)) {
                   Flash::error('Favor selecione o Desconto!');
                   return Redirect::action('Financeiro\AgendamentoController@baixa', array('id' => strtolower( $agendamento->id )) )->withInput();
                }
            }
            
            if($success) {
                
                $valorSaldo = $agendamento->valor_saldo - $attributes['valor_lancamento'];
                
                $this->agendamentoRepository->updateSaldo($agendamento->id, $valorSaldo);

                Flash::success('Lançamento efetuado com sucesso!');
            }
            else
                Flash::error('Error ao efeturar Lançamento!');

            return Redirect::action('Financeiro\AgendamentoController@index')->with('message', 'Success');

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\AgendamentoController@baixa', array('id' => strtolower( $agendamento->id )) )->withErrors($e->getErrors())->withInput();
        }

    }

    public function excluiBaixa($id) {

        try {
            //
            $lancamento = $this->lancamentoBancarioRepository->find($id);
            
            if($lancamento!=NULL && $lancamento->id>0) {

                // atualiza o saldo
                $agendamento = $this->agendamentoRepository->find($lancamento->agendamento_id);
                $valorSaldo = $agendamento->valor_saldo + $lancamento->valor_lancamento;
                
                $this->agendamentoRepository->updateSaldo($agendamento->id, $valorSaldo);
                
                $agendamento->save();

                if( $agendamento->save() ) {
                    
                    $this->lancamentoBancarioRepository->delete($lancamento->id);

                    Flash::success('Baixa de lançamento deletado com sucesso!');
                }
                else
                    Flash::error('Error ao deletar baixa!');

                return Redirect::action('Financeiro\AgendamentoController@edit', array('id'=>$agendamento->id))->with('message', 'Success');

            }

            Flash::error('Error ao deletar baixar!');
            return Redirect::action('Financeiro\AgendamentoController@index', array() );

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\AgendamentoController@edit', array('id' => strtolower( $agendamento->id )) )->withErrors($e->getErrors())->withInput();
        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //
        try {
            
            $agendamento = $this->agendamentoRepository->find($id);

            if($agendamento) {
                $this->agendamentoRepository->delete($agendamento->id);

                Flash::success('Agendamento deletado com sucesso!');

                return Redirect::action('Financeiro\AgendamentoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Agendamento!');

                return Redirect::action('Financeiro\AgendamentoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\AgendamentoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
