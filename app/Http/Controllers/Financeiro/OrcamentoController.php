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

use App\Repositories\Financeiro\OrcamentoRepository;
use App\Repositories\Cadastro\ContaContabelRepository;
use App\Repositories\Cadastro\CentroResultadoRepository;
use App\Repositories\Cadastro\ProjetoRepository;
use App\Repositories\Cadastro\FavorecidoRepository;

use App\Exceptions\Validation\ValidationException;

class OrcamentoController extends Controller
{
    //
    protected $planoContaRepository;
    protected $centroResultadoRepository;
    protected $projetoRepository;
    protected $orcamentoRepository;
    protected $per_page;

    public function __construct(
            ContaContabelRepository $plano,
            ProjetoRepository $projeto,
            CentroResultadoRepository $centro,
            FavorecidoRepository $favorecido,
            OrcamentoRepository $orcamento

    ) {
        $this->planoContaRepository = $plano;
        $this->centroResultadoRepository = $centro;
        $this->projetoRepository = $projeto;
        $this->favorecidoRepository = $favorecido;
        $this->orcamentoRepository = $orcamento;
        
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

        $filtros = Input::get('filtros');
        if(!empty($filtros))
            $request->session()->put('filtros', $filtros);
        else {
            if($request->session()->has('filtros'))
                $filtros = $request->session()->get('filtros');
        }

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'data_competencia';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'DESC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;

        if(isset($filtros['data_competencia']) and !empty($filtros['data_competencia'])) {
            $datas = explode(" - ", $filtros['data_competencia']);
            $where['data_competencia'] = ['whereBetween', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];
        }
        if(isset($filtros['data_vencimento']) and !empty($filtros['data_vencimento'])) {
            $datas = explode(" - ", $filtros['data_vencimento']);
            $where['data_vencimento'] = ['whereBetween', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];
        }
        if(isset($filtros['historico']) && !empty($filtros['historico'])) {
            $where['historico'] = ['like', '%'.$filtros['historico'].'%'];
        }
        if(isset($filtros['tipo_movimento']) && !empty($filtros['tipo_movimento'])) {
            $where['tipo_movimento'] = $filtros['tipo_movimento'];
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
            $orcamentos = $this->orcamentoRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $orcamentos = $this->orcamentoRepository->paginate($page, $this->per_page, $orderBy, $where);

       
        return view('financeiro.orcamento.index', [
            'orcamentos' => $orcamentos,
            'filtros' => $filtros,
            'orderBy'   => $orderBy,
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
       
        return view('financeiro.orcamento.form', compact(
            'planosContas', 'centrosResultados', 'projetos'
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
                $this->orcamentoRepository->update($id, $attributes);
            else {
                $this->orcamentoRepository->create($attributes);
            }

            Flash::success('Orçamento salvo com sucesso!');

            return Redirect::action('Financeiro\OrcamentoController@create', array() )->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\OrcamentoController@create', array() )->withErrors($e->getErrors())->withInput();
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
        $orcamento = $this->orcamentoRepository->find($id);
        
        $planosContas = $this->planoContaRepository->all();
        $centrosResultados = $this->centroResultadoRepository->all();
        $projetos = $this->projetoRepository->all();

        return view('financeiro.orcamento.form', compact(
            'orcamento', 'planosContas', 'centrosResultados', 'projetos'
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
            
            $orcamento = $this->orcamentoRepository->find($id);

            if($orcamento) {

                $this->orcamentoRepository->delete($orcamento->id);

                Flash::success('Orçamento deletado com sucesso!');

                return Redirect::action('Financeiro\OrcamentoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Orçamento!');

                return Redirect::action('Financeiro\OrcamentoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\OrcamentoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
