<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\ContaContabel;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\ContaContabelRepository;
use App\Exceptions\Validation\ValidationException;

class ContaContabelController extends Controller
{
    //
    protected $contaRepository;

    public function __construct(ContaContabelRepository $contaRepository)
    {
        $this->contaRepository = $contaRepository;

        // verifica login
        $this->middleware('auth');
    }

 	public function index()
    {

        $contas = $this->contaRepository->paginate();

        return view('cadastros.conta_contabil.index', [
            'contas' => $contas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {	

    	$contaSuperior = $this->contaRepository->find($id);

        return view('cadastros.conta_contabil.form',[
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
                $conta = $this->contaRepository->update($id, Input::all());
            else
                $conta = $this->contaRepository->create(Input::all());

            Flash::success('Conta Contábil salvo com sucesso!');

            return Redirect::action('Cadastro\ContaContabelController@create', array('id' => $conta->id))->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\ContaContabelController@create')->withErrors($e->getErrors())->withInput();
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
        $conta = $this->contaRepository->find($id);

        $contaSuperior = null;
        if($conta->conta_superior!=null)
            $contaSuperior = $conta->parent;

        return view('cadastros.conta_contabil.form', ['contaContabil' => $conta, 'contaSuperior' => $contaSuperior]);
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
            
            $conta = $this->contaRepository->find($id);
                       
            if($conta) {
                
                if($this->contaRepository->delete($conta->id))
                    Flash::success('Plano de conta deletado com sucesso!');

                return Redirect::action('Cadastro\ContaContabelController@index')->with('message', 'success');
            
            }  else {

                 Flash::error('Error ao deletar Plano de Conta!');

                return Redirect::action('Cadastro\ContaContabelController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\ContaContabelController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
