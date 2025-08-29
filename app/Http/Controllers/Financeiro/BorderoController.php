<?php

namespace App\Http\Controllers\Financeiro;

use Auth;
use Input;
use Flash;
use Redirect;
use DateTime;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Financeiro\BorderoRepository;
use App\Repositories\Cadastro\BancoRepository;
use App\Repositories\Financeiro\AgendamentoRepository;
use App\Repositories\Cadastro\FormaFinanceiraRepository;
use App\Repositories\Cadastro\DescontoRepository;
use App\Repositories\Financeiro\LancamentoBancarioRepository;
use App\Exceptions\Validation\ValidationException;
use App\Model\Financeiro\LancamentoBancario;

class BorderoController extends Controller
{
    //
    protected $borderoRepository;
    protected $bancoRepository;
    protected $agendamentoRepository;
    protected $lancamentoBancarioRepository;
    protected $formaFinanceiraRepository;
    protected $descontoRepository;

    protected $per_page;

    public function __construct(
            BorderoRepository $bordero,
            BancoRepository $banco,
            AgendamentoRepository $agendamento,
            LancamentoBancarioRepository $lancamento,
            FormaFinanceiraRepository $forma,
            DescontoRepository $desconto
    ) {
        $this->borderoRepository = $bordero;
        $this->bancoRepository = $banco;
        $this->agendamentoRepository = $agendamento;
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

        if( $request->input('session') ) {
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

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'data_emissao';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'DESC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;

        if(isset($filtros['id']) && !empty($filtros['id'])) {
            $id = trim($filtros['id']);
            $where['borderos.id'] = $id;
        } else {

            if(isset($filtros['data_emissao']) and !empty($filtros['data_emissao'])) {
                $datas = explode(" - ", $filtros['data_emissao']);
                $where['data_emissao'] = ['whereBetween', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];
            }
            if(isset($filtros['data_liquidacao']) and !empty($filtros['data_liquidacao'])) {
                $datas = explode(" - ", $filtros['data_liquidacao']);
                $where['data_liquidacao'] = ['whereBetween', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];
            }
            if(isset($filtros['tipo_bordero']) && !empty($filtros['tipo_bordero'])) {
                $where['tipo_bordero'] = $filtros['tipo_bordero'];
            }
            if(isset($filtros['descricao']) && !empty($filtros['descricao'])) {
                $where['descricao'] = ['like', '%'.$filtros['descricao'].'%'];
            }

            if(Input::get('status')) {
                
                $status = Input::get('status');

                if(in_array('L', $status) && !in_array('A', $status))
                    $where['data_liquidacao'] = ['!=', null];
                elseif(in_array('A', $status) && !in_array('L', $status))
                    $where['data_liquidacao'] = ['=', null];

            }

        }
        /*  PÁGINAÇÃO
         ***************************************************************************
         */

        $per_page = Input::get('per_page');
        if(isset($per_page) && is_numeric($per_page))
            $request->session()->put('per_page', $per_page);
        else
            $per_page = $request->session()->get('per_page');

        if($per_page !== null)
            $borderos = $this->borderoRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $borderos = $this->borderoRepository->paginate($page, $this->per_page, $orderBy, $where);


        return view('financeiro.bordero.index', [
            'borderos'  => $borderos,
            'filtros'   => $filtros,
            'orderBy'   => $orderBy,
            'per_page'  => $per_page,
            'status'    => $status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bancos = $this->bancoRepository->lists();
       
        return view('financeiro.bordero.form', compact(
            'bancos'
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
            $attributes = Input::all();                       

            if(isset($id) && $id>0)
                $this->borderoRepository->update($id, $attributes);
            else {
                $this->borderoRepository->create($attributes);
            }

            $bordero = $this->borderoRepository->get();

            Flash::success('Borderô salvo com sucesso!');

            return Redirect::action('Financeiro\BorderoController@edit', array('id'=>$bordero->id) )->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\BorderoController@create', array() )->withErrors($e->getErrors())->withInput();
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
        $bordero = $this->borderoRepository->find($id);

        $bancos = $this->bancoRepository->lists();

        return view('financeiro.bordero.form', compact(
            'bordero', 'bancos'
        ));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function busca_agendamento(Request $request)
    {

        $dataCompetencia = $dataVencimento = null;
        $id = $request->get('id');
        $attributes = Input::all();

        $bordero = $this->borderoRepository->find($id);

        // Campos de FIltros

        $where = array(
            'tipo_movimento' => $bordero->tipo_bordero,
            'valor_saldo' => ['>', 0],
            'bordero_id'=> NULL,
        );

        if(isset($attributes['historico']) && !empty($attributes['historico'])) {
            $historico = $attributes['historico'];
            $where['historico'] = ['like', '%'.$historico.'%'];
        }
        if(isset($attributes['favorecido']) && !empty($attributes['favorecido'])) {
            $favorecido = $attributes['favorecido'];
            $where['favorecidos.nome_empresarial'] = ['like', '%'.$favorecido.'%'];
            $where['favorecidos.nome_fantasia'] = ['like', '%'.$favorecido.'%'];
        }
        if(isset($attributes['data_competencia']) && !empty($attributes['data_competencia'])) {
            $dataCompetencia = explode(" - ", $attributes['data_competencia']);
            $where['data_competencia'] = ['whereBetween', array( convertDateEn(trim($dataCompetencia[0])), convertDateEn(trim($dataCompetencia[1]))) ];
        }
        if(isset($attributes['data_vencimento']) && !empty($attributes['data_vencimento'])) {
            $dataVencimento = explode(" - ", $attributes['data_vencimento']);
            $where['data_vencimento'] = ['whereBetween', array( convertDateEn(trim($dataVencimento[0])), convertDateEn(trim($dataVencimento[1]))) ];
        }

        // verifica se teve algum check marcado
        if(isset($attributes['agendamento_id'])) {
        foreach ($attributes['agendamento_id'] as $key => $value) {
            $agendamento = $this->agendamentoRepository->find($key);
            if($agendamento->bordero_id == null) {
                $agendamento->bordero_id = $bordero->id;
                $agendamento->save();
            
                //echo $agendamento->valor_saldo;
                $bordero->valor += $agendamento->valor_saldo;
                $bordero->saldo = $bordero->valor;
            }
        }}

        $bordero->save();


        $page = 1;
        if($request->get('page') == null)
            $page = $request->get('page');
        
        $agendamentos = $this->agendamentoRepository->paginate($page, env('PER_PAGE', 30), ['agendamentos.data_vencimento', 'DESC'], $where);

        if(isset($dataCompetencia))
            $dataCompetencia = implode(" - ", $dataCompetencia);
        if(isset($dataVencimento))
            $dataVencimento = implode(" - ", $dataVencimento);

        return view('financeiro.bordero.form', compact(
            'bordero', 'agendamentos', 'historico', 'favorecido', 'dataCompetencia', 'dataVencimento'
        )); 
    }

    public function delete_agendamento($id)
    {
        try {

            $agendamento = $this->agendamentoRepository->find($id);
            $borderoId = $agendamento->bordero_id;
            $agendamento->bordero_id = NULL;
            $agendamento->save();

            $bordero = $this->borderoRepository->find($borderoId);
            $bordero->valor -= $agendamento->valor_saldo;
            $bordero->saldo = $bordero->valor;

            $bordero->save();

            Flash::success('Agendamento retirado com sucesso!');
            return Redirect::action('Financeiro\BorderoController@edit', array('id'=>$borderoId) )->with('message', 'Success');
        } catch (ValidationException $e) {

            Flash::success('Erro ao retirar agendamento!');
            return Redirect::action('Financeiro\BorderoController@edit', array('id'=>$borderoId) )->withErrors($e->getErrors())->withInput();
        }
    }

    public function baixa(Request $request, $id) {

        $bordero = $this->borderoRepository->find($id);
        $bancos = $this->bancoRepository->lists();
        $formasFinanceira = $this->formaFinanceiraRepository->lists();
        $descontos = $this->descontoRepository->lists();

        $valorMulta = 0;
        $valorJuros = 0;
        $valorDesconto = 0;
        foreach ($bordero->agendamentos as $agendamento) {
            $data = $this->calculaJurosMultaDesconto($agendamento);
            $valorMulta += $data['valorMulta'];
            $valorJuros += $data['valorJuros'];
            $valorDesconto += $data['valorDesconto'];
        }
        $valorLancamento = round( $bordero->saldo + $valorMulta + $valorJuros - $valorDesconto , 2);

        return view('financeiro.bordero.baixa', compact(
            'bordero', 'bancos', 'formasFinanceira', 'descontos', 'valorMulta', 'valorJuros', 'valorDesconto', 'valorLancamento'
        )); 
    }

    public function storeBaixa(Request $request) {

        try {
            //
            $bordero = $this->borderoRepository->find(Input::get('bordero_id'));
            $formaFinanceira = $this->formaFinanceiraRepository->find(Input::get('forma_financeira_id'));
            $attributes = Input::all();

            // Calcula portecagem por bordero            
            $valorMultaTotal = 0;
            $valorJurosTotal = 0;
            $valorDescontoTotal = 0;
            foreach ($bordero->agendamentos as $agendamento) {
                $data = $this->calculaJurosMultaDesconto($agendamento);
                $valorMultaTotal += $data['valorMulta'];
                $valorJurosTotal += $data['valorJuros'];
                //$valorDescontoTotal += $data['valorDesconto'];
            }

            $valorMulta = decimalFormat($attributes['valor_multa']);
            $rateioPorcentoMulta = 0;
            if($valorJurosTotal>0)
                $rateioPorcentoMulta = ($valorMulta / $valorMultaTotal );
            
            $valorJuros = decimalFormat($attributes['valor_juros']);
            $rateioPorcentoJuros = 0;
            if($valorJurosTotal>0)
                $rateioPorcentoJuros = ($valorJuros / $valorJurosTotal );
            
            $valorDesconto = decimalFormat($attributes['valor_desconto']);
            $valorLancamento = decimalFormat($attributes['valor_lancamento']);
            $valorSaldo = ($valorLancamento + $valorDesconto) - ($valorMulta + $valorJuros);
            $rateioPorcentoLancamento = ( $valorSaldo / $bordero->saldo );

            /*$rateioPorcentoDesconto = 0;
            if($valorDesconto>0)
                $rateioPorcentoDesconto = 1 - ( ($valorSaldo) / $bordero->saldo );
*/
            // Reseto saldo border (novo saldo no foreach)
            $bordero->saldo = 0;
            $attributes['baixa_id'] = uniqid();

            foreach ($bordero->agendamentos as $key => $agendamento) {
                
                $data = $this->calculaJurosMultaDesconto($agendamento);

                if($agendamento) {
                    
                    $attributes['numero_titulo'] = $agendamento->numero_titulo;
                    $attributes['numero_parcela'] = $agendamento->numero_parcela;
                    $attributes['favorecido_id'] = $agendamento->favorecido->id;                    
                    $attributes['tipo_movimento'] = $agendamento->tipo_movimento;
                    $attributes['tipo_baixa'] = "BRD";
                    $attributes['valor_lancamento'] = $agendamento->valor_saldo * $rateioPorcentoLancamento;
                    $attributes['valor_multa'] = $this->calculaJurosMultaDesconto($agendamento)['valorMulta'] * $rateioPorcentoMulta;
                    $attributes['valor_juros'] = $this->calculaJurosMultaDesconto($agendamento)['valorJuros'] * $rateioPorcentoJuros;
                    $attributes['valor_desconto'] = ($attributes['valor_lancamento'] * $valorDesconto) / $valorSaldo;

                    // faz a liquidação de acordo a Forma Financeira
                    if($formaFinanceira->liquida == 'S' )
                        $attributes['data_liquidacao'] = convertDateEn($attributes['data_lancamento']);
                    
                    $attributes['agendamento_id'] = $agendamento->id;
                    $attributes['bordero_id'] = $bordero->id;
                    $this->lancamentoBancarioRepository = new LancamentoBancarioRepository(new LancamentoBancario());
                    $success = $this->lancamentoBancarioRepository->create($attributes);
                }

                if($success) {
                    // atualiza o saldo
                    $valorLancamentoAgendamento = $attributes['valor_lancamento'];
                    $valorMultaAgendamento = $attributes['valor_multa'];
                    $valorJurosAgendamento = $attributes['valor_juros'];
                    $valorDescontoAgendamento = $attributes['valor_desconto'];

                    $valorSaldoAgendamento = $agendamento->valor_saldo - $valorLancamentoAgendamento;
                    $agendamentoSalvo = $this->agendamentoRepository->updateSaldo($agendamento->id, $valorSaldoAgendamento);

                    $bordero->saldo += $agendamentoSalvo->valor_saldo;
                    // faz a liquidação de acordo a Forma Financeira
                    if($formaFinanceira->liquida == 'S' && $bordero->saldo == 0) {
                        $bordero->data_liquidacao = convertDateEn($attributes['data_lancamento']);
                    }

                }
                    
            }

            // salva para atualizar valor saldo
            $bordero->save();
            
            Flash::success('Lançamentos efetuados com sucesso!');
            return Redirect::action('Financeiro\BorderoController@index')->with('message', 'Success');

        } catch (ValidationException $e) {
            Flash::error('Error ao efeturar Lançamento!');
            return Redirect::action('Financeiro\BorderoController@baixa', array('id' => strtolower( $bordero->id )) )->withErrors($e->getErrors())->withInput();
        }

    }

    private function calculaJurosMultaDesconto($agendamento) {

        /* Calcula Juros e Multas */
        $data_lancamento = new DateTime(date('Y-m-d'));
        $valorMulta     = round(0,2);
        $valorJuros     = round(0,2);
        $valorDesconto  = round(0,2);

        $diff = $data_lancamento->diff( new DateTime($agendamento->data_vencimento) );
        $diasDiferenca = $diff->days;
        $estaVencido = $diff->invert;

        $percetualMulta = 0;
        $percetualJuros = 0;
        $valorSaldo = $agendamento->valor_saldo;

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
                    case '2':
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
                    case '2':
                        $tempoJuros = ceil($diasDiferenca/360); // Perído Anual da Juros
                        break;
                    default:
                        # code...
                        break;
                }
                
                $valorMulta = round( $valorSaldo * ( ($percetualMulta*$tempoMulta)/100), 2);
                $valorJuros = round( $valorSaldo * ( ($percetualJuros*$tempoJuros)/100), 2);
                $valorDesconto = round( 0,2);

                //Limite o valor da Multa
                if($valorMulta > $valorMultaLimite)
                    $valorMulta = $valorMultaLimite;
                
            }

        }
        
        $data['valorMulta'] = $valorMulta;
        $data['valorJuros'] = $valorJuros;
        $data['valorDesconto'] = $valorDesconto;

        return $data;
    }

    public function excluiBaixa($id) {

        try {
            //
            $lancamentos = $this->lancamentoBancarioRepository->findByBaixa($id);
            
            foreach ($lancamentos as $lancamento) {
                
                // atualiza o saldo
                $agendamento = $this->agendamentoRepository->find($lancamento->agendamento_id);
                $agendamento->valor_saldo = $agendamento->valor_saldo + $lancamento->valor_lancamento;

                // atualiza o saldo
                $bordero = $this->borderoRepository->find($agendamento->bordero_id);
                $bordero->saldo = $bordero->saldo + $lancamento->valor_lancamento;

                if($bordero->saldo == $bordero->valor) {
                    $bordero->data_liquidacao = NULL;
                }

                if( $agendamento->save() && $bordero->save() ) {
                    
                    $this->lancamentoBancarioRepository->delete($lancamento->id);

                    Flash::success('Baixa de lançamento deletado com sucesso!');
                }
                else
                    Flash::error('Error ao deletar baixa!');

            }

            return Redirect::action('Financeiro\BorderoController@edit', array('id'=>$bordero->id))->with('message', 'Success');

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\BorderoController@edit', array('id' => strtolower( $bordero->id )) )->withErrors($e->getErrors())->withInput();
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
            
            $bordero = $this->borderoRepository->find($id);

            if($bordero) {

                $this->borderoRepository->delete($bordero->id);

                Flash::success('Borderô deletado com sucesso!');

                return Redirect::action('Financeiro\BorderoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Borderô!');

                return Redirect::action('Financeiro\BorderoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\BorderoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
