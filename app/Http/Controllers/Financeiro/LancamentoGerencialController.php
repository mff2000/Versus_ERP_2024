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

use App\Repositories\Financeiro\LancamentoGerencialRepository;
use App\Repositories\Cadastro\ContaContabelRepository;
use App\Repositories\Cadastro\CentroResultadoRepository;
use App\Repositories\Cadastro\ProjetoRepository;
use App\Repositories\Cadastro\FavorecidoRepository;

use App\Exceptions\Validation\ValidationException;

class LancamentoGerencialController extends Controller
{
    //
    protected $planoContaRepository;
    protected $centroResultadoRepository;
    protected $projetoRepository;
    protected $favorecidoRepository;
    protected $lancamentoGerencialRepository;
    protected $per_page;
    
    public function __construct(
            ContaContabelRepository $plano,
            ProjetoRepository $projeto,
            CentroResultadoRepository $centro,
            FavorecidoRepository $favorecido,
            LancamentoGerencialRepository $lancamento

    ) {
        $this->planoContaRepository = $plano;
        $this->centroResultadoRepository = $centro;
        $this->projetoRepository = $projeto;
        $this->favorecidoRepository = $favorecido;
        $this->lancamentoGerencialRepository = $lancamento;
        
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

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'data_lancamento';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'DESC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;

        if(isset($filtros['data_lancamento']) and !empty($filtros['data_lancamento'])) {
            $datas = explode(" - ", $filtros['data_lancamento']);
            $where['data_lancamento'] = ['whereBetween', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];
        }
        if(isset($filtros['favorecido_id']) && !empty($filtros['favorecido_id'])) {
            $where['favorecido_id'] = $filtros['favorecido_id'];
        }
        if(isset($filtros['historico']) && !empty($filtros['historico'])) {
            $where['historico'] = ['like', '%'.$filtros['historico'].'%'];
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
             $lancamentos = $this->lancamentoGerencialRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $lancamentos = $this->lancamentoGerencialRepository->paginate($page, $this->per_page, $orderBy, $where);

        $favorecidos = $this->favorecidoRepository->lists();

        return view('financeiro.lancamento_gerencial.index', [
            'lancamentos' => $lancamentos,
            'filtros' => $filtros,
            'favorecidos' => $favorecidos,
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
        $favorecidos = $this->favorecidoRepository->lists();
       
        return view('financeiro.lancamento_gerencial.form', compact(
            'planosContas', 'favorecidos', 'centrosResultados', 'projetos'
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
                $this->lancamentoGerencialRepository->update($id, $attributes);
            else {
                $this->lancamentoGerencialRepository->create($attributes);
            }

            Flash::success('Lançamento salvo com sucesso!');

            return Redirect::action('Financeiro\LancamentoGerencialController@create', array() )->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\LancamentoGerencialController@create', array() )->withErrors($e->getErrors())->withInput();
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
        $lancamento = $this->lancamentoGerencialRepository->find($id);
        
        $planosContas = $this->planoContaRepository->all();
        $centrosResultados = $this->centroResultadoRepository->all();
        $projetos = $this->projetoRepository->all();
        $favorecidos = $this->favorecidoRepository->lists();

        return view('financeiro.lancamento_gerencial.form', compact(
            'lancamento', 'planosContas', 'favorecidos', 'centrosResultados', 'projetos'
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
            
            $lancamento = $this->lancamentoGerencialRepository->find($id);

            if($lancamento) {

                $this->lancamentoGerencialRepository->delete($lancamento->id);

                Flash::success('Lançamento deletado com sucesso!');

                return Redirect::action('Financeiro\LancamentoGerencialController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Lançamento!');

                return Redirect::action('Financeiro\LancamentoGerencialController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\LancamentoGerencialController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
