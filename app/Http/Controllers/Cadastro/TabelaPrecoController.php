<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\TabelaPreco;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\TabelaPrecoRepository;
use App\Repositories\Cadastro\ProdutoRepository;
use App\Exceptions\Validation\ValidationException;

class TabelaPrecoController extends Controller
{
    //
    protected $tabelaRepository;
    protected $produtoRepository;
    protected $per_page;

    public function __construct(TabelaPrecoRepository $tabela, ProdutoRepository $produto)
    {
        $this->tabelaRepository = $tabela;
        $this->produtoRepository = $produto;

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

        $tabelas = $this->tabelaRepository->paginate($page, $this->per_page, false, $where);

        return view('cadastros.tabela_preco.index', [
            'tabelas' => $tabelas,
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
        $produtos = $this->produtoRepository->all();

        return view('cadastros.tabela_preco.form', compact('produtos'));
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
            if($id) {
                $this->tabelaRepository->update($id, Input::all());
            }
            else
                $this->tabelaRepository->create(Input::all());

            $tabela = $this->tabelaRepository->get();
            Flash::success('Tabela de Preço salva com sucesso!');

            return Redirect::action('Cadastro\TabelaPrecoController@edit', array('id'=>$tabela->id) )->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\TabelaPrecoController@create')->withErrors($e->getErrors())->withInput();
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
        $tabela = $this->tabelaRepository->find($id);
        $produtos = $this->produtoRepository->all();

        return view('cadastros.tabela_preco.form', compact('tabela', 'produtos') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function salvaProduto(Request $request)
    {   

        $id = $request->get('id');
        $tabela = $this->tabelaRepository->find($id);
        $attributes = Input::all();

        $produtos = null;
        // verifica se teve algum check marcado
        if(isset($attributes['produto_id'])) {
        foreach ($attributes['produto_id'] as $key => $value) {
            $produto = $this->produtoRepository->find($key);
            $produtos[] = $produto;
            
        }}

        if(count($produtos) > 0) 
            $tabela->produtos()->saveMany($produtos);

        return Redirect::action('Cadastro\TabelaPrecoController@edit', array('id'=>$id) )->with('message', 'Success');
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

    public function excluiProduto($tabelaId, $produtoId) {

        try {
            //
            $tabela = $this->tabelaRepository->find($tabelaId);
            
            $tabela->produtos()->detach([$produtoId]);

            return Redirect::action('Cadastro\TabelaPrecoController@edit', array('id'=>$tabela->id))->with('message', 'Success');

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\TabelaPrecoController@edit', array('id' => strtolower( $tabela->id )) )->withErrors($e->getErrors())->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TabelaPreco $tabelaPreco)
    {
        //
        //
        try {
            
            $tabelaPreco = $this->tabelaRepository->find($tabelaPreco->id);

            if($tabelaPreco) {
                
                $tabelaPreco->produtos()->detach();
                $this->tabelaRepository->delete($tabelaPreco->id);
                
                Flash::success('Tabela de Preço deletada com sucesso!');
                return Redirect::action('Cadastro\TabelaPrecoController@index')->with('message', 'Success');
                
            }  else {
                Flash::error('Error ao deletar Tabela Preco!');
                return Redirect::action('Cadastro\TabelaPrecoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\TabelaPrecoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
