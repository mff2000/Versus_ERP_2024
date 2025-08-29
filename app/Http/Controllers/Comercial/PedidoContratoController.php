<?php

namespace App\Http\Controllers\Comercial;

use Auth;
use Input;
use Flash;
use Redirect;
use DateTime;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Comercial\PedidoContratoRepository;
use App\Repositories\Comercial\ContratoFornecimentoRepository;
use App\Repositories\Cadastro\ProdutoRepository;

use App\Exceptions\Validation\ValidationException;

class PedidoContratoController extends Controller
{
    //
    protected $pedidoRepository;
    protected $contratoRepository;
    protected $produtoRepository;
    protected $per_page;

    public function __construct(
    		PedidoContratoRepository $pedido,
            ContratoFornecimentoRepository $contrato,
            ProdutoRepository $produto
    ) {
        $this->contratoRepository = $contrato;
        $this->pedidoRepository = $pedido;
        $this->produtoRepository = $produto;
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
    public function index()
    {

        /* FILTROS
         **************************************************************************
         */
        $where = null;
        $filtros = Input::get('filtros');
        $page = (Input::get('page') != "") ? Input::get('page') : 1;
        
        /*  PÁGINAÇÃO
         ***************************************************************************
         */

        $pedidos = $this->pedidoRepository->paginate($page, $this->per_page, false, $where);
        $contratos = $this->contratoRepository->lists();
        
        return view('comercial.pedido_contrato.index', [
            'pedidos' => $pedidos,
            'filtros' => $filtros,
            'contratos' => $contratos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contratos = $this->contratoRepository->lists();
        $produtos = $this->produtoRepository->lists();

        return view('comercial.pedido_contrato.form', compact(
           'contratos', 'produtos'
        ));
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
            $attributes = Input::all();                       

            if(isset($id) && $id>0)
                $this->pedidoRepository->update($id, $attributes);
            else {
                $this->pedidoRepository->create($attributes);
            }

            Flash::success('Pedido salvo com sucesso!');

            return Redirect::action('Comercial\PedidoContratoController@index', array() )->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Comercial\PedidoContratoController@create', array() )->withErrors($e->getErrors())->withInput();
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
        $pedido = $this->pedidoRepository->find($id);
        
        $contratos = $this->contratoRepository->lists();
        $produtos = $this->produtoRepository->lists();

        return view('comercial.pedido_contrato.form', compact(
            'pedido', 'contratos', 'produtos'
        ));        
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
            
            $pedido = $this->pedidoRepository->find($id);

            if($pedido) {

                $this->pedidoRepository->delete($pedido->id);

                Flash::success('Pedido deletado com sucesso!');

                return Redirect::action('Comercial\PedidoContratoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Orçamento!');

                return Redirect::action('Comercial\PedidoContratoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Comercial\PedidoContratoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
