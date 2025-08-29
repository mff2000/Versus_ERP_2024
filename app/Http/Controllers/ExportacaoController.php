<?php

namespace App\Http\Controllers;

use Excel;
use Input;
use Flash;
use Redirect;
use DB;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Financeiro\AgendamentoRepository;
use App\Repositories\Financeiro\BorderoRepository;
use App\Repositories\Financeiro\LancamentoBancarioRepository;
use App\Repositories\Financeiro\LancamentoGerencialRepository;
use App\Repositories\Financeiro\OrcamentoRepository;
use App\Repositories\Financeiro\TransferenciaBancariaRepository;

use App\Exceptions\Validation\ValidationException;

class ExportacaoController extends Controller
{
    //
	protected $agendamento;
    protected $bordero;
    protected $lancamentoBancario;
    protected $lancamentoGerencial;
    protected $orcamento;
    protected $transferencia;

	public function __construct(
        AgendamentoRepository $agendamento,
        BorderoRepository $bordero,
        LancamentoBancarioRepository $lancamentoBancario,
        LancamentoGerencialRepository $lancamentoGerencial,
        OrcamentoRepository $orcamento,
        TransferenciaBancariaRepository $transferencia
    )
    {
        $this->agendamento = $agendamento;
        $this->bordero = $bordero;
        $this->lancamentoBancario = $lancamentoBancario;
        $this->lancamentoGerencial = $lancamentoGerencial;
        $this->orcamento = $orcamento;
        $this->transferencia = $transferencia;

        // verifica login
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('exportacao');
    }

    public function gerarExcel(Request $request) {

        if (ob_get_level() > 0) { ob_end_clean(); }

        if($request->has('financeiro')) {
            $this->exportaFinaceiro($request->get('financeiro'));            
        } 
        elseif($request->has('cadastro')) {
            $this->exportaCadastro($request->get('cadastro'));
        } else {
            Flash::error('Nenhuma tabela selecionada!');
            return Redirect::action('ExportacaoController@index')->with('message', 'Success');
        }

    }

     private function exportaCadastro($tabelas) {
        
        // Initialize the array which will be passed into the Excel
        // generator.
        $cadastroArray = []; 

        foreach ($tabelas as $key => $tabela) {
            
            switch ($key) {
                case 'banco':
                    
                    // Define the Excel spreadsheet headers
                    $cadastroArray['banco'][] = ['ID', 'Código', 'Agência', 'Nº da Conta', 'Descricao', 'Saldo', 'Limite'];

                    $bancos = \App\Model\Cadastro\Banco::select(
                        'id', 'codigo', DB::raw('CONCAT(agencia, "-", dv_agencia)'), DB::raw('CONCAT(numero_conta, "-", dv_conta)'), 'descricao', 'saldo_atual', 'limite'
                    )->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($bancos as $banco) {
                        $cadastroArray['banco'][] = $banco->toArray();
                    }

                    break;
                
                case 'centro_resultado':
                    
                    // Define the Excel spreadsheet headers
                    $cadastroArray['centro_resultado'][] = ['ID', 'Código', 'Descricao', 'Classe', 'Conta Superior', 'Ativo'];

                    $centros = \App\Model\Cadastro\CentroResultado::select(
                        'id', 'codigo', 'descricao', 'classe', 'conta_superior', 'ativo'
                    )->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($centros as $centro) {
                        $cadastroArray['centro_resultado'][] = $centro->toArray();
                    }

                    break;
                
                case 'correcao_financeira':
                    
                    // Define the Excel spreadsheet headers
                    $cadastroArray['correcao_financeira'][] = ['ID', 'Descricao', 'Juros (%)', 'Período Juros', 'Multa (%)', 'Período Multa', 'Limite de Multa', 'Plano de Conta'];

                    $centros = \App\Model\Cadastro\CorrecaoFinanceira::select(
                        'correcao_financeiras.id', 'correcao_financeiras.descricao', 'aliquota_juros', 'periodo_juros', 'aliquota_multa', 'periodo_multa', 'limite_multa', 'planos_contas.descricao as plano_conta'
                    )->leftJoin('planos_contas', 'correcao_financeiras.plano_conta_id', '=', 'planos_contas.id')->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($centros as $centro) {
                        $cadastroArray['correcao_financeira'][] = $centro->toArray();
                    }

                    break;

                case 'desconto':
                    
                    // Define the Excel spreadsheet headers
                    $cadastroArray['desconto'][] = ['ID', 'Descricao', 'Plano de Conta'];

                    $descontos = \App\Model\Cadastro\Desconto::select(
                        'descontos.id', 'descontos.descricao', 'planos_contas.descricao as plano_conta'
                    )->leftJoin('planos_contas', 'descontos.plano_conta_id', '=', 'planos_contas.id')->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($descontos as $desconto) {
                        $cadastroArray['desconto'][] = $desconto->toArray();
                    }

                    break;

                case 'favorecido':
                    
                    // Define the Excel spreadsheet headers
                    $cadastroArray['favorecido'][] = ['ID', 'Tipo', 'CNPJ/CPF', 'Inscrição Estadual', 'Razão Social', 'Nome Fantasia', 'Telefone 1', 'Telefone 2', 'Celular 1', 'Celular 2', 'E-mail'];

                    $favorecidos = \App\Model\Cadastro\Favorecido::select(
                        'id', 'tipo_pessoa', 'cnpj', 'inscricao_estadual', 'nome_empresarial', 'nome_fantasia', 'tel_fixo1', 'tel_fixo2', 'tel_movel1', 'tel_movel2', 'email_geral'
                    )->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($favorecidos as $favorecido) {
                        $cadastroArray['favorecido'][] = $favorecido->toArray();
                    }

                    break;

                case 'forma_financeira':
                    
                    // Define the Excel spreadsheet headers
                    $cadastroArray['forma_financeira'][] = ['ID', 'Código', 'Descrição', 'Liquída?', 'Ativo'];

                    $formas = \App\Model\Cadastro\FormaFinanceira::select(
                        'id', 'codigo', 'descricao', 'liquida', 'ativo'
                    )->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($formas as $forma) {
                        $cadastroArray['forma_financeira'][] = $forma->toArray();
                    }

                    break;

                case 'plano_conta':
                    
                    // Define the Excel spreadsheet headers
                    $cadastroArray['plano_conta'][] = ['ID', 'Código', 'Descrição', 'Classe', 'Natureza', 'Conta Superior', 'Ativo'];

                    $planos = \App\Model\Cadastro\ContaContabel::select(
                        'id', 'codigo', 'descricao', 'classe', 'natureza', 'conta_superior', 'ativo'
                    )->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($planos as $plano) {
                        $cadastroArray['plano_conta'][] = $plano->toArray();
                    }

                    break;

                case 'projeto':
                    
                    // Define the Excel spreadsheet headers
                    $cadastroArray['projeto'][] = ['ID', 'Código', 'Descrição', 'Classe', 'Conta Superior', 'Ativo'];

                    $projetos = \App\Model\Cadastro\Projeto::select(
                        'id', 'codigo', 'descricao', 'classe', 'conta_superior', 'ativo'
                    )->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($projetos as $projeto) {
                        $cadastroArray['projeto'][] = $projeto->toArray();
                    }

                    break;

                default:
                    # code...
                    break;
            }

        }


        // Generate and return the spreadsheet
        Excel::create('Cadastros', function($excel) use ($cadastroArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Dados Cadastrais');
            $excel->setCreator('Versus ERP')->setCompany(env('AUTHTO_COMPANY'));
            $excel->setDescription('Exportação do módulo de cadastros');

            if(isset($cadastroArray['banco'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Bancos', function($sheet) use ($cadastroArray) {
                    $sheet->fromArray($cadastroArray['banco'], null, 'A1', false, false);
                });
            }

            if(isset($cadastroArray['centro_resultado'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Centros de Resutado', function($sheet) use ($cadastroArray) {
                    $sheet->fromArray($cadastroArray['centro_resultado'], null, 'A1', false, false);
                });
            }

            if(isset($cadastroArray['correcao_financeira'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Correções Financeira', function($sheet) use ($cadastroArray) {
                    $sheet->fromArray($cadastroArray['correcao_financeira'], null, 'A1', false, false);
                });
            }

            if(isset($cadastroArray['desconto'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Descontos', function($sheet) use ($cadastroArray) {
                    $sheet->fromArray($cadastroArray['desconto'], null, 'A1', false, false);
                });
            }

            if(isset($cadastroArray['favorecido'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Favorecidos', function($sheet) use ($cadastroArray) {
                    $sheet->fromArray($cadastroArray['favorecido'], null, 'A1', false, false);
                });
            }

            if(isset($cadastroArray['forma_financeira'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Formas Financerias', function($sheet) use ($cadastroArray) {
                    $sheet->fromArray($cadastroArray['forma_financeira'], null, 'A1', false, false);
                });
            }

            if(isset($cadastroArray['plano_conta'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Planos de Conta', function($sheet) use ($cadastroArray) {
                    $sheet->fromArray($cadastroArray['plano_conta'], null, 'A1', false, false);
                });
            }

            if(isset($cadastroArray['projeto'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Projetos', function($sheet) use ($cadastroArray) {
                    $sheet->fromArray($cadastroArray['projeto'], null, 'A1', false, false);
                });
            }

        })->download('xls');

    }

    private function exportaFinaceiro($tabelas) {
        
        // Initialize the array which will be passed into the Excel
        // generator.
        $financeiroArray = []; 

        foreach ($tabelas as $key => $tabela) {
            
            switch ($key) {
                case 'agendamento':
                    
                    // Define the Excel spreadsheet headers
                    $financeiroArray['agendamento'][] = ['ID', 'Número', 'Parcela', 'Tipo', 'Histórico', 'Favorecido', 'Valor', 'Saldo', 'Competência', 'Vencimento'];

                    $agendamentos = \App\Model\Financeiro\Agendamento::select(
                        'agendamentos.id', 'numero_titulo', 'numero_parcela', 'tipo_movimento', 'historico', 'favorecidos.nome_empresarial', 'valor_titulo', 'valor_saldo', 'data_competencia', 'data_vencimento'
                    )->leftJoin('favorecidos', 'agendamentos.favorecido_id', '=', 'favorecidos.id')->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($agendamentos as $agendamento) {
                        $financeiroArray['agendamento'][] = $agendamento->toArray();
                    }

                    break;
                
                case 'bordero':
                    
                    // Define the Excel spreadsheet headers
                    $financeiroArray['bordero'][] = ['ID', 'Tipo', 'Descrição', 'Emissão', 'Valor', 'Saldo', 'Liquidação', 'Observação'];

                    $borderos = \App\Model\Financeiro\Bordero::select(
                        'id', 'tipo_bordero', 'descricao', 'data_emissao', 'data_emissao', 'valor', 'saldo', 'data_liquidacao', 'observacoes'
                    )->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($borderos as $bordero) {
                        $financeiroArray['bordero'][] = $bordero->toArray();
                    }

                    break;

                case 'lancamento_bancario':
                    
                    // Define the Excel spreadsheet headers
                    $financeiroArray['lancamento_bancario'][] = ['ID', 'Número', 'Parcela', 'Tipo', 'Histórico', 'Favorecido', 'Valor', 'Lançamento', 'Liquidação', 'Banco', 'Agendamento Relacionado'];

                    $lancamentos = \App\Model\Financeiro\LancamentoBancario::select(
                        'lancamentos_bancarios.id', 'numero_titulo', 'numero_parcela', 'tipo_movimento', 'historico', 'favorecidos.nome_empresarial', 'valor_lancamento', 'data_lancamento', 'data_liquidacao', 'bancos.descricao', 'agendamento_id'
                    )
                    ->leftJoin('bancos', 'lancamentos_bancarios.banco_id', '=', 'bancos.id')
                    ->leftJoin('favorecidos', 'lancamentos_bancarios.favorecido_id', '=', 'favorecidos.id')->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($lancamentos as $lancamento) {
                        $financeiroArray['lancamento_bancario'][] = $lancamento->toArray();
                    }

                    break;

                case 'lancamento_gerencial':
                    
                    // Define the Excel spreadsheet headers
                    $financeiroArray['lancamento_gerencial'][] = ['ID', 'Número', 'Parcela', 'Histórico', 'Favorecido', 'Valor', 'Lançamento'];

                    $lancamentos = \App\Model\Financeiro\LancamentoGerencial::select(
                        'lancamentos_gerenciais.id', 'numero_titulo', 'numero_parcela', 'historico', 'favorecidos.nome_empresarial', 'valor_lancamento', 'data_lancamento'
                    )
                    ->leftJoin('favorecidos', 'lancamentos_gerenciais.favorecido_id', '=', 'favorecidos.id')->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($lancamentos as $lancamento) {
                        $financeiroArray['lancamento_gerencial'][] = $lancamento->toArray();
                    }

                    break;

                case 'orcamento':
                    
                    // Define the Excel spreadsheet headers
                    $financeiroArray['orcamento'][] = ['ID', 'Tipo', 'Histórico', 'Valor', 'Lançamento', 'Vencimento'];

                    $orcamentos = \App\Model\Financeiro\Orcamento::select(
                        'id', 'tipo_movimento', 'historico', 'valor_lancamento', 'data_competencia', 'data_vencimento'
                    )
                    ->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($orcamentos as $orcamento) {
                        $financeiroArray['orcamento'][] = $orcamento->toArray();
                    }

                    break;

                case 'transferencia':
                    
                    // Define the Excel spreadsheet headers
                    $financeiroArray['transferencia'][] = ['ID', 'Banco Origem', 'Banco Destino', 'Histórico', 'Valor', 'Lançamento'];

                    $transferencias = \App\Model\Financeiro\TransferenciaBancaria::select(
                        'transferencias_bancarias.id', 'banco_origem.descricao as origem', 'banco_destino.descricao as destino', 'historico', 'valor_lancamento', 'data_lancamento'
                    )
                    ->join('bancos as banco_origem', function ($join) {
                        $join->on('banco_origem.id', '=', 'transferencias_bancarias.banco_origem_id');
                    })->join('bancos as banco_destino', function ($join) {
                        $join->on('banco_destino.id', '=', 'transferencias_bancarias.banco_destino_id');
                    })
                    ->get();

                    // Convert each member of the returned collection into an array,
                    // and append it to the payments array.
                    foreach ($transferencias as $transferencia) {
                        $financeiroArray['transferencia'][] = $transferencia->toArray();
                    }

                    break;

                default:
                    # code...
                    break;
            }

        }


        // Generate and return the spreadsheet
        Excel::create('Financeiro', function($excel) use ($financeiroArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Dados do Financeiro');
            $excel->setCreator('Versus ERP')->setCompany(env('AUTHTO_COMPANY'));
            $excel->setDescription('Exportação do módulo financeiro');

            if(isset($financeiroArray['agendamento'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('agendamentos', function($sheet) use ($financeiroArray) {
                    $sheet->fromArray($financeiroArray['agendamento'], null, 'A1', false, false);
                });
            }

            if(isset($financeiroArray['bordero'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('borderôs', function($sheet) use ($financeiroArray) {
                    $sheet->fromArray($financeiroArray['bordero'], null, 'A1', false, false);
                });
            }

            if(isset($financeiroArray['lancamento_bancario'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Lançamentos Bancários', function($sheet) use ($financeiroArray) {
                    $sheet->fromArray($financeiroArray['lancamento_bancario'], null, 'A1', false, false);
                });
            }

            if(isset($financeiroArray['lancamento_gerencial'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Lançamentos Gerenciais', function($sheet) use ($financeiroArray) {
                    $sheet->fromArray($financeiroArray['lancamento_gerencial'], null, 'A1', false, false);
                });
            }

            if(isset($financeiroArray['orcamento'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Lançamentos Orçamento', function($sheet) use ($financeiroArray) {
                    $sheet->fromArray($financeiroArray['orcamento'], null, 'A1', false, false);
                });
            }

            if(isset($financeiroArray['transferencia'])) {
                
                // Build the spreadsheet, passing in the payments array
                $excel->sheet('Transferências Bancárias', function($sheet) use ($financeiroArray) {
                    $sheet->fromArray($financeiroArray['transferencia'], null, 'A1', false, false);
                });
            }

        })->download('xls');

    }

}
