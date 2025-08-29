<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\CondicaoPagamento;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\CondicaoPagamentoRepository;
use App\Exceptions\Validation\ValidationException;

class CondicaoPagamentoController extends Controller
{
    //
    protected $condicaoRepository;
    protected $per_page;

    public function __construct(CondicaoPagamentoRepository $armazem)
    {
        $this->condicaoRepository = $armazem;

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

        $condicoes = $this->condicaoRepository->paginate($page, $this->per_page, false, $where);

        return view('cadastros.condicao_pagamento.index', [
            'condicoes' => $condicoes,
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
        return view('cadastros.condicao_pagamento.form');
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
                $this->condicaoRepository->update($id, Input::all());
            else
                $this->condicaoRepository->create(Input::all());

            Flash::success('Codição de Pagamento salvo com sucesso!');

            return Redirect::action('Cadastro\CondicaoPagamentoController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\CondicaoPagamentoController@create')->withErrors($e->getErrors())->withInput();
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
        $condicao = $this->condicaoRepository->find($id);

        return view('cadastros.condicao_pagamento.form', ['condicao' => $condicao]);
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
    public function destroy(Request $request, CondicaoPagamento $condicaoPagamento)
    {
        //
        try {
            
            $condicao = $this->condicaoRepository->find($condicaoPagamento->id);

            if($condicao) {

                if (count($condicao->favorecidos)) {
                    Flash::error('Error ao deletar Condição de Pagamento! (Relacionamento com Favorecidos)');
                    return Redirect::action('Cadastro\CondicaoPagamentoController@index')->with('message', 'error');
                }  else {
                    
                    $this->condicaoRepository->delete($condicao->id);

                    Flash::success('Condição de Pagamento deletado com sucesso!');
                    return Redirect::action('Cadastro\CondicaoPagamentoController@index')->with('message', 'Success');
                }

            }  else {
                Flash::error('Error ao deletar Condição de Pagamento!');
                return Redirect::action('Cadastro\CondicaoPagamentoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\CondicaoPagamentoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
