<?php

namespace App\Http\Controllers\Cadastro;

use App\Model\Favorecido;
use Input;
use Flash;
use Redirect;
use Excel;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\FavorecidoRepository;
use App\Repositories\Cadastro\CondicaoPagamentoRepository;
use App\Repositories\Cadastro\VendedorRepository;
use App\Repositories\Cadastro\TabelaPrecoRepository;
use App\Exceptions\Validation\ValidationException;

class FavorecidoController extends Controller
{
    //
	protected $favorecido;
    protected $condicaoPagamentoRepository;
    protected $vendedorRepository;
    protected $tabelaPrecoRepository;
    protected $per_page;

	public function __construct(FavorecidoRepository $favorecido, CondicaoPagamentoRepository $condicao, VendedorRepository $vendedor, TabelaPrecoRepository $tabela)
    {
        $this->favorecido = $favorecido;
        $this->condicaoPagamentoRepository = $condicao;
        $this->vendedorRepository = $vendedor;
        $this->tabelaPrecoRepository = $tabela;
        // Páginação Padrão
        $this->per_page = config('versus.per_page');

        // verifica login
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'nome_empresarial';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'ASC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;
        $where = null;

        if(isset($filtros['nome_empresarial']) and !empty($filtros['nome_empresarial'])) {
            $nome = $filtros['nome_empresarial'];
            $where['nome_empresarial'] = ['like', '%'.$nome.'%' ];
        }
        if(isset($filtros['nome_fantasia']) and !empty($filtros['nome_fantasia'])) {
            $nome = $filtros['nome_fantasia'];
            $where['nome_fantasia'] = ['like', '%'.$nome.'%' ];
        }
        if(isset($filtros['cnpj']) and !empty($filtros['cnpj'])) {
            $where['cnpj'] = $filtros['cnpj'];
        }

    	
        $per_page = Input::get('per_page');
        if(isset($per_page) && is_numeric($per_page))
            $request->session()->put('per_page', $per_page);
        else
            $per_page = $request->session()->get('per_page');

        if($per_page !== null)
            $favorecidos = $this->favorecido->paginate($page, $per_page, $orderBy, $where);
        else
            $favorecidos = $this->favorecido->paginate($page, $this->per_page, $orderBy, $where);

        return view('cadastros.favorecido.index', [
            'favorecidos' => $favorecidos,
            'filtros' => $filtros,
            'orderBy' => $orderBy,
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
        $condicoes = $this->condicaoPagamentoRepository->lists(false);
        $tabelas = $this->tabelaPrecoRepository->lists();
        $vendedores = $this->vendedorRepository->all();
        $tiposPessoa = getTypePesson();

        return view('cadastros.favorecido.form', compact('tiposPessoa', 'condicoes', 'vendedores', 'tabelas') );
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

            if(env('GALERIA', false))
                $attributes['tipo_galeria'] = implode(",", $attributes['tipo_galeria']);
            
            if($id)
                $this->favorecido->update($id, $attributes);
            else
                $this->favorecido->create($attributes);

            Flash::success('Favorecido salvo com sucesso!');

            return Redirect::action('Cadastro\FavorecidoController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\FavorecidoController@create')->withErrors($e->getErrors())->withInput();
        }

    }


    public function toExcel(Request $request) {

        if (ob_get_level() > 0) { ob_end_clean(); }

        $favorecidos = \App\Model\Cadastro\Favorecido::select(
            'id', 'tipo_pessoa', 'cnpj', 'inscricao_estadual', 'nome_empresarial', 'nome_fantasia'
        )->get();

        // Initialize the array which will be passed into the Excel
        // generator.
        $paymentsArray = []; 

        // Define the Excel spreadsheet headers
        $paymentsArray[] = ['ID', 'Tipo Pessoa','CNPJ/CPF','Inscrição','Razão Social', 'Nome Fantasia'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($favorecidos as $payment) {
            $paymentsArray[] = $payment->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('favorecidos', function($excel) use ($paymentsArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Favorecidos');
            $excel->setCreator('Versus ERP')->setCompany(env('AUTHTO_COMPANY'));
            $excel->setDescription('lista de favorecidos');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($paymentsArray) {
                $sheet->fromArray($paymentsArray, null, 'A1', false, false);
            });

        })->download('xls');

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
        $favorecido = $this->favorecido->find($id);
        $condicoes = $this->condicaoPagamentoRepository->lists(false);
        $vendedores = $this->vendedorRepository->all();
        $tabelas = $this->tabelaPrecoRepository->lists();
        $tiposPessoa = getTypePesson();

        return view('cadastros.favorecido.form', compact('favorecido', 'tiposPessoa', 'condicoes', 'vendedores', 'tabelas') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteobs(Request $request, $id)
    {
        $this->favorecido->deleteObs($id);

        Flash::success('Observação deletada com sucesso!');

        return Redirect::action('Cadastro\FavorecidoController@index')->with('message', 'Success');
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
        try {
            
            $favorecido = $this->favorecido->find($id);

            if($favorecido) {
                if(count($favorecido->agendamentos) == 0) {
                    $this->favorecido->delete($favorecido->id);
                    Flash::success('Favorecido deletado com sucesso!');
                }
                else {
                    Flash::error('Não é possível deletar favorecidos com lançamentos!');
                }

                return Redirect::action('Cadastro\FavorecidoController@index')->with('message', 'Success');

            }  else {
                 Flash::error('Error ao deletar Favorecido!');

                return Redirect::action('Cadastro\FavorecidoController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Cadastro\FavorecidoController@index')->withErrors($e->getErrors())->withInput();
        }
    }

}
