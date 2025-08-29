<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\Unidade;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\UnidadeRepository;
use App\Exceptions\Validation\ValidationException;

class UnidadeController extends Controller
{
    //
    protected $unidadeRepository;
    protected $per_page;

    public function __construct(UnidadeRepository $armazem)
    {
        $this->unidadeRepository = $armazem;

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

        $unidades = $this->unidadeRepository->paginate($page, $this->per_page, false, $where);

        return view('cadastros.unidade.index', [
            'unidades' => $unidades,
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
        return view('cadastros.unidade.form');
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
                $this->unidadeRepository->update($id, Input::all());
            else
                $this->unidadeRepository->create(Input::all());

            Flash::success('Unidade de Medida salvo com sucesso!');

            return Redirect::action('Cadastro\UnidadeController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\UnidadeController@create')->withErrors($e->getErrors())->withInput();
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
        $unidade = $this->unidadeRepository->find($id);

        return view('cadastros.unidade.form', ['unidade' => $unidade]);
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
    public function destroy(Request $request, Unidade $unidade)
    {
        //
        //
        try {
            
            $unidade = $this->unidadeRepository->find($unidade->id);

            if($unidade) {
                
                if (count($unidade->produtos)) {
                    Flash::error('Error ao deletar Unidade de Medida! (Relacionamento com produtos)');
                    return Redirect::action('Cadastro\UnidadeController@index')->with('message', 'error');
                }  else {
                    $this->unidadeRepository->delete($unidade->id);

                    Flash::success('Unidade de Medida deletado com sucesso!');
                    return Redirect::action('Cadastro\UnidadeController@index')->with('message', 'Success');
                }
            }  else {
                Flash::error('Error ao deletar Unidade de Medida!');
                return Redirect::action('Cadastro\UnidadeController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\UnidadeController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
