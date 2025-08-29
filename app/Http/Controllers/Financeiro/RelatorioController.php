<?php

namespace App\Http\Controllers\Financeiro;

use Input;
use Flash;
use Redirect;
use App\Model\Cadastro\Banco;
use App\Http\Controllers\Controller;
use PDF;
use DateTime;
use Carbon\Carbon;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Financeiro\AgendamentoRepository;
use App\Repositories\Financeiro\LancamentoBancarioRepository;
use App\Repositories\Financeiro\TransferenciaBancariaRepository;
use App\Repositories\Cadastro\FavorecidoRepository;
use App\Repositories\Cadastro\ContaContabelRepository;
use App\Repositories\Cadastro\CentroResultadoRepository;
use App\Repositories\Cadastro\ProjetoRepository;
use App\Repositories\Cadastro\BancoRepository;

use App\Exceptions\Validation\ValidationException;

class RelatorioController extends Controller
{
    //
    protected $agendamentoReport = 'report/agendamentos.pdf';
    protected $extratoBancarioReport = 'report/extratos_bancarios.pdf';
    protected $dreReport = 'report/dre.pdf';
    protected $dreDetailsReport = 'report/dre_details.pdf';
    protected $fluxoCaixaReport = 'report/fluxo_caixa.pdf';

    public function __construct(AgendamentoRepository $agendamentos, FavorecidoRepository $favorecidos, ContaContabelRepository $contas, CentroResultadoRepository $centros, ProjetoRepository $projetos, BancoRepository $bancos, LancamentoBancarioRepository $lancamentos, TransferenciaBancariaRepository $transferencias)
    {
        $this->agendamentoRepository = $agendamentos;
        $this->favorecidoRepository = $favorecidos;
        $this->planoContaRepository = $contas;
        $this->centroResultadoRepository = $centros;
        $this->projetoRepository = $projetos;
        $this->bancoRepository = $bancos;
        $this->lancamentoBancarioRepository = $lancamentos;
        $this->transferenciaBancariaRepository = $transferencias;

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
            case 'agendamentos': 
                $colunas = array('numero_titulo', 'numero_parcela', 'historico', 'favorecido_id', 'valor_titulo', 'valor_saldo', 'data_competencia', 'data_vencimento');
                return view('financeiro.relatorios.'.$relatorio.'.index', [
                    'colunas'=>$colunas, 'tabela'=>'agendamentos',
                    'favorecidos' => $this->favorecidoRepository->lists(false),
                    'planosContas' => $this->planoContaRepository->all(),
                    'centrosResultados' => $this->centroResultadoRepository->all(),
                    'projetos' => $this->projetoRepository->all()  
                ]);
                break;
            case 'extratos_bancarios':
                $colunas = array('numero_titulo', 'numero_parcela', 'historico', 'valor_lancamento', 'data_liquidacao');
                return view('financeiro.relatorios.'.$relatorio.'.index', [
                    'colunas'=>$colunas, 'tabela'=>'lancamentos_bancarios',
                    'bancos' => $this->bancoRepository->lists(false)
                ]);
                break;
            case 'demonstrativos_resultados':
                $colunas = array('codigo', 'descricao');
                return view('financeiro.relatorios.'.$relatorio.'.index', [
                    'colunas'=>$colunas, 'tabela'=>'planos_contas',
                    'planosContas' => $this->planoContaRepository->all(),
                    'centrosResultados' => $this->centroResultadoRepository->all(),
                    'projetos' => $this->projetoRepository->all()  
                ]);
                break;
            case 'fluxo_caixa':
                $colunas = array('codigo', 'descricao');
                return view('financeiro.relatorios.'.$relatorio.'.index', [
                    'colunas'=>$colunas, 'tabela'=>'lancamentos_bancarios',
                    'bancos' => $this->bancoRepository->lists(false) 
                ]);
                break;
            case 'razao':
                $colunas = array('id', 'historico', 'favorecido_id', 'tipo_movimento');
                return view('financeiro.relatorios.'.$relatorio.'.index', [
                    'colunas'=>$colunas, 'tabela'=>'agendamentos',
                    'bancos' => $this->bancoRepository->lists(false) 
                ]);
                break;
            default:
                $tabela = null;
                $colunas = null;
                break;
        }

       
        
    }

    public function agendamentos(Request $request) {

        set_time_limit(900);
        
        $where = null;
        $orderBy = null;
        $orderByColumn = Input::get('orderByColumn');

        $filtros = Input::get('filtros');
        $filtrosDesc = '<ol class="breadcrumb">';

        if(isset($filtros['data_competencia_ini']) && isset($filtros['data_competencia_end']) && !empty($filtros['data_competencia_ini']) && !empty($filtros['data_competencia_end'])) {
            $data_ini = $filtros['data_competencia_ini'];
            $data_end = $filtros['data_competencia_end'];
            $where['data_competencia'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];

            $filtrosDesc .= "<li><b>Data Competência:</b> ".$data_ini." à ".$data_end."</li>";
        }
        if(isset($filtros['data_vencimento_ini']) && isset($filtros['data_vencimento_end']) && !empty($filtros['data_vencimento_ini']) && !empty($filtros['data_vencimento_end'])) {
            $data_ini = $filtros['data_vencimento_ini'];
            $data_end = $filtros['data_vencimento_end'];
            $where['data_vencimento'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];

            $filtrosDesc .= "<li><b>Data Vencimento:</b> ".$data_ini." à ".$data_end."</li>";
        }
        if(isset($filtros['data_baixa_ini']) && isset($filtros['data_baixa_end']) && !empty($filtros['data_baixa_ini']) && !empty($filtros['data_baixa_end'])) {
            $data_ini = $filtros['data_baixa_ini'];
            $data_end = $filtros['data_baixa_end'];
            $where['data_baixa'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];

            $filtrosDesc .= "<li><b>Data de Baixa:</b> ".$data_ini." à ".$data_end."</li>";
        }
        if(isset($filtros['favorecido_id']) && !empty($filtros['favorecido_id'])) {
            $favorecido_id = $filtros['favorecido_id'];
            $where['favorecido_id'] = ['whereIn', $favorecido_id];

            $filtrosDesc .= "<li><b>Favorecidos:</b> ";
            foreach ($favorecido_id as $key => $value) {
                if(count($favorecido_id) == ($key+1))
                    $filtrosDesc .= $this->favorecidoRepository->find($value)->nome_empresarial;
                else
                    $filtrosDesc .= $this->favorecidoRepository->find($value)->nome_empresarial.", ";
            }
            $filtrosDesc .= "</li>";
            
        }
        if(isset($filtros['plano_conta_id']) && !empty($filtros['plano_conta_id'])) {
            $planos_conta_id = $filtros['plano_conta_id'];
            $where['agendamento_rateio.plano_conta_id'] = ['whereIn', array_keys($planos_conta_id)];

            $filtrosDesc .= "<li><b>Plano(s) de Conta: </b>";
            foreach (array_keys($planos_conta_id) as $key => $value) {
                $plano = $this->planoContaRepository->find($value);
                $filtrosDesc .= $plano->descricao.",";
            }
            $filtrosDesc .= '</li>';
        }
        if(isset($filtros['centro_resultado_id']) && !empty($filtros['centro_resultado_id'])) {
            $centros_resultado_id = $filtros['centro_resultado_id'];
            $where['agendamento_rateio.centro_resultado_id'] = ['whereIn', array_keys($centros_resultado_id)];
        }
        if(isset($filtros['projeto_id']) && !empty($filtros['projeto_id'])) {
            $projetos_id = $filtros['projeto_id'];
            $where['agendamento_rateio.projeto_id'] = ['whereIn', array_keys($projetos_id)];
        }
        if(isset($filtros['agendamento_aberto']) && isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_liquidados'])) {
            $where['valor_saldo'] = ['>', 0];
            $filtrosDesc .= "<li><b>Status :</b> Abertos/Parcial</li>";
        }
        else if(isset($filtros['agendamento_liquidados']) && isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_aberto'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo != valor_titulo'];
            $filtrosDesc .= "<li><b>Status :</b> Parcial/Liquidados</li>";
        }
        else if(isset($filtros['agendamento_liquidados']) && isset($filtros['agendamento_aberto']) && !isset($filtros['agendamento_parcial'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo = valor_titulo and valor_saldo = 0'];
            $filtrosDesc .= "<li><b>Status :</b> Abertos/Liquidados</li>";
        }
        else if(isset($filtros['agendamento_aberto']) && !isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_liquidados'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo = valor_titulo'];
            $filtrosDesc .= "<li><b>Status :</b> Abertos</li>";
        }
        else if(isset($filtros['agendamento_liquidados']) && !isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_aberto'])) {
            $where['valor_saldo'] = 0;
            $filtrosDesc .= "<li><b>Status :</b> Liquidados</li>";
        }
        else if(isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_liquidados']) && !isset($filtros['agendamento_aberto'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo < valor_titulo and valor_saldo > 0'];
            $filtrosDesc .= "<li><b>Status :</b> Parcial</li>";
        } 
        else {
            $filtrosDesc .= "<li><b>Status :</b> Liquidados/Parcial/Abertos</li>";
        }
        if(isset($filtros['agendamento_bordero']) && !empty($filtros['agendamento_bordero'])) {
            $where['bordero_id'] = ['!=', NULL];
        }

        if(isset($filtros['tipo_pagamento']) && !isset($filtros['tipo_recebimento'])) {
            $where['tipo_movimento'] = 'PGT';
            $filtrosDesc .= "<li><b>Tipo:</b> Pagamentos</li>";
        } else if(isset($filtros['tipo_recebimento']) && !isset($filtros['tipo_pagamento'])) {
            $where['tipo_movimento'] = 'RCT';
            $filtrosDesc .= "<li><b>Tipo:</b> Recebimentos</li>";
        } else {
            $filtrosDesc .= "<li><b>Tipo:</b> Pagamentos/Recebimentos</li>";
        }
        
        $filtrosDesc .="</ol>";

        if(isset($orderByColumn) && !empty($orderByColumn)) {
            $orderBy[0] = Input::get('orderByColumn');
            $orderBy[1] = Input::get('orderByDirecion');
        }

        $data['agendamentos'] = $this->agendamentoRepository->all($where, $orderBy);
        $data['colunas'] = Input::get('colunas');

        if(Input::get('printIn') == 'PDF') {
            $pdf = PDF::loadView('financeiro.relatorios.agendamentos.report', $data);
        
            $orientacao = Input::get('orientacao');
            if($orientacao == 'H') {
                $pdf->setPaper('a4', 'landscape');
            }

            $pdf->stream();
            $pdf->save($this->agendamentoReport);
            return Redirect::to($this->agendamentoReport);
        } else {

            $title = 'Relatório de Agendamentos';

            return view('financeiro.relatorios.agendamentos.report', [
                'agendamentos'=>$data['agendamentos'], 'colunas'=>$data['colunas'], 'title' => $title, 'filtrosDesc' => $filtrosDesc
            ]);
        }

        
    }

    public function extratos_bancarios(Request $request) {

        set_time_limit(900);

        $where = null;
        $orderBy = null;
        $orderByColumn = Input::get('orderByColumn');

        $filtros = Input::get('filtros');

        // apenas liquidados
        $where['data_liquidacao'] = ['!=', NULL];

        if(isset($filtros['data_lancamento_ini']) && isset($filtros['data_lancamento_end']) && !empty($filtros['data_lancamento_ini']) && !empty($filtros['data_lancamento_end'])) {
            $data_ini = $filtros['data_lancamento_ini'];
            $data_end = $filtros['data_lancamento_end'];
            $where['data_liquidacao'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end)) ) ];
        }
        
        if(isset($filtros['banco_id']) && !empty($filtros['banco_id'])) {
            $where['banco_id'] = $filtros['banco_id'];
        } else {
            Flash::error('Por favor, selecione qual Banco!');
            return Redirect::action('Financeiro\RelatorioController@extrato_bancario')->withErrors($e->getErrors())->withInput();
        }
                
        if(isset($orderByColumn) && !empty($orderByColumn)) {
            $orderBy[0] = Input::get('orderByColumn');
            $orderBy[1] = Input::get('orderByDirecion');
        }

        $data['lancamentos'] = $this->bancoRepository->getLancamentosExtrato($where, $orderBy);
        $data['banco'] = $this->bancoRepository->find($filtros['banco_id']);
        $data['saldoAnterior'] = $this->bancoRepository->calcSaldoAt(convertDateEn($filtros['data_lancamento_ini']), $filtros['banco_id']);
        $data['colunas'] = Input::get('colunas');
        $data['dataIni'] = $filtros['data_lancamento_ini'];
        $data['dataEnd'] = $filtros['data_lancamento_end'];

        if(Input::get('printIn') == 'PDF') {
            $pdf = PDF::loadView('financeiro.relatorios.extratos_bancarios.report', $data);
        
            $orientacao = Input::get('orientacao');
            if($orientacao == 'H') {
                $pdf->setPaper('a4', 'landscape');
            }

            $pdf->stream();
            $pdf->save($this->extratoBancarioReport);
            return Redirect::to($this->extratoBancarioReport);
        } else {

            $title = 'Extrato Bancário - Período '.(trim($data_ini)).' à '.(trim($data_end));

            return view('financeiro.relatorios.extratos_bancarios.report', [
                'lancamentos'=>$data['lancamentos'], 
                'colunas'=>$data['colunas'],
                'banco' => $data['banco'],
                'dataIni' => $data['dataIni'],
                'dataEnd' => $data['dataEnd'],
                'saldoAnterior' => $data['saldoAnterior'],
                'title' => $title
            ]);
        }

        
    }


    public function fluxo_caixa(Request $request) {

        set_time_limit(900);

        $where = null;
        $orderBy = null;
        $orderByColumn = Input::get('orderByColumn');

        $filtros = Input::get('filtros');
        $data['periodo'] = Input::get('periodo');

        if(isset($filtros['banco_id']) && !empty($filtros['banco_id'])) {
            $where['banco_id'] = $filtros['banco_id'];
        } 
                
        if(isset($orderByColumn) && !empty($orderByColumn)) {
            $orderBy[0] = Input::get('orderByColumn');
            $orderBy[1] = Input::get('orderByDirecion');
        }

        $data['datas'] = getDatePeriodo($data['periodo']);

        //$data['lancamentosApagar'] = $this->agendamentoRepository->getToFluxoCaixa($data['datas'], 'PGT');

        //$data['lancamentosAreceber'] = $this->agendamentoRepository->getToFluxoCaixa($data['datas'], 'RCT');
        
        $data['saldoAtrasado'] = $this->agendamentoRepository->getAtrasadoFluxoCaixa($data['datas'][0][1]->toDateString());

        $data['totalApagar'] = $this->agendamentoRepository->getTotalFluxoCaixa($data['datas'], 'PGT');
        $data['totalAreceber'] = $this->agendamentoRepository->getTotalFluxoCaixa($data['datas'], 'RCT');

        $data['saldoAtual'] = $this->bancoRepository->calcSaldoAt($data['datas'][0][1]->toDateString());
        //$data['saldoAnterior'] = $this->bancoRepository->calcSaldoAt($data['datas'][1][1]->toDateString());
        
        $data['limite'] = $this->bancoRepository->getLimiteTotalAtual();

        if(Input::get('printIn') == 'PDF') {
            $pdf = PDF::loadView('financeiro.relatorios.fluxo_caixa.report', $data['datas'][$i]);
        
            $orientacao = Input::get('orientacao');
            if($orientacao == 'H') {
                $pdf->setPaper('a4', 'landscape');
            }

            $pdf->stream();
            $pdf->save($this->fluxoCaixaReport);
            return Redirect::to($this->fluxoCaixaReport);
        } else {

            $title = 'Fluxo de Caixa - Período ';

            return view('financeiro.relatorios.fluxo_caixa.report', [
                //'lancamentosApagar'=>$data['lancamentosApagar'], 
                //'lancamentosAreceber'=>$data['lancamentosAreceber'],
                'totalApagar' => $data['totalApagar'],
                'totalAreceber' => $data['totalAreceber'],
                'saldoAtual' => $data['saldoAtual'],
                //'saldoAnterior' => $data['saldoAtual'],
                'saldoAtrasado' => $data['saldoAtrasado'],
                'limite'    => $data['limite'],
                'periodo' => $data['periodo'],
                'datas' => $data['datas'],
                'title' => $title
            ]);
        }

        
    }

    public function demonstrativos_resultados(Request $request) {

        set_time_limit(900);

        $where = null;
        $orderBy = null;
        $orderByColumn = Input::get('orderByColumn');

        $filtros = Input::get('filtros');
        $filtrosDesc = '<ol class="breadcrumb">';

        if(
            (empty($filtros['data_competencia_ini']) && empty($filtros['data_competencia_end'])) &&
            (empty($filtros['data_vencimento_ini']) && empty($filtros['data_vencimento_end'])) &&
            (empty($filtros['data_baixa_ini']) && empty($filtros['data_baixa_end']))
        ) {

            Flash::error('Necessário preencher pelo menos 1 intervalo de data!');
            return Redirect::action('Financeiro\RelatorioController@index', array('relatorio' => 'demonstrativos_resultados'));
        }
        

        if(isset($filtros['data_competencia_ini']) && isset($filtros['data_competencia_end']) && !empty($filtros['data_competencia_ini']) && !empty($filtros['data_competencia_end'])) {
            $data_ini = $filtros['data_competencia_ini'];
            $data_end = $filtros['data_competencia_end'];
            $where['data_competencia'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];

            $filtrosDesc .= "<li><b>Data Competência:</b> ".$data_ini." à ".$data_end."</li>";
        }
        if(isset($filtros['data_vencimento_ini']) && isset($filtros['data_vencimento_end']) && !empty($filtros['data_vencimento_ini']) && !empty($filtros['data_vencimento_end'])) {
            $data_ini = $filtros['data_vencimento_ini'];
            $data_end = $filtros['data_vencimento_end'];
            $where['data_vencimento'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];

            $filtrosDesc .= "<li><b>Data Vencimento:</b> ".$data_ini." à ".$data_end."</li>";
        }
        if(isset($filtros['data_baixa_ini']) && isset($filtros['data_baixa_end']) && !empty($filtros['data_baixa_ini']) && !empty($filtros['data_baixa_end'])) {
            $data_ini = $filtros['data_baixa_ini'];
            $data_end = $filtros['data_baixa_end'];
            $where['data_lancamento'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];

            $filtrosDesc .= "<li><b>Data Baixa:</b> ".$data_ini." à ".$data_end."</li>";
        }
        
        if(isset($filtros['plano_conta_id']) && !empty($filtros['plano_conta_id'])) {
            $filtrosDesc .= "<li><b>Plano(s) de Conta: </b>";
            $planos_conta_id = $filtros['plano_conta_id'];
            $where['agendamento_rateio.plano_conta_id'] = ['whereIn', array_keys($planos_conta_id)];

            foreach (array_keys($planos_conta_id) as $key => $value) {
                $plano = $this->planoContaRepository->find($value);
                $filtrosDesc .= $plano->descricao.",";
            }
            $filtrosDesc .= '</li>';
        }
        if(isset($filtros['centro_resultado_id']) && !empty($filtros['centro_resultado_id'])) {
            $centros_resultado_id = $filtros['centro_resultado_id'];
            $where['agendamento_rateio.centro_resultado_id'] = ['whereIn', array_keys($centros_resultado_id)];
        }
        if(isset($filtros['projeto_id']) && !empty($filtros['projeto_id'])) {
            $projetos_id = $filtros['projeto_id'];
            $where['agendamento_rateio.projeto_id'] = ['whereIn', array_keys($projetos_id)];
        }
        if(isset($filtros['agendamento_aberto']) && isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_liquidados'])) {
            $where['valor_saldo'] = ['>', 0];
            $filtrosDesc .= "<li><b>Status :</b> Abertos/Parcial</li>";
        }
        else if(isset($filtros['agendamento_liquidados']) && isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_aberto'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo != valor_titulo'];
            $filtrosDesc .= "<li><b>Status :</b> Parcial/Liquidados</li>";
        }
        else if(isset($filtros['agendamento_liquidados']) && isset($filtros['agendamento_aberto']) && !isset($filtros['agendamento_parcial'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo = valor_titulo and valor_saldo = 0'];
            $filtrosDesc .= "<li><b>Status :</b> Abertos/Liquidados</li>";
        }
        else if(isset($filtros['agendamento_aberto']) && !isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_liquidados'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo = valor_titulo'];
            $filtrosDesc .= "<li><b>Status :</b> Abertos</li>";
        }
        else if(isset($filtros['agendamento_liquidados']) && !isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_aberto'])) {
            $where['valor_saldo'] = 0;
            $filtrosDesc .= "<li><b>Status :</b> Liquidados</li>";
        }
        else if(isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_liquidados']) && !isset($filtros['agendamento_aberto'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo < valor_titulo and valor_saldo > 0'];;
            $filtrosDesc .= "<li><b>Status :</b> Parcial</li>";
        } else {
            $filtrosDesc .= "<li><b>Status :</b> Liquidados/Parcial/Abertos</li>";
        }
        if(isset($filtros['agendamento_bordero']) && !empty($filtros['agendamento_bordero'])) {
            $where['bordero_id'] = ['!=', NULL];
        }

        if(isset($filtros['tipo_pagamento']) && !isset($filtros['tipo_recebimento'])) {
            $where['agendamentos.tipo_movimento'] = 'PGT';
            $filtrosDesc .= "<li><b>Tipo de Pagamento:</b> Pagamentos</li>";
        } else if(isset($filtros['tipo_recebimento']) && !isset($filtros['tipo_pagamento'])) {
            $where['agendamentos.tipo_movimento'] = 'RCT';
            $filtrosDesc .= "<li><b>Tipo de Pagamento:</b> Recebimentos</li>";
        } else {
            $filtrosDesc .= "<li><b>Tipo de Pagamento:</b> Pagamentos/Recebimentos</li>";
        }
        
        $filtrosDesc .="</ol>";

        if(isset($orderByColumn) && !empty($orderByColumn)) {
            $orderBy[0] = Input::get('orderByColumn');
            $orderBy[1] = Input::get('orderByDirecion');
        }
        
        // Orçando x Realizado
        $orcado = false;
        $title = 'Relatório Financeiro - DRE';
        if(isset($filtros['orcado'])) {
            $orcado = true;
            $title = 'Relatório Financeiro - DRE - Orçado x Realizado';
        }

        $data['planosContas'] = $this->planoContaRepository->dre($where, $orderBy);
        $data['colunas'] = Input::get('colunas');
        $data['where'] = json_encode($where);
        $data['orcado'] = $orcado;

        if(Input::get('printIn') == 'PDF') {
            $pdf = PDF::loadView('financeiro.relatorios.demonstrativos_resultados.report', $data);
        
            $orientacao = Input::get('orientacao');
            if($orientacao == 'H') {
                $pdf->setPaper('a4', 'landscape');
            }

            $pdf->stream();
            $pdf->save($this->dreReport);
            return Redirect::to($this->dreReport);
        } else {
            return view('financeiro.relatorios.demonstrativos_resultados.report', [
                'planosContas'=> $data['planosContas'], 'colunas'=>$data['colunas'],
                'where' => $data['where'], 'orcado' => $orcado, 'title' => $title, 'filtrosDesc' => $filtrosDesc
            ]);
        }

        
    }

    public function demonstrativo_resultado_details($id, Request $request) {

        set_time_limit(900);

        $query = objectToArray(json_decode($request['query']));
        
        $data['planoConta'] = $this->planoContaRepository->find($id);

        if(count($data['planoConta']->correcoes) > 0) {
            $query['agendamentos.correcao_financeira_id'] = $data['planoConta']->correcoes[0]->id;
            $data['agendamentos'] = $this->lancamentoBancarioRepository->getToDreDetailsDeJurosMulta($query);
        } 
        else if(count($data['planoConta']->descontos) > 0) {
            $query['lancamentos_bancarios.desconto_id'] = $data['planoConta']->descontos[0]->id;
            $data['agendamentos'] = $this->lancamentoBancarioRepository->getToDreDetailsPorDesconto($query);
        } else {
            $query['agendamento_rateio.plano_conta_id'] = $id;
            $data['agendamentos'] = $this->agendamentoRepository->getToDreDetails($query);
        }

        $title = 'Detalhamento - DRE';

        if(Input::get('printIn') == 'PDF') {
            $pdf = PDF::loadView('financeiro.relatorios.demonstrativos_resultados.report_details', $data);
        
            $orientacao = Input::get('orientacao');
            if($orientacao == 'H') {
                $pdf->setPaper('a4', 'landscape');
            }

            $pdf->stream();
            $pdf->save($this->dreDetailsReport);
            return Redirect::to($this->dreDetailsReport);
        } else {
            return view('financeiro.relatorios.demonstrativos_resultados.report_details', [
                'agendamentos'=> $data['agendamentos'],
                'planoConta' => $data['planoConta'],
                'title' => $title
            ]);
        }

        
    }

    public function razao(Request $request) {

        set_time_limit(900);

        $where = null;
        $orderBy[0] = 'plano_conta_id';
        $orderBy[1] = 'ASC';
        $orderByColumn = Input::get('orderByColumn');

        $filtros = Input::get('filtros');
        $filtrosDesc = '<ol class="breadcrumb">';

        if(isset($filtros['data_competencia_ini']) && isset($filtros['data_competencia_end']) && !empty($filtros['data_competencia_ini']) && !empty($filtros['data_competencia_end'])) {
            $data_ini = $filtros['data_competencia_ini'];
            $data_end = $filtros['data_competencia_end'];
            $where['data_competencia'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];

            $filtrosDesc .= "<li><b>Data Competência:</b> ".$data_ini." à ".$data_end."</li>";
        }
        if(isset($filtros['data_vencimento_ini']) && isset($filtros['data_vencimento_end']) && !empty($filtros['data_vencimento_ini']) && !empty($filtros['data_vencimento_end'])) {
            $data_ini = $filtros['data_vencimento_ini'];
            $data_end = $filtros['data_vencimento_end'];
            $where['data_vencimento'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];

            $filtrosDesc .= "<li><b>Data Vencimento:</b> ".$data_ini." à ".$data_end."</li>";
        }
        if(isset($filtros['data_baixa_ini']) && isset($filtros['data_baixa_end']) && !empty($filtros['data_baixa_ini']) && !empty($filtros['data_baixa_end'])) {
            $data_ini = $filtros['data_baixa_ini'];
            $data_end = $filtros['data_baixa_end'];
            $where['data_liquidacao'] = ['whereBetweenDate', array( convertDateEn(trim($data_ini)), convertDateEn(trim($data_end))) ];

            $filtrosDesc .= "<li><b>Data de Baixa:</b> ".$data_ini." à ".$data_end."</li>";
        }

        if(isset($filtros['agendamento_aberto']) && isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_liquidados'])) {
            $where['valor_saldo'] = ['>', 0];
            $filtrosDesc .= "<li><b>Status :</b> Abertos/Parcial</li>";
        }
        else if(isset($filtros['agendamento_liquidados']) && isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_aberto'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo != valor_titulo'];
            $filtrosDesc .= "<li><b>Status :</b> Parcial/Liquidados</li>";
        }
        else if(isset($filtros['agendamento_liquidados']) && isset($filtros['agendamento_aberto']) && !isset($filtros['agendamento_parcial'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo = valor_titulo and valor_saldo = 0'];
            $filtrosDesc .= "<li><b>Status :</b> Abertos/Liquidados</li>";
        }
        else if(isset($filtros['agendamento_aberto']) && !isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_liquidados'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo = valor_titulo'];
            $filtrosDesc .= "<li><b>Status :</b> Abertos</li>";
        }
        else if(isset($filtros['agendamento_liquidados']) && !isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_aberto'])) {
            $where['valor_saldo'] = 0;
            $filtrosDesc .= "<li><b>Status :</b> Liquidados</li>";
        }
        else if(isset($filtros['agendamento_parcial']) && !isset($filtros['agendamento_liquidados']) && !isset($filtros['agendamento_aberto'])) {
            $where['valor_saldo'] = ['whereRaw', 'valor_saldo < valor_titulo and valor_saldo > 0'];
            $filtrosDesc .= "<li><b>Status :</b> Parcial</li>";
        } 
        else {
            $filtrosDesc .= "<li><b>Status :</b> Liquidados/Parcial/Abertos</li>";
        }

        $filtrosDesc .="</ol>";

        if(isset($orderByColumn) && !empty($orderByColumn)) {
            $orderBy[0] = Input::get('orderByColumn');
            $orderBy[1] = Input::get('orderByDirecion');
        }

        $agendamentos = $this->planoContaRepository->getRazao($where, $orderBy);

        if(Input::get('printIn') == 'PDF') {
            $pdf = PDF::loadView('financeiro.relatorios.razao.report', $data['datas'][$i]);
        
            $orientacao = Input::get('orientacao');
            if($orientacao == 'H') {
                $pdf->setPaper('a4', 'landscape');
            }

            $pdf->stream();
            $pdf->save($this->fluxoCaixaReport);
            return Redirect::to($this->fluxoCaixaReport);
        } else {

            $title = 'Relatório de Razão - Período ';

            return view('financeiro.relatorios.razao.report', [
                'agendamentos'  => $agendamentos,
                'title' => $title,
                'filtrosDesc'   => $filtrosDesc,
                'orderBy'   => $orderBy[0]
            ]);
        }

        
    }


}
