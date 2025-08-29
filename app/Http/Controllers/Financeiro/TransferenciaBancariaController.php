<?php

namespace App\Http\Controllers\Financeiro;

use Auth;
use Input;
use Flash;
use Redirect;
use DateTime;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Financeiro\TransferenciaBancariaRepository;
use App\Repositories\Cadastro\BancoRepository;

use App\Exceptions\Validation\ValidationException;

class TransferenciaBancariaController extends Controller
{
    //
    protected $bancoRepository;
    protected $transferenciaBancariaRepository;
    protected $per_page;
    
    public function __construct(            
            BancoRepository $banco,
            TransferenciaBancariaRepository $transferencia
    ) {
        $this->bancoRepository = $banco;
        $this->transferenciaBancariaRepository = $transferencia;
        
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

        if( $request->input('session') ) {
            $request->session()->forget('filtros');
            $request->session()->forget('per_page');
        }

        /* FILTROS
         **************************************************************************
         */
        $where = null;
        
        $filtros = Input::get('filtros');
        if(!empty($filtros))
            $request->session()->put('filtros', $filtros);
        else {
            if($request->session()->has('filtros'))
                $filtros = $request->session()->get('filtros');
        }

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'data_lancamento';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'DESC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;

        if(isset($filtros['data_lancamento']) and !empty($filtros['data_lancamento'])) {
            $datas = explode(" - ", $filtros['data_lancamento']);
            $where['data_lancamento'] = ['whereBetween', array( convertDateEn(trim($datas[0])), convertDateEn(trim($datas[1]))) ];
        }
        if(isset($filtros['banco_origem_id']) && !empty($filtros['banco_origem_id'])) {
            $where['banco_origem_id'] = $filtros['banco_origem_id'];
        }
        if(isset($filtros['banco_destino_id']) && !empty($filtros['banco_destino_id'])) {
            $where['banco_destino_id'] = $filtros['banco_destino_id'];
        }
        if(isset($filtros['historico']) && !empty($filtros['historico'])) {
            $where['historico'] = ['like', '%'.$filtros['historico'].'%'];
        }

        /*  PÁGINAÇÃO
         ***************************************************************************
         */

        $per_page = Input::get('per_page');
        if(isset($per_page) && is_numeric($per_page))
            $request->session()->put('per_page', $per_page);
        else
            $per_page = $request->session()->get('per_page');

        if($per_page !== null)
            $transferencias = $this->transferenciaBancariaRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $transferencias = $this->transferenciaBancariaRepository->paginate($page, $this->per_page, $orderBy, $where);

        
        $bancos = $this->bancoRepository->lists();

        return view('financeiro.transferencia.index', [
            'transferencias' => $transferencias,
            'filtros' => $filtros,
            'bancos' => $bancos,
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
        $bancos = $this->bancoRepository->lists();
      
        return view('financeiro.transferencia.form', compact(
            'bancos'
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

            if($id)
                $this->transferenciaBancariaRepository->update($id, Input::all());
            else {
                $this->transferenciaBancariaRepository->create(Input::all());
            }

            Flash::success('Transferência salva com sucesso!');

            return Redirect::action('Financeiro\TransferenciaBancariaController@create', array() )->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\TransferenciaBancariaController@create', array() )->withErrors($e->getErrors())->withInput();
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
        $transferencia = $this->transferenciaBancariaRepository->find($id);
        $bancos = $this->bancoRepository->lists();
        
        return view('financeiro.transferencia.form', compact(
            'transferencia', 'bancos'
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
            
            $transferencia = $this->transferenciaBancariaRepository->find($id);

            if($transferencia) {
                $this->transferenciaBancariaRepository->delete($transferencia->id);

                Flash::success('Transferência deletada com sucesso!');

                return Redirect::action('Financeiro\TransferenciaBancariaController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar Transferência!');

                return Redirect::action('Financeiro\TransferenciaBancariaController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Financeiro\TransferenciaBancariaController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}
