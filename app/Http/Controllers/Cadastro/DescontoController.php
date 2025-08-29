<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\Desconto;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\DescontoRepository;
use App\Repositories\Cadastro\ContaContabelRepository;
use App\Exceptions\Validation\ValidationException;

class DescontoController extends Controller
{
    //
    protected $descontoRepository;
    protected $planoContaRepository;
    protected $per_page;

    public function __construct(DescontoRepository $desconto, ContaContabelRepository $plano)
    {
        $this->descontoRepository = $desconto;
        $this->planoContaRepository = $plano;

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
            $descontos = $this->descontoRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $descontos = $this->descontoRepository->paginate($page, $this->per_page, $orderBy, $where);
        

        return view('cadastros.desconto.index', [
            'descontos' => $descontos,
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
        $planos = $this->planoContaRepository->all();

        return view('cadastros.desconto.form', ['planosContas'=>$planos]);
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
                $this->descontoRepository->update($id, Input::all());
            else
                $this->descontoRepository->create(Input::all());

            Flash::success('Desconto salvo com sucesso!');

            return Redirect::action('Cadastro\DescontoController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\DescontoController@create')->withErrors($e->getErrors())->withInput();
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
        $desconto = $this->descontoRepository->find($id);
        $planos = $this->planoContaRepository->all();

        return view('cadastros.desconto.form', ['desconto' => $desconto, 'planosContas'=>$planos]);
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
            
            $desconto = $this->descontoRepository->find($id);

            if($desconto) {
                
                if($this->descontoRepository->delete($desconto->id)) {
                    Flash::success('Desconto deletado com sucesso!');
                }
                return Redirect::action('Cadastro\DescontoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Desconto!');

                return Redirect::action('Cadastro\DescontoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\DescontoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
