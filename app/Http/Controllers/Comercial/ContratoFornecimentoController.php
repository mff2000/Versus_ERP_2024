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

use App\Repositories\Comercial\ContratoFornecimentoRepository;
use App\Repositories\Cadastro\ContaContabelRepository;
use App\Repositories\Cadastro\CentroResultadoRepository;
use App\Repositories\Cadastro\ProjetoRepository;
use App\Repositories\Cadastro\FavorecidoRepository;
use App\Repositories\Cadastro\TipoTransacaoRepository;
use App\Repositories\Cadastro\VendedorRepository;
use App\Repositories\Cadastro\ProdutoRepository;
use App\Repositories\Cadastro\CondicaoPagamentoRepository;
use App\Exceptions\Validation\ValidationException;

class ContratoFornecimentoController extends Controller
{
    //
    protected $contratoRepository;
    protected $tipoTransacaoRepository;
    protected $vendedorRepository;
    protected $produtoRepository;
    protected $condicaoRepository;
    protected $per_page;

    public function __construct(
            FavorecidoRepository $favorecido,
            ContratoFornecimentoRepository $contrato,
            TipoTransacaoRepository $tipo,
            VendedorRepository $vendedor,
            ProdutoRepository $produto,
            CondicaoPagamentoRepository $condicao
    ) {
        $this->favorecidoRepository = $favorecido;
        $this->contratoRepository = $contrato;
        $this->tipoTransacaoRepository = $tipo;
        $this->vendedorRepository = $vendedor;
        $this->produtoRepository = $produto;
        $this->condicaoRepository = $condicao;
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

        $contratos = $this->contratoRepository->paginate($page, $this->per_page, false, $where);
        $favorecidos = $this->favorecidoRepository->lists();
        return view('comercial.contrato_fornecimento.index', [
            'contratos' => $contratos,
            'favorecidos' => $favorecidos, 
            'filtros' => $filtros,
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
        $tiposTransacao = $this->tipoTransacaoRepository->lists();
        $vendedores = $this->vendedorRepository->lists();
        $produtos = $this->produtoRepository->lists();
        $condicoes = $this->condicaoRepository->lists();

        return view('comercial.contrato_fornecimento.form', compact(
           'favorecidos', 'tiposTransacao', 'vendedores', 'produtos', 'condicoes'
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
                $this->contratoRepository->update($id, $attributes);
            else {
                $this->contratoRepository->create($attributes);
            }

            Flash::success('Contrato salvo com sucesso!');

            return Redirect::action('Comercial\ContratoFornecimentoController@index', array() )->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Comercial\ContratoFornecimentoController@create', array() )->withErrors($e->getErrors())->withInput();
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
        $contrato = $this->contratoRepository->find($id);
        
        $favorecidos = $this->favorecidoRepository->lists();
        $tiposTransacao = $this->tipoTransacaoRepository->lists();
        $vendedores = $this->vendedorRepository->lists();
        $produtos = $this->produtoRepository->lists();
        $condicoes = $this->condicaoRepository->lists();

        return view('comercial.contrato_fornecimento.form', compact(
            'contrato', 'tiposTransacao', 'favorecidos', 'vendedores', 'produtos', 'condicoes'
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
            
            $contrato = $this->contratoRepository->find($id);

            if($contrato) {

                foreach ($contrato->itens as $key => $item) {
                    $item->delete();
                }
                
                $this->contratoRepository->delete($contrato->id);

                Flash::success('Contrato deletado com sucesso!');

                return Redirect::action('Comercial\ContratoFornecimentoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Contrato!');

                return Redirect::action('Comercial\ContratoFornecimentoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Comercial\ContratoFornecimentoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
