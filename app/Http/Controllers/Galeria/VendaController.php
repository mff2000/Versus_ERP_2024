<?php

namespace App\Http\Controllers\Galeria;

use Input;
use Flash;
use Redirect;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Model\Cadastro\Favorecido;
use App\Model\Galeria\ItemVenda;
use App\Repositories\Galeria\VendaRepository;
use App\Repositories\Galeria\ObraRepository;
use App\Repositories\Cadastro\CondicaoPagamentoRepository;
use App\Repositories\Cadastro\VendedorRepository;

class VendaController extends Controller
{
    
    protected $obraRepository;
    protected $vendaRepository;
    protected $condicaoRepository;    
    protected $per_page;

    public function __construct(ObraRepository $obra, VendaRepository $venda, CondicaoPagamentoRepository $condicao, VendedorRepository $vendedor)
    {
       
        $this->obraRepository = $obra;
        $this->vendaRepository = $venda;
        $this->condicaoRepository = $condicao;
        $this->vendedorRepository = $vendedor;
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

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'id';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'DESC';
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
            $vendas = $this->vendaRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $vendas = $this->vendaRepository->paginate($page, $this->per_page, $orderBy, $where);
        
        $clientes = Favorecido::where('tipo_galeria', 'like', '%C%')->pluck('nome_empresarial', 'id');
        $clientes->prepend('', '');

        return view('galeria.venda.index', [
            'vendas'    => $vendas,
            'clientes'  => $clientes,
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
        
        $clientes = Favorecido::where('tipo_galeria', 'like', '%C%')->pluck('nome_empresarial', 'id');
        $clientes->prepend('', '');
        $artistas = Favorecido::where('tipo_galeria', 'like', '%A%')->pluck('nome_empresarial', 'id');
        $artistas->prepend('', '');
        $condicoes = $this->condicaoRepository->lists();
        $vendedores = $this->vendedorRepository->lists();
        
        return view('galeria.venda.form', compact('clientes', 'condicoes', 'vendedores', 'obras', 'artistas'));
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
            
            $attributes['registro_venda'] = convertDateEn($attributes['registro_venda']);
            $attributes['valor'] = decimalFormat($attributes['valor']);
            $attributes['situacao'] = 'A';

            if(isset($attributes['vendedor_id']) && !is_numeric($attributes['vendedor_id']))
                $attributes['vendedor_id'] = null;
            
            if($id)
                $this->vendaRepository->update($id, $attributes);
            else
                $this->vendaRepository->create($attributes);

            Flash::success('Venda salva com sucesso!');

            return Redirect::action('Galeria\VendaController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Galeria\VendaController@create')->withErrors($e->getErrors())->withInput();
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
        $venda = $this->vendaRepository->find($id);
        $clientes = Favorecido::where('tipo_galeria', 'like', '%C%')->pluck('nome_empresarial', 'id');
        $clientes->prepend('', '');
        $artistas = Favorecido::where('tipo_galeria', 'like', '%A%')->pluck('nome_empresarial', 'id');
        $artistas->prepend('', '');
        $condicoes = $this->condicaoRepository->lists();
        $vendedores = $this->vendedorRepository->lists();
        $obras = $this->obraRepository->lists();

        return view('galeria.venda.form', compact('venda', 'clientes', 'condicoes', 'vendedores', 'obras', 'artistas'));
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

    public function deleteItem($id)
    {
        //
        try {
            
            $item = ItemVenda::find($id);

            if($item) {
                
                $obra = $this->obraRepository->find($item->obra->id);

                if($item->delete()) {

                    $obra->estoque++;
                    $obra->save();
                    
                    return response()->json(['result'=>'success']);
                } else {
                    return Response()->json(['result' => 'error', 'message'=>'Erro ao deletar item']);
                }
                
            }  else {
                return Response()->json(['result' => 'error', 'message'=>'Item não encontrado']);
            } 

        } catch (ValidationException $e) {
            return Response()->json(['result' => 'error', 'message'=> $e.getMessage()]);
        }
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
            
            $venda = $this->vendaRepository->find($id);

            if($venda) {
                
                foreach ($venda->itens as $key => $item) {
                    
                    $obra = $item->obra;
                    $obra->estoque++;
                    $obra->save();

                    $item->delete();
                }

                if($this->vendaRepository->delete($venda->id)) {
                    Flash::success('Venda deletada com sucesso!');
                }
                return Redirect::action('Galeria\VendaController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar registro!');

                return Redirect::action('Galeria\VendaController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Galeria\VendaController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}

