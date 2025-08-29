<?php

namespace App\Http\Controllers\Galeria;

use Input;
use Flash;
use Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Model\Cadastro\Favorecido;
use App\Repositories\Galeria\TipoObraRepository;
use App\Repositories\Galeria\TecnicaRepository;
use App\Repositories\Galeria\ObraRepository;

use App\Model\Galeria\Obra;

class ObraController extends Controller
{
    
    protected $obraRepository;
    protected $tecnicaRepository;
    protected $tipoObraRepository;
    protected $per_page;

    public function __construct(ObraRepository $obra, TecnicaRepository $tecnica, TipoObraRepository $tipo)
    {
       
        $this->obraRepository = $obra;
        $this->tecnicaRepository = $tecnica;
        $this->tipoObraRepository = $tipo;
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

        $orderBy[0] = (Input::get('orderBy')) ? Input::get('orderBy') : 'titulo';
        $orderBy[1] = Input::get('orderType') ? Input::get('orderType') : 'ASC';
        $page = (Input::get('page') != "") ? Input::get('page') : 1;
        $where = null;

        if(isset($filtros['nome']) and !empty($filtros['nome'])) {
            $nome = $filtros['nome'];
            $where['nome'] = ['like', '%'.$nome.'%' ];
        }

        if(isset($filtros['tipo_obra_id']) and !empty($filtros['tipo_obra_id'])) {
            $tipoObraId = $filtros['tipo_obra_id'];
            $where['tipo_obra_id'] = $tipoObraId;
        }      

        $per_page = Input::get('per_page');
        if(isset($per_page) && is_numeric($per_page))
            $request->session()->put('per_page', $per_page);
        else
            $per_page = $request->session()->get('per_page');

        if($per_page !== null)
            $obras = $this->obraRepository->paginate($page, $per_page, $orderBy, $where);
        else
            $obras = $this->obraRepository->paginate($page, $this->per_page, $orderBy, $where);
        
        $tiposObras = $this->tipoObraRepository->lists();
        $tecnicas = $this->tecnicaRepository->lists();

        return view('galeria.obra.index', [
            'tecnicas' => $tecnicas,
            'tiposObras' => $tiposObras,
            'obras'     => $obras,
            'filtros'   => $filtros,
            'orderBy'   => $orderBy,
            'per_page'  => $per_page
        ]);
    }

    public function getByAjax(Request $request) {

        if($request->has('obra_id')) {
            $products = $this->obraRepository->find($request->input('obra_id'));
        }
        elseif($request->has('artista_id')) {
            $products = Obra::where('estoque', '>', 0);

            if(is_numeric($request->get('artista_id')))
                $products->where("artista_id", "=", $request->input('artista_id'));

            $products = $products->get();
        }
        else {
            $products = DB::table('gal_obras as obras')
                    ->select(DB::raw('obras.id, obras.titulo as text'))
                    ->whereNull('deleted_at')
                    ->where('estoque', '>', 0)
                    ->orderBy('obras.titulo');

            $products = $products->get();
        }
        
        if($products != null) {
            //return Response::json($products->toArray()); 
            //return response()->json($products);
            return response()->json([
                'results' => json_encode($products),
                'pagination' => ['"more"'=>"true"]
            ]);
        }
        else
            return Response::json(['error' => TRUE]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tiposObras = $this->tipoObraRepository->lists();
        $tecnicas = $this->tecnicaRepository->lists();
        $artistas = Favorecido::where('tipo_galeria', 'like', '%A%')->pluck('nome_empresarial', 'id');
        $artistas->prepend('', '');
        $favorecidos = Favorecido::where('tipo_galeria', 'like', '%C%')->pluck('nome_empresarial', 'id');
        $favorecidos->prepend('', '');

        return view('galeria.obra.form', compact('tiposObras', 'tecnicas', 'artistas', 'favorecidos'));
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
            
            $attributes['data_aquisicao'] = convertDateEn($attributes['data_aquisicao']);
            $attributes['valor_custo'] = decimalFormat($attributes['valor_custo']);
            $attributes['valor_venda'] = decimalFormat($attributes['valor_venda']);
            $attributes['valor_minimo_venda'] = decimalFormat($attributes['valor_minimo_venda']);

            if(!empty($attributes['foto'])) {
                $attributes['foto'] = uploadImage($request, 'foto', 'uploads/galeria');
            }

            if(!is_numeric($attributes['proprietario_id']))
                $attributes['proprietario_id'] = null;

            if($id)
                $this->obraRepository->update($id, $attributes);
            else
                $this->obraRepository->create($attributes);

            Flash::success('Obra salva com sucesso!');

            return Redirect::action('Galeria\ObraController@index')->with('message', 'Success');
        } catch (ValidationException $e) {
            return Redirect::action('Galeria\ObraController@create')->withErrors($e->getErrors())->withInput();
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
        $obra = $this->obraRepository->find($id);
        $tiposObras = $this->tipoObraRepository->lists();
        $tecnicas = $this->tecnicaRepository->lists();
        $artistas = Favorecido::where('tipo_galeria', 'like', '%A%')->pluck('nome_empresarial', 'id');
        $artistas->prepend('', '');
        $favorecidos = Favorecido::where('tipo_galeria', 'like', '%C%')->pluck('nome_empresarial', 'id');
        $favorecidos->prepend('', '');

        $endereco = null;
        if($obra->proprietario_id) {
            $endereco = getAddress($obra->proprietario);
        }

        return view('galeria.obra.form', compact('obra', 'tiposObras', 'tecnicas', 'artistas', 'favorecidos', 'endereco'));
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
            
            $obra = $this->obraRepository->find($id);

            if($obra) {
                
                if(is_file($obra->foto))
                    unlink($obra->foto);

                if($this->obraRepository->delete($obra->id)) {
                    Flash::success('Obra deletada com sucesso!');
                }
                return Redirect::action('Galeria\ObraController@index')->with('message', 'Success');
            }  else {
                 Flash::error('Error ao deletar registro!');

                return Redirect::action('Galeria\ObraController@index')->with('message', 'error');
            } 

        } catch (ValidationException $e) {
            return Redirect::action('Galeria\ObraController@create')->withErrors($e->getErrors())->withInput();
        }
    }

}

