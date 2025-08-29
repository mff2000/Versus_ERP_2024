<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\CentroResultado;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\CentroResultadoRepository;
use App\Exceptions\Validation\ValidationException;

class CentroResultadoController extends Controller
{
    //
    protected $centroRepository;

    public function __construct(CentroResultadoRepository $centroRepository)
    {
        $this->centroRepository = $centroRepository;

        // verifica login
        $this->middleware('auth');
    }

 	public function index()
    {

        $centros = $this->centroRepository->paginate();

        return view('cadastros.centro_resultado.index', [
            'centros' => $centros
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {	

    	$centroSuperior = $this->centroRepository->find($id);

        return view('cadastros.centro_resultado.form',[
        	'centroSuperior' => $centroSuperior
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
                $this->centroRepository->update($id, Input::all());
            else
                $this->centroRepository->create(Input::all());

            Flash::success('Centro de Resultado salvo com sucesso!');

            return Redirect::action('Cadastro\CentroResultadoController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\CentroResultadoController@create')->withErrors($e->getErrors())->withInput();
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
        $centro = $this->centroRepository->find($id);

        $centroSuperior = null;
        if($centro->conta_superior!=null)
            $centroSuperior = $centro->parent;

        return view('cadastros.centro_resultado.form', ['centroResultado' => $centro, 'centroSuperior' => $centroSuperior]);
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
            
            $centro = $this->centroRepository->find($id);
            
            if($centro) {
                
                if($this->centroRepository->delete($centro->id))
                    Flash::success('Centro de Resultado deletado com sucesso!');

                return Redirect::action('Cadastro\CentroResultadoController@index')->with('message', 'success');
            
            }  else {

                 Flash::error('Error ao deletar Centro de Resultado!');

                return Redirect::action('Cadastro\CentroResultadoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\CentroResultadoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
