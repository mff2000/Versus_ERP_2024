<?php

namespace App\Http\Controllers\Galeria;

use Input;
use Flash;
use Redirect;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Galeria\TipoObraRepository;

class TipoObraController extends Controller
{
    
    protected $tipoRepository;
    protected $per_page;

    public function __construct(TipoObraRepository $tipo)
    {
       
        $this->tipoRepository = $tipo;
        // Páginação Padrão
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

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'nome';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'ASC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;
        $where = null;

        if(isset($filtros['nome']) and !empty($filtros['nome'])) {
            $nome = $filtros['nome'];
            $where['nome'] = ['like', '%'.$nome.'%' ];
        }

        $per_page = Input::get('per_page');
        if(isset($per_page) && is_numeric($per_page))
            $request->session()->put('per_page', $per_page);
        else
            $per_page = $request->session()->get('per_page');

        if($per_page !== null)
            $tiposObra = $this->tipoRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $tiposObra = $this->tipoRepository->paginate($page, $this->per_page, $orderBy, $where);
        

        return view('galeria.tipo_obra.index', [
            'tiposObra' => $tiposObra,
            'filtros'   => $filtros,
            'orderBy'   => $orderBy,
            'per_page'  => $per_page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('galeria.tipo_obra.form', []);
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
                $this->tipoRepository->update($id, Input::all());
            else
                $this->tipoRepository->create(Input::all());

            Flash::success('Tipo de Obra salvo com sucesso!');

            return Redirect::action('Galeria\TipoObraController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Galeria\TipoObraController@create')->withErrors($e->getErrors())->withInput();
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
        $tipoObra = $this->tipoRepository->find($id);

        return view('galeria.tipo_obra.form', ['tipoObra' => $tipoObra]);
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
        //
        try {
            
            $tipo = $this->tipoRepository->find($id);

            if($tipo) {
                
                if($this->tipoRepository->delete($tipo->id)) {
                    Flash::success('Tipo de Obra deletado com sucesso!');
                }
                return Redirect::action('Galeria\TipoObraController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar registro!');

                return Redirect::action('Galeria\TipoObraController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Galeria\TipoObraController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
