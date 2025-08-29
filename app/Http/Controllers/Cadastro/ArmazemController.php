<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\Armazem;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\ArmazemRepository;
use App\Exceptions\Validation\ValidationException;

class ArmazemController extends Controller
{
    //
    protected $armazemRepository;
    protected $per_page;

    public function __construct(ArmazemRepository $armazem)
    {
        $this->armazemRepository = $armazem;

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

        $armazens = $this->armazemRepository->paginate($page, $this->per_page, false, $where);

        return view('cadastros.armazem.index', [
            'armazens' => $armazens,
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
        return view('cadastros.armazem.form');
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
                $this->armazemRepository->update($id, Input::all());
            else
                $this->armazemRepository->create(Input::all());

            Flash::success('Armazem salvo com sucesso!');

            return Redirect::action('Cadastro\ArmazemController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\ArmazemController@create')->withErrors($e->getErrors())->withInput();
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
        $armazem = $this->armazemRepository->find($id);

        return view('cadastros.armazem.form', ['armazem' => $armazem]);
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
    public function destroy(Request $request, Armazem $armazem)
    {
        //
        //
        try {
            
            $armazem = $this->armazemRepository->find($armazem->id);

            if($armazem) {

                if (count($armazem->produtos)) {
                    Flash::success('Error ao deletar Armazém! (Relacionamento com produtos)');
                    return Redirect::action('Cadastro\ArmazemController@index')->with('message', 'error');
                }  else {
                    $this->armazemRepository->delete($armazem->id);

                    Flash::error('Armazém deletado com sucesso!');
                    return Redirect::action('Cadastro\ArmazemController@index')->with('message', 'Success');
                }
                
            } else {
                Flash::error('Error ao deletar Armazém! (Não encontrado)');
                return Redirect::action('Cadastro\ArmazemController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\ArmazemController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
