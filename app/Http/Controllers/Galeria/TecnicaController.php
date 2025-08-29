<?php

namespace App\Http\Controllers\Galeria;

use Input;
use Flash;
use Redirect;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Model\Galeria\Tecnica;
use App\Repositories\Galeria\TecnicaRepository;

class TecnicaController extends Controller
{
    
    protected $tecnicaRepository;
    protected $per_page;

    public function __construct(TecnicaRepository $tecnica)
    {
       
        $this->tecnicaRepository = $tecnica;
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
            $tecnicas = $this->tecnicaRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $tecnicas = $this->tecnicaRepository->paginate($page, $this->per_page, $orderBy, $where);
    

        return view('galeria.tecnica.index', [
            'tecnicas' => $tecnicas,
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

        return view('galeria.tecnica.form', compact(''));
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
                $this->tecnicaRepository->update($id, Input::all());
            else
                $this->tecnicaRepository->create(Input::all());

            Flash::success('Técnica salva com sucesso!');

            return Redirect::action('Galeria\TecnicaController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Galeria\TecnicaController@create')->withErrors($e->getErrors())->withInput();
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
        $tecnica = $this->tecnicaRepository->find($id);

        return view('galeria.tecnica.form', compact('tecnica'));
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
            
            $tecnica = $this->tecnicaRepository->find($id);

            if($tecnica) {
                
                if($this->tecnicaRepository->delete($tecnica->id)) {
                    Flash::success('Técnica deletada com sucesso!');
                }
                return Redirect::action('Galeria\TecnicaController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar registro!');

                return Redirect::action('Galeria\TecnicaController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Galeria\TecnicaController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}

