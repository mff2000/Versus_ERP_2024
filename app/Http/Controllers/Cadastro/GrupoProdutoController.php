<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\GrupoProduto;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\GrupoProdutoRepository;
use App\Exceptions\Validation\ValidationException;

class GrupoProdutoController extends Controller
{
    //
    protected $grupoRepository;
    protected $per_page;

    public function __construct(GrupoProdutoRepository $grupo)
    {
        $this->grupoRepository = $grupo;

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

        $grupos = $this->grupoRepository->paginate($page, $this->per_page, false, $where);

        return view('cadastros.grupo_produto.index', [
            'grupos' => $grupos,
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
        return view('cadastros.grupo_produto.form');
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
                $this->grupoRepository->update($id, Input::all());
            else
                $this->grupoRepository->create(Input::all());

            Flash::success('Grupo de Produto salvo com sucesso!');

            return Redirect::action('Cadastro\GrupoProdutoController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\GrupoProdutoController@create')->withErrors($e->getErrors())->withInput();
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
        $grupo = $this->grupoRepository->find($id);

        return view('cadastros.grupo_produto.form', ['grupo' => $grupo]);
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
    public function destroy(Request $request, GrupoProduto $grupoProduto)
    {
        //
        //
        try {
            
            $grupo = $this->grupoRepository->find($grupoProduto->id);

            if($grupo) {

                if (count($grupo->produtos)) {
                    Flash::error('Error ao deletar Grupo de Produto! (Relacionamento com produtos)');
                    return Redirect::action('Cadastro\GrupoProdutoController@index')->with('message', 'error');
                }  else {
                    $this->grupoRepository->delete($unidade->id);

                    Flash::success('Grupo de Produto deletado com sucesso!');
                    return Redirect::action('Cadastro\GrupoProdutoController@index')->with('message', 'Success');
                }

            }  else {
                
                Flash::error('Error ao deletar Grupo de Produto!');
                return Redirect::action('Cadastro\GrupoProdutoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\GrupoProdutoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
