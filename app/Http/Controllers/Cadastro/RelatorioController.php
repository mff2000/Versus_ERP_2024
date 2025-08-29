<?php

namespace App\Http\Controllers\Cadastro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\Banco;
use App\Http\Controllers\Controller;
use PDF;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Cadastro\FavorecidoRepository;
use App\Repositories\Cadastro\ContaContabelRepository;
use App\Repositories\Cadastro\CentroResultadoRepository;
use App\Repositories\Cadastro\ProjetoRepository;
use App\Exceptions\Validation\ValidationException;

class RelatorioController extends Controller
{
    //
    protected $favorecidoReport = 'report/favorecidos.pdf';
    protected $planoContaReport = 'report/planos_de_contas.pdf';
    protected $centroResultadoReport = 'report/centros_de_resultados.pdf';
    protected $projetoReport = 'report/projetos.pdf';

    public function __construct(FavorecidoRepository $favorecidos, ContaContabelRepository $planos, CentroResultadoRepository $centros, ProjetoRepository $projetos)
    {
        $this->favorecidoRepository = $favorecidos;
        $this->planoContaRepository = $planos;
        $this->centroResultadoRepository = $centros;
        $this->projetoRepository = $projetos;

        // verifica login
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($relatorio)
    {
        
        switch ($relatorio) {
            case 'favorecidos': 
                $colunas = array('nome_empresarial', 'nome_fantasia', 'cnpj', 'tel_fixo1', 'tel_movel1', 'email_geral', 'limite_credito', 'risco_credito', 'created_at');
                break;
            case 'planos_contas': 
                $colunas = array('codigo', 'descricao', 'classe', 'natureza');
                break;
            case 'centros_resultados': 
                $colunas = array('codigo', 'descricao', 'classe');
                break;
            case 'projetos': 
                $colunas = array('codigo', 'descricao', 'classe', 'natureza');
                break;
            default:
                $tabela = null;
                $colunas = null;
                break;
        }

       return view('cadastros.relatorios.'.$relatorio.'.index', ['colunas'=>$colunas, 'tabela'=>$relatorio]);
        
    }

    public function favorecidos(Request $request) {

        $where = null;
        $orderBy = null;
        $orderByColumn = Input::get('orderByColumn');

        $filtros = Input::get('filtros');

        if(isset($filtros['created_at_ini']) && isset($filtros['created_at_end']) && !empty($filtros['created_at_ini']) && !empty($filtros['created_at_end'])) {
            $data_ini = $filtros['created_at_ini'];
            $data_end = $filtros['created_at_end'];
            $where['created_at'] = ['whereBetween', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];
        }
        if(isset($filtros['nome_empresarial']) && !empty($filtros['nome_empresarial'])) {
            $nome_empresarial = $filtros['nome_empresarial'];
            $where['nome_empresarial'] = ['like', '%'.$nome_empresarial.'%'];
        }
        if(isset($filtros['nome_fantasia']) && !empty($filtros['nome_fantasia'])) {
            $nome_fantasia = $filtros['nome_fantasia'];
            $where['nome_fantasia'] = ['like', '%'.$nome_fantasia.'%'];
        }
        
        if(isset($orderByColumn) && !empty($orderByColumn)) {
            $orderBy[0] = Input::get('orderByColumn');
            $orderBy[1] = Input::get('orderByDirecion');
        }

        $data['favorecidos'] = $this->favorecidoRepository->all($where, $orderBy);
        $data['colunas'] = Input::get('colunas');
        $pdf = PDF::loadView('cadastros.relatorios.favorecidos.report', $data);
        
        $orientacao = Input::get('orientacao');
        if($orientacao == 'H') {
            $pdf->setPaper('a4', 'landscape');
        }

        $pdf->stream();
        $pdf->save($this->favorecidoReport);

        /*return view('cadastros.relatorios.favorecidos.report', [
            'favorecidos'=>$data['favorecidos'], 'colunas'=>$data['colunas'] 
        ]);*/

        return Redirect::to($this->favorecidoReport);
    }

    public function planos_contas(Request $request) {

        set_time_limit(900);

        $where = null;
        $orderBy = null;
        $orderByColumn = Input::get('orderByColumn');
        
        if(isset($orderByColumn) && !empty($orderByColumn)) {
            $orderBy[0] = Input::get('orderByColumn');
            $orderBy[1] = Input::get('orderByDirecion');
        }

        $data['planos_contas'] = $this->planoContaRepository->all($where, $orderBy);
        $data['colunas'] = Input::get('colunas');
        $pdf = PDF::loadView('cadastros.relatorios.planos_contas.report', $data);
        
        $orientacao = Input::get('orientacao');
        if($orientacao == 'H') {
            $pdf->setPaper('a4', 'landscape');
        }

        $pdf->stream();
        $pdf->save($this->planoContaReport);

        /*return view('cadastros.relatorios.favorecidos.report', [
            'favorecidos'=>$data['favorecidos'], 'colunas'=>$data['colunas'] 
        ]);*/

        return Redirect::to($this->planoContaReport);
    }

    public function centros_resultados(Request $request) {

        set_time_limit(900);

        $where = null;
        $orderBy = null;
        $orderByColumn = Input::get('orderByColumn');
        
        if(isset($orderByColumn) && !empty($orderByColumn)) {
            $orderBy[0] = Input::get('orderByColumn');
            $orderBy[1] = Input::get('orderByDirecion');
        }

        $data['centros_resultados'] = $this->centroResultadoRepository->all($where, $orderBy);
        $data['colunas'] = Input::get('colunas');
        $pdf = PDF::loadView('cadastros.relatorios.centros_resultados.report', $data);
        
        $orientacao = Input::get('orientacao');
        if($orientacao == 'H') {
            $pdf->setPaper('a4', 'landscape');
        }

        $pdf->stream();
        $pdf->save($this->centroResultadoReport);

        /*return view('cadastros.relatorios.favorecidos.report', [
            'favorecidos'=>$data['favorecidos'], 'colunas'=>$data['colunas'] 
        ]);*/

        return Redirect::to($this->centroResultadoReport);
    }

    public function projetos(Request $request) {

        set_time_limit(900);

        $where = null;
        $orderBy = null;
        $orderByColumn = Input::get('orderByColumn');
        
        if(isset($orderByColumn) && !empty($orderByColumn)) {
            $orderBy[0] = Input::get('orderByColumn');
            $orderBy[1] = Input::get('orderByDirecion');
        }

        $data['projetos'] = $this->projetoRepository->all($where, $orderBy);
        $data['colunas'] = Input::get('colunas');
        $pdf = PDF::loadView('cadastros.relatorios.projetos.report', $data);
        
        $orientacao = Input::get('orientacao');
        if($orientacao == 'H') {
            $pdf->setPaper('a4', 'landscape');
        }

        $pdf->stream();
        $pdf->save($this->centroResultadoReport);

        /*return view('cadastros.relatorios.favorecidos.report', [
            'favorecidos'=>$data['favorecidos'], 'colunas'=>$data['colunas'] 
        ]);*/

        return Redirect::to($this->centroResultadoReport);
    }

}
