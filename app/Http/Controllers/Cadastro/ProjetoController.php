<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\Projeto;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\ProjetoRepository;
use App\Exceptions\Validation\ValidationException;

class ProjetoController extends Controller
{
    //
    protected $projetoRepository;

    public function __construct(ProjetoRepository $projetoRepository)
    {
        $this->projetoRepository = $projetoRepository;

        // verifica login
        $this->middleware('auth');
    }

 	public function index()
    {

        $projetos = $this->projetoRepository->paginate();

        return view('cadastros.projeto.index', [
            'projetos' => $projetos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {	

    	$contaSuperior = $this->projetoRepository->find($id);

        return view('cadastros.projeto.form',[
        	'contaSuperior' => $contaSuperior
        ]);
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
                $this->projetoRepository->update($id, Input::all());
            else
                $this->projetoRepository->create(Input::all());

            Flash::success('Projeto salvo com sucesso!');

            return Redirect::action('Cadastro\ProjetoController@create', array('id' => $id))->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\ProjetoController@create')->withErrors($e->getErrors())->withInput();
        }
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
        $projeto = $this->projetoRepository->find($id);

        $contaSuperior = null;
        if($projeto->conta_superior!=null)
            $contaSuperior = $projeto->parent;

        return view('cadastros.projeto.form', ['projeto' => $projeto, 'contaSuperior' => $contaSuperior]);
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
            
            $projeto = $this->projetoRepository->find($id);
        
            
            if($projeto) {
                
                if($this->projetoRepository->delete($projeto->id))
                    Flash::success('Projeto deletado com sucesso!');

                return Redirect::action('Cadastro\ProjetoController@index')->with('message', 'success');
            
            }  else {

                 Flash::error('Error ao deletar Projeto!');

                return Redirect::action('Cadastro\ProjetoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\ProjetoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
