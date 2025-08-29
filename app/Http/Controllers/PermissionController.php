<?php

namespace App\Http\Controllers;

use App\Model\Permission;

use Illuminate\Http\Request;

use App\Http\Requests;
use Flash;
use Redirect;

class PermissionController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $permissoes = Permission::orderBy('name', 'asc')->get();

        return view('cadastros.permissao.index', [
            'permissoes' => $permissoes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastros.permissao.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $nome = $request['nome'];

        $perfil = Permission::where('name', '=', $nome)->first();
        if($perfil) {
            Flash::error('Regra de Permissão já cadastrada!');
            return Redirect::action('PermissionController@create')->withInput();
        }

        $user = Permission::create([
            'name' => str_slug($request['name']),
            'description' => $request['descricao'],
            'display_name' => $request['display_name'],
        ]);

        Flash::success('Permissão criada com sucesso!');

        return Redirect::action('PermissionController@index')->with('message', 'Success');
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
    public function destroy($id)
    {
        //
    }
}
