<?php

namespace App\Http\Controllers;

use App\Model\Empresa;
use Input;
use Flash;
use Redirect;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\EmpresaRepository;
use App\Exceptions\Validation\ValidationException;

class EmpresaController extends Controller
{
    //
	protected $empresa;

	public function __construct(EmpresaRepository $empresa)
    {
        $this->empresa = $empresa;

        // verifica login
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$empresa = $this->empresa->first();

        if(!$empresa) $empresa = new Empresa();

        return view('cadastros.config.index', [
            'empresa' => $empresa
        ]);
    }


    public function store(Request $request)
    {
        try {

            $id = $request->get('id');
            if($id)
                $this->empresa->update($id, Input::all());
            else
                $this->empresa->create(Input::all());

            Flash::message('Dados da empresa atualizado com sucesso!');

            return Redirect::action('EmpresaController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('EmpresaController@index')->withErrors($e->getErrors())->withInput();
        }
    }

}
