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

use App\Repositories\Comercial\PedidoAvulsoRepository;
use App\Repositories\Cadastro\FavorecidoRepository;
use App\Repositories\Cadastro\TipoTransacaoRepository;
use App\Repositories\Cadastro\VendedorRepository;
use App\Repositories\Cadastro\ProdutoRepository;

use App\Exceptions\Validation\ValidationException;

class PedidoAvulsoController extends Controller
{
    //
    protected $pedidoRepository;
    protected $favorecidoRepository;
    protected $tipoTransacaoRepository;
    protected $vendedorRepository;
    protected $produtoRepository;
    protected $per_page;

    public function __construct(
    		PedidoAvulsoRepository $pedido,
            FavorecidoRepository $favorecido,
            TipoTransacaoRepository $tipo,
            VendedorRepository $vendedor,
            ProdutoRepository $produto
    ) {
        $this->favorecidoRepository = $favorecido;
        $this->pedidoRepository = $pedido;
        $this->tipoTransacaoRepository = $tipo;
        $this->vendedorRepository = $vendedor;
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
        $favorecidos = $this->favorecidoRepository->lists();
        
        return view('comercial.pedido.index', [
            'pedidos' => $pedidos,
            'filtros' => $filtros,
            'favorecidos' => $favorecidos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $favorecidos = $this->favorecidoRepository->lists();
        $vendedores = $this->vendedorRepository->lists();
        $produtos = $this->produtoRepository->lists();
       	$tiposTransacao = $this->tipoTransacaoRepository->lists();

        return view('comercial.pedido.form', compact(
           'favorecidos', 'vendedores', 'tiposTransacao', 'produtos'
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

            return Redirect::action('Comercial\PedidoAvulsoController@index', array() )->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Comercial\PedidoAvulsoController@create', array() )->withErrors($e->getErrors())->withInput();
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
        
        $favorecidos = $this->favorecidoRepository->lists();
        $vendedores = $this->vendedorRepository->lists();
       	$tiposTransacao = $this->tipoTransacaoRepository->lists();
        $produtos = $this->produtoRepository->lists();

        return view('comercial.pedido.form', compact(
            'pedido', 'favorecidos', 'vendedores', 'tiposTransacao', 'produtos'
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

                return Redirect::action('Comercial\PedidoAvulsoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Orçamento!');

                return Redirect::action('Comercial\PedidoAvulsoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Comercial\PedidoAvulsoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
