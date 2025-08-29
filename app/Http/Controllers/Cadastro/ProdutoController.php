<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\Produto;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\ProdutoRepository;
use App\Repositories\Cadastro\ArmazemRepository;
use App\Repositories\Cadastro\UnidadeRepository;
use App\Repositories\Cadastro\GrupoProdutoRepository;
use App\Exceptions\Validation\ValidationException;

class ProdutoController extends Controller
{
    //
    protected $produtoRepository;
    protected $armazemRepository;
    protected $unidadeRepository;
    protected $grupoProdutoRepository;
    protected $per_page;

    public function __construct(ProdutoRepository $produto, ArmazemRepository $armazem, UnidadeRepository $unidade, GrupoProdutoRepository $grupo)
    {
        $this->produtoRepository = $produto;
        $this->armazemRepository = $armazem;
        $this->unidadeRepository = $unidade;
        $this->grupoProdutoRepository = $grupo;

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

        $produtos = $this->produtoRepository->paginate($page, $this->per_page, false, $where);

        return view('cadastros.produto.index', [
            'produtos' => $produtos,
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
        $unidades = $this->unidadeRepository->lists();
        $armazens = $this->armazemRepository->lists();
        $grupos = $this->grupoProdutoRepository->lists();

        return view('cadastros.produto.form', compact('unidades', 'armazens', 'grupos'));
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
                $this->produtoRepository->update($id, Input::all());
            else
                $this->produtoRepository->create(Input::all());

            Flash::success('Produto salvo com sucesso!');

            return Redirect::action('Cadastro\ProdutoController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\ProdutoController@create')->withErrors($e->getErrors())->withInput();
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
        $unidades = $this->unidadeRepository->lists();
        $armazens = $this->armazemRepository->lists();
        $grupos = $this->grupoProdutoRepository->lists();
        //
        $produto = $this->produtoRepository->find($id);

        return view('cadastros.produto.form', compact('produto', 'unidades', 'armazens', 'grupos'));
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
    public function destroy(Request $request, Produto $produto)
    {
        //
        //
        try {
            
            $produto = $this->produtoRepository->find($produto->id);

            if($produto) {
                $this->produtoRepository->delete($produto->id);

                Flash::success('Produto deletado com sucesso!');

                return Redirect::action('Cadastro\ProdutoController@index')->with('message', 'Success');
            }  else {
                 Flash::success('Error ao deletar Produto!');

                return Redirect::action('Cadastro\ProdutoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\ProdutoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
