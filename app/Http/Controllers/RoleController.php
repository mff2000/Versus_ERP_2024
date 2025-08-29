<?php

namespace App\Http\Controllers;

use App\Model\Role;
use App\Model\Permission;

use Illuminate\Http\Request;

use App\Http\Requests;
use Flash;
use Redirect;

class RoleController extends Controller
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

        $perfis = Role::orderBy('name', 'asc')->get();

        return view('cadastros.perfil.index', [
            'perfis' => $perfis
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('cadastros.perfil.form', compact('permissions'));
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

        $perfil = Role::where('name', '=', $nome)->first();
        if($perfil) {
            Flash::error('Perfil de usuário já cadastrado!');
            return Redirect::action('RoleController@create')->withInput();
        }

        $role = Role::create([
            'name' => str_slug($request['nome']),
            'description' => $request['descricao'],
            'display_name' => $request['nome'],
        ]);

        $role->attachPermission($request['permission']);

        Flash::success('Perfil criado com sucesso!');

        return Redirect::action('RoleController@index')->with('message', 'Success');
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
