<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\Banco;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\BancoRepository;
use App\Exceptions\Validation\ValidationException;

class BancoController extends Controller
{
    //
    protected $bancoRepository;
    protected $per_page;

    public function __construct(BancoRepository $banco)
    {
        $this->bancoRepository = $banco;

        $this->per_page = config('versus.per_page');

        // verifica login
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->input('session') ) {
            $request->session()->forget('filtros');
            $request->session()->forget('per_page');
        }

        $filtros = Input::get('filtros');
        if(!empty($filtros))
            $request->session()->put('filtros', $filtros);
        else {
            if($request->session()->has('filtros'))
                $filtros = $request->session()->get('filtros');
        }

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'descricao';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'ASC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;
        $where = null;

        if(isset($filtros['descricao']) and !empty($filtros['descricao'])) {
            $nome = $filtros['descricao'];
            $where['descricao'] = ['like', '%'.$nome.'%' ];
        }

        
        $per_page = Input::get('per_page');
        if(isset($per_page) && is_numeric($per_page))
            $request->session()->put('per_page', $per_page);
        else
            $per_page = $request->session()->get('per_page');

        if($per_page !== null)
            $bancos = $this->bancoRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $bancos = $this->bancoRepository->paginate($page, $this->per_page, $orderBy, $where);

        return view('cadastros.banco.index', [
            'bancos' => $bancos,
            'filtros' => $filtros,
            'orderBy' => $orderBy,
            'per_page'=> $per_page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastros.banco.form');
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
                $this->bancoRepository->update($id, Input::all());
            else
                $this->bancoRepository->create(Input::all());

            Flash::success('Banco salvo com sucesso!');

            return Redirect::action('Cadastro\BancoController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\BancoController@create')->withErrors($e->getErrors())->withInput();
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
        $banco = $this->bancoRepository->find($id);

        $endereco = getAddress($banco);

        return view('cadastros.banco.form', ['banco' => $banco, 'endereco' => $endereco]);
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
    public function destroy(Request $request, Banco $banco)
    {
        //
        //
        try {
            
            $banco = $this->bancoRepository->find($banco->id);

            if($banco && count($banco->lancamentos) == 0 ) {
                $this->bancoRepository->delete($banco->id);

                Flash::success('Conta Bancária deletada com sucesso!');

                return Redirect::action('Cadastro\BancoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Conta Bancária!');

                return Redirect::action('Cadastro\BancoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\BancoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
