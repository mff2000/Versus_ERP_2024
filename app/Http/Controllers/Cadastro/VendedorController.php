<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\Vendedor;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\VendedorRepository;
use App\Exceptions\Validation\ValidationException;

class VendedorController extends Controller
{
    //
    protected $vendedorRepository;
    protected $per_page;

    public function __construct(VendedorRepository $vendedor)
    {
        $this->vendedorRepository = $vendedor;

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

        $vendedores = $this->vendedorRepository->paginate($page, $this->per_page, false, $where);

        return view('cadastros.vendedor.index', [
            'vendedores' => $vendedores,
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
        return view('cadastros.vendedor.form');
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
                $this->vendedorRepository->update($id, Input::all());
            else
                $this->vendedorRepository->create(Input::all());

            Flash::success('Vendedor salvo com sucesso!');

            return Redirect::action('Cadastro\VendedorController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\VendedorController@create')->withErrors($e->getErrors())->withInput();
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
        $vendedor = $this->vendedorRepository->find($id);

        return view('cadastros.vendedor.form', ['vendedor' => $vendedor]);
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
    public function destroy(Request $request, Vendedor $vendedor)
    {
        //
        //
        try {
            
            $vendedor = $this->vendedorRepository->find($vendedor->id);

            if($vendedor) {
                
                if (count($vendedor->favorecidos)) {
                    Flash::error('Error ao deletar Vendedor! (Relacionamento com Favorecidos)');
                    return Redirect::action('Cadastro\VendedorController@index')->with('message', 'error');
                }  else {
                    $this->vendedorRepository->delete($vendedor->id);

                    Flash::success('Vendedor deletado com sucesso!');
                    return Redirect::action('Cadastro\VendedorController@index')->with('message', 'Success');
                }
            }  else {
                Flash::error('Error ao deletar Vendedor!');
                return Redirect::action('Cadastro\VendedorController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\VendedorController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
