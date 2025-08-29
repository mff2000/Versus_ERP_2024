<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\TipoTransacao;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\TipoTransacaoRepository;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\Cadastro\ContaContabelRepository;
use App\Repositories\Cadastro\CentroResultadoRepository;
use App\Repositories\Cadastro\CfopRepository;

class TipoTransacaoController extends Controller
{
    //
    protected $tipoTransacaoRepository;
    protected $planoContaRepository;
    protected $centroResultadoRepository;
    protected $cfopRepository;
    protected $per_page;

    public function __construct(TipoTransacaoRepository $tipo,ContaContabelRepository $plano, CentroResultadoRepository $centro, CfopRepository $cfop)
    {
        $this->tipoTransacaoRepository = $tipo;
        $this->planoContaRepository = $plano;
        $this->centroResultadoRepository = $centro;
        $this->cfopRepository = $cfop;

        $this->per_page = config('versus.per_page');

        // verifica login
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $filtros = Input::get('filtros');
        $page = (Input::get('page') != "") ? Input::get('page') : 1;
        $where = null;

        if(isset($filtros['descricao']) and !empty($filtros['descricao'])) {
            $nome = $filtros['descricao'];
            $where['descricao'] = ['like', '%'.$nome.'%' ];
        }

        $tipos = $this->tipoTransacaoRepository->paginate($page, $this->per_page, false, $where);

        return view('cadastros.tipo_transacao.index', [
            'tipos' => $tipos,
            'filtros' => $filtros
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cfops = $this->cfopRepository->lists();
        $planosConta = $this->planoContaRepository->all();
        $centrosResultado = $this->centroResultadoRepository->all();

        return view('cadastros.tipo_transacao.form', compact('cfops', 'planosConta', 'centrosResultado'));
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
                $this->tipoTransacaoRepository->update($id, Input::all());
            else
                $this->tipoTransacaoRepository->create(Input::all());

            Flash::success('Tipo de Transação salvo com sucesso!');

            return Redirect::action('Cadastro\TipoTransacaoController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\TipoTransacaoController@create')->withErrors($e->getErrors())->withInput();
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
        $cfops = $this->cfopRepository->lists();
        $planosConta = $this->planoContaRepository->all();
        $centrosResultado = $this->centroResultadoRepository->all();
        //
        $tipoTransacao = $this->tipoTransacaoRepository->find($id);

        return view('cadastros.tipo_transacao.form', compact('tipoTransacao', 'planosConta', 'centrosResultado', 'cfops'));
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
    public function destroy(Request $request, TipoTransacao $tipoTransacao)
    {
        //
        //
        try {
            
            $tipoTransacao = $this->tipoTransacaoRepository->find($tipoTransacao->id);

            if($tipoTransacao) {
                $this->tipoTransacaoRepository->delete($tipoTransacao->id);

                Flash::success('Tipo de Transação deletado com sucesso!');

                return Redirect::action('Cadastro\TipoTransacaoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Tipo de Transação!');

                return Redirect::action('Cadastro\TipoTransacaoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\TipoTransacaoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
