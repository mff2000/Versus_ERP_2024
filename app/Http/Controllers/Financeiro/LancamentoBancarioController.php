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

use App\Repositories\Financeiro\AgendamentoRepository;
use App\Repositories\Financeiro\LancamentoBancarioRepository;
use App\Repositories\Cadastro\ContaContabelRepository;
use App\Repositories\Cadastro\CentroResultadoRepository;
use App\Repositories\Cadastro\ProjetoRepository;
use App\Repositories\Cadastro\FavorecidoRepository;
use App\Repositories\Cadastro\BancoRepository;
use App\Repositories\Cadastro\FormaFinanceiraRepository;
use App\Repositories\Cadastro\DescontoRepository;

use App\Exceptions\Validation\ValidationException;

class LancamentoBancarioController extends Controller
{
    //
    protected $planoContaRepository;
    protected $centroResultadoRepository;
    protected $projetoRepository;
    protected $favorecidoRepository;
    protected $bancoRepository;
    protected $lancamentoBancarioRepository;
    protected $formaFinanceiraRepository;
    protected $descontoRepository;
    protected $agendamentoRepository;

    protected $per_page;

    public function __construct(
            ContaContabelRepository $plano,
            ProjetoRepository $projeto,
            CentroResultadoRepository $centro,
            FavorecidoRepository $favorecido,
            BancoRepository $banco,
            LancamentoBancarioRepository $lancamento,
            FormaFinanceiraRepository $forma,
            DescontoRepository $desconto,
            AgendamentoRepository $agendamentoRepository
    ) {
        $this->planoContaRepository = $plano;
        $this->centroResultadoRepository = $centro;
        $this->projetoRepository = $projeto;
        $this->favorecidoRepository = $favorecido;
        $this->bancoRepository = $banco;
        $this->lancamentoBancarioRepository = $lancamento;
        $this->formaFinanceiraRepository = $forma;
        $this->descontoRepository = $desconto;
        $this->agendamentoRepository = $agendamentoRepository;

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

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'id';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'DESC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;

        if(isset($filtros['id']) && !empty($filtros['id'])) {
            $id = trim($filtros['id']);
            $where['lancamentos_bancarios.id'] = $id;
        } else {

            if(isset($filtros['data_lancamento']) and !empty($filtros['data_lancamento'])) {
                $datas = explode(" - ", $filtros['data_lancamento']);
                $where['data_lancamento'] = ['whereBetweenDate', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];
            }
            if(isset($filtros['data_liquidacao']) && !empty($filtros['data_liquidacao'])) {
                $datas = explode(" - ", $filtros['data_liquidacao']);
                $where['data_liquidacao'] = ['whereBetweenDate', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];
            }
            if(isset($filtros['favorecido_id']) && !empty($filtros['favorecido_id'])) {
                $where['favorecido_id'] = $filtros['favorecido_id'];
            }
            if(isset($filtros['banco_id']) && !empty($filtros['banco_id'])) {
                $where['banco_id'] = $filtros['banco_id'];
            }
            if(isset($filtros['tipo_movimento']) && !empty($filtros['tipo_movimento'])) {
                $where['tipo_movimento'] = $filtros['tipo_movimento'];
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
            $lancamentos = $this->lancamentoBancarioRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $lancamentos = $this->lancamentoBancarioRepository->paginate($page, $this->per_page, $orderBy, $where);

        
        $favorecidos = $this->favorecidoRepository->lists();
        $bancos = $this->bancoRepository->lists();

        return view('financeiro.lancamento.index', [
            'lancamentos' => $lancamentos,
            'favorecidos' => $favorecidos,
            'status'    => $status,
            'filtros' => $filtros,
            'bancos' => $bancos,
            'orderBy' => $orderBy,
            'per_page'  => $per_page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $planosContas = $this->planoContaRepository->all();
        $centrosResultados = $this->centroResultadoRepository->all();
        $projetos = $this->projetoRepository->all();
        $favorecidos = $this->favorecidoRepository->lists();
        $bancos = $this->bancoRepository->lists();
        $formasFinanceira = $this->formaFinanceiraRepository->lists();
        $descontos = $this->descontoRepository->lists();

        return view('financeiro.lancamento.form', compact(
            'planosContas', 'favorecidos', 'centrosResultados', 'projetos', 'bancos', 'formasFinanceira', 'descontos'
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
            $formaFinanceira = $this->formaFinanceiraRepository->find(Input::get('forma_financeira_id'));
            
            // Dados do Agendamento criado por Lançamento
            $dataAgendamento = $attributes;
            $dataAgendamento['data_competencia'] = $attributes['data_lancamento'];
            $dataAgendamento['data_vencimento'] = $attributes['data_lancamento'];
            $dataAgendamento['valor_titulo'] = $attributes['valor_lancamento'];
            $dataAgendamento['parcela_inicial'] = '1';
            $dataAgendamento['quantidade_parcelas'] = '1';
            $dataAgendamento['periodo_repeticao'] = 0;
            $dataAgendamento['valor_saldo'] = 0;
            //Rateios
            $dataAgendamento['tblAppendGrid_rowOrder'] = '1';
            $dataAgendamento['tblAppendGrid_RecordId_1'] = '0';
            $dataAgendamento['tblAppendGrid_plano_conta_id_1'] = $attributes['plano_conta_id'];
            $dataAgendamento['tblAppendGrid_centro_resultado_id_1'] = $attributes['centro_resultado_id'];
            $dataAgendamento['tblAppendGrid_projeto_id_1'] = $attributes['projeto_id'];
            $dataAgendamento['tblAppendGrid_rateio_porcentagem_1'] = '100 %';
            $dataAgendamento['tblAppendGrid_rateio_valor_1'] = $attributes['valor_lancamento'];

            if($id) {
                $lancamento = $this->lancamentoBancarioRepository->find($id);

                $dataAgendamento['tblAppendGrid_RecordId_1'] = $lancamento->agendamento->rateios[0]->id;

                $agendamento = $this->agendamentoRepository->update($lancamento->agendamento_id, $dataAgendamento);
            }
            else {
                $agendamento = $this->agendamentoRepository->create($dataAgendamento);
            }
            // Dados do Agendamento criado por Lançamento #FIM

            if($formaFinanceira->liquida =='S')
                $attributes['data_liquidacao'] = date('Y-m-d');
            else
                $attributes['data_liquidacao'] = null;

            // Sempre vinculado ao agendamento;
            $attributes['agendamento_id'] = $agendamento->id;

            // Tipo de Baixa Direto
            $attributes['tipo_baixa'] = 'NOR';
            if($id)
                $this->lancamentoBancarioRepository->update($id, $attributes);
            else {
                $this->lancamentoBancarioRepository->create($attributes);
            }

            Flash::success('Lançamento salvo com sucesso!');

            if($id > 0)
                return Redirect::action('Financeiro\LancamentoBancarioController@index', array() )->with('message', 'Success');
            else
                return Redirect::action('Financeiro\LancamentoBancarioController@create', array() )->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\LancamentoBancarioController@create', array() )->withErrors($e->getErrors())->withInput();
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
        $lancamento = $this->lancamentoBancarioRepository->find($id);
        
        $planosContas = $this->planoContaRepository->all();
        $centrosResultados = $this->centroResultadoRepository->all();
        $projetos = $this->projetoRepository->all();
        $favorecidos = $this->favorecidoRepository->lists();
        $bancos = $this->bancoRepository->lists();
        $formasFinanceira = $this->formaFinanceiraRepository->lists();
        $descontos = $this->descontoRepository->lists();

        $valor_saldo = $lancamento->valor_lancamento - ($lancamento->valor_multa+$lancamento->valor_juros) + $lancamento->valor_desconto;

        return view('financeiro.lancamento.form', compact(
            'lancamento', 'planosContas', 'favorecidos', 'centrosResultados', 'projetos', 'bancos', 'valor_saldo', 'formasFinanceira', 'descontos'
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
            
            $lancamento = $this->lancamentoBancarioRepository->find($id);

            if($lancamento) {

                $this->agendamentoRepository->delete($lancamento->agendamento_id);
                $this->lancamentoBancarioRepository->delete($lancamento->id);

                Flash::success('Lançamento deletado com sucesso!');

                return Redirect::action('Financeiro\LancamentoBancarioController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Lançamento!');

                return Redirect::action('Financeiro\LancamentoBancarioController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\LancamentoBancarioController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
