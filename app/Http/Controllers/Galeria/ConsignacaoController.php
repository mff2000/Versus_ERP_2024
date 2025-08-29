<?php

namespace App\Http\Controllers\Galeria;

use Input;
use Flash;
use Redirect;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Model\Cadastro\Favorecido;
use App\Repositories\Galeria\ConsignacaoRepository;
use App\Repositories\Galeria\ObraRepository;

use App\Model\Galeria\Venda;
use App\Model\Galeria\ItemVenda;

class ConsignacaoController extends Controller
{
    
    protected $obraRepository;
    protected $consignacaoRepository;
    protected $vendaRepository;
    protected $per_page;

    public function __construct(ObraRepository $obra, ConsignacaoRepository $consignacao)
    {
       
        $this->obraRepository = $obra;
        $this->consignacaoRepository = $consignacao;
       
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
            $consignacoes = $this->consignacaoRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $consignacoes = $this->consignacaoRepository->paginate($page, $this->per_page, $orderBy, $where);
        
        $clientes = Favorecido::where('tipo_galeria', 'like', '%C%')->pluck('nome_empresarial', 'id');
        $clientes->prepend('', '');

        return view('galeria.consignacao.index', [
            'consignacoes'  => $consignacoes,
            'clientes'      => $clientes,
            'filtros'       => $filtros,
            'orderBy'       => $orderBy,
            'per_page'      => $per_page
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
        $obras = $this->obraRepository->lists();

        return view('galeria.consignacao.form', compact('clientes', 'obras', 'artistas'));
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
            
            $attributes['data_devolucao'] = convertDateEn($attributes['data_devolucao']);
            $attributes['valor'] = decimalFormat($attributes['valor']);
            $attributes['situacao'] = 'A';
            
            if($id) {
                $consignacao = $this->consignacaoRepository->update($id, $attributes);
            }
            else {
                $consignacao = $this->consignacaoRepository->create($attributes);
            }

            Flash::success('Consignação salva com sucesso!');   

            return Redirect::action('Galeria\ConsignacaoController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Galeria\ConsignacaoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

    public function formRetorno($id) {

        $consignacao = $this->consignacaoRepository->find($id);

        return view('galeria.consignacao.retorno', compact('consignacao'));

    }

    public function saveRetorno(Request $request) {

        $consignacao = $this->consignacaoRepository->find($request->get('id'));
        $houveVenda = false;
        $valor_venda = 0;
        
        if($consignacao) {
            
            if($request->get('retornar_tudo') == 'S') {
                $consignacao->situacao = 'D';
                $consignacao->save();

                foreach ($consignacao->itens as $key => $item) {
                    $item->obra->estoque++;
                    $item->obra->save(); 
                }

            } else {
                $itemsId = $request->get('item_id');
                
                foreach ($consignacao->itens as $key => $item) {
                    
                    $obra = $this->obraRepository->find($item->obra_id);

                    if(in_array($item->id, $itemsId)) {
                        
                        $houveVenda = true;
                        $valor_venda += $item->valor_obra;
                        $obra->data_venda = date('Y-m-d');                    
                        $item->vendido = 1;
                        $item->save();

                    } else {
                        $obra->estoque++;
                    }

                    $obra->save();

                }

                $consignacao->valor = $valor_venda;
                $consignacao->situacao = 'N';
                $consignacao->save();
                
                if($houveVenda)
                    $venda = $this->geraVenda($consignacao);

            }

            Flash::success('Retorno de Consignação realizado com sucesso!'); 

            if($houveVenda)
                return Redirect::action('Galeria\VendaController@edit', array('id' => $venda->id))->with('message', 'Success');
            else
                return Redirect::action('Galeria\ConsignacaoController@index')->with('message', 'Success');

        } else {
            return view('galeria.consignacao.retorno', compact('consignacao'));
        }

    }
    
    private function geraVenda($consignacao) {

        $venda = new Venda();

        $attributes['proposta_id'] = $consignacao->id;
        $attributes['valor'] = $consignacao->valor;
        $attributes['situacao'] = 'A';
        $attributes['data_inclusao'] = date('Y-m-d');
        $attributes['usuario_id'] = $consignacao->usuario_id;
        $attributes['cliente_id'] = $consignacao->cliente_id;
        $venda->fill($attributes)->save();
        
        foreach ($consignacao->itens as $key => $item) {
            if($item->vendido == 1) {
                $newItem = new ItemVenda();
                $newItem->obra_id = $item->obra_id;
                $newItem->venda_id = $venda->id;
                $newItem->valor_obra = $item->valor_obra;
                $newItem->save();
            }
        }

        return $venda;
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
        $consignacao = $this->consignacaoRepository->find($id);
        $clientes = Favorecido::where('tipo_galeria', 'like', '%C%')->pluck('nome_empresarial', 'id');
        $clientes->prepend('', '');
        $artistas = Favorecido::where('tipo_galeria', 'like', '%A%')->pluck('nome_empresarial', 'id');
        $artistas->prepend('', '');
        
        $obras = $this->obraRepository->lists();

        return view('galeria.consignacao.form', compact('consignacao', 'clientes', 'obras', 'artistas'));
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
            
            $consignacao = $this->consignacaoRepository->find($id);

            if($consignacao) {

                if($consignacao->situacao == 'A') {
                    foreach ($consignacao->itens as $key => $item) {
                        $item->obra->estoque++;
                        $item->obra->save();
                        $item->delete();
                    }
                }
                if($this->consignacaoRepository->delete($consignacao->id)) {
                    Flash::success('Consignação deletada com sucesso!');
                }
                return Redirect::action('Galeria\ConsignacaoController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar registro!');

                return Redirect::action('Galeria\ConsignacaoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Galeria\ConsignacaoController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}

