<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Model\Role;

use Illuminate\Http\Request;

use App\Http\Requests;

use Flash;
use Redirect;

class UserController extends Controller
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
        
        $users = User::orderBy('name', 'asc')->get();

        return view('cadastros.user.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('display_name', 'id');

        return view('cadastros.user.form', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $email = $request['email'];

        $user = User::where('email', '=', $email)->first();
        if($user) {
            Flash::error('E-mail do usuário já registrado!');
            return Redirect::action('UserController@create')->withInput();
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'remember_token' => $request['_token'],
            'password' => bcrypt($request['password']),
        ]);

        $role = Role::find($request['role_id']);
        // role attach alias
        $user->attachRole($role); // parameter can be an Role object, array, or id

        Flash::success('Usuário criado com sucesso!');

        return Redirect::action('UserController@index')->with('message', 'Success');
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
