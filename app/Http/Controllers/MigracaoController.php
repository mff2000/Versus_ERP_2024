<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\User;
use App\Model\SqlServer\BancoSql;
use App\Model\Banco;

use App\Model\SqlServer\CentroResultadoSql;
use App\Model\CentroResultado;

use App\Model\SqlServer\FavorecidoSql;
use App\Model\Favorecido;

use App\Model\SqlServer\PlanoContaSql;
use App\Model\ContaContabel;

use App\Model\SqlServer\FormaFinanceiraSql;
use App\Model\FormaFinanceira;

use App\Model\SqlServer\AgendamentoSql;
use App\Model\Financeiro\Agendamento;
use App\Model\Financeiro\Rateio;

use App\Model\SqlServer\LancamentoSql;
use App\Model\Financeiro\LancamentoBancario;

use App\Model\SqlServer\TransferenciaSql;
use App\Model\Financeiro\TransferenciaBancaria;

use App\Model\SqlServer\LancamentoGerencialSql;
use App\Model\Financeiro\LancamentoGerencial;

use App\Model\SqlServer\OrcamentoSql;
use App\Model\Financeiro\Orcamento;

class MigracaoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        return User::create([
            'name' => 'Jean',
            'email' => 'jrgama10@gmail.com',
            'remember_token' => env('APP_KEY'),
            'password' => bcrypt('versus@2016'),
        ]);
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        ini_set('memory_limit', '-1'); 
        set_time_limit(3600);
        
        $bancoSql = new BancoSql();
        foreach ($bancoSql->all() as $key => $value) {
             
            if($value->del_id == 0)  {
                 $banco = new Banco();
                 
                 $banco->id = $value->id;
                 $banco->codigo = $value->codigo;
                 $banco->agencia = $value->agencia;
                 $banco->dv_agencia = $value->dv_agencia;
                 $banco->numero_conta = $value->numero_conta;
                 $banco->dv_conta = $value->dv_conta;
                 $banco->descricao = $value->descricao;
                 $banco->saldo_atual = $value->saldo_atual;
                 $banco->save();
            }
        }

        $centroSql = new CentroResultadoSql();
        foreach ($centroSql->orderBy('id', 'asc')->get() as $key => $value) {
             
             if($value->del_id == 0)  {

                $centro = new CentroResultado();
                 
                $centro->id = $value->id;

                $codigos = explode(".", $value->codigo);
                $count = count($codigos);

                //$centroPai = $centroSql->where('codigo', substr($value->codigo, 0, strrpos($value->codigo, ".")))->first();

                $centro->codigo = $codigos[$count-1];
                $centro->descricao = $value->descricao;
                $centro->classe = $value->classe;

                if(isset($value->conta_superior) && !empty($value->conta_superior)) {
                    $cS = $centro->where('codigo_devra', $value->conta_superior)->first();
                    $centro->conta_superior = $cS->id;
                }

                $centro->codigo_devra = $value->codigo;
                $centro->save();

             }
        }
        

        $favorecidoSql = new FavorecidoSql();
        foreach ($favorecidoSql->all() as $key => $value) {
            
            if($value->del_id == 0)  {

                $favorecido = new Favorecido();
                
                $favorecido->id = $value->id;
                $favorecido->tipo_pessoa = $value->tipo_pessoa;
                $favorecido->cnpj = $value->cnpj;
                $favorecido->inscricao_estadual = utf8_encode($value->inscricao_estadual);
                $favorecido->nome_empresarial = utf8_encode($value->nome_empresarial);
                $favorecido->nome_fantasia = $value->nome_fantasia;
                $favorecido->cep = $value->cep;
                $favorecido->endereco = $value->endereco;
                $favorecido->numero = $value->numero;
                $favorecido->complemento = $value->complemento;
                $favorecido->bairro = $value->bairro;
                $favorecido->cidade = $value->cidade;
                $favorecido->uf = $value->uf;
                $favorecido->entrega_cep = $value->entrega_cep;
                $favorecido->entrega_endereco = $value->entrega_endereco;
                $favorecido->entrega_numero = $value->entrega_numero;
                $favorecido->entrega_complemento = $value->entrega_complemento;
                $favorecido->entrega_bairro = $value->entrega_bairro;
                $favorecido->entrega_cidade = $value->entrega_cidade;
                $favorecido->entrega_uf = $value->entrega_uf;
                $favorecido->cobranca_cep = $value->cobranca_cep;
                $favorecido->cobranca_endereco = $value->cobranca_endereco;
                $favorecido->cobranca_numero = $value->cobranca_numero;
                $favorecido->cobranca_complemento = $value->cobranca_complemento;
                $favorecido->cobranca_bairro = $value->cobranca_bairro;
                $favorecido->cobranca_cidade = $value->cobranca_cidade;
                $favorecido->cobranca_uf = $value->cobranca_uf;
                $favorecido->tel_fixo1 = $value->tel_fixo1;
                $favorecido->tel_fixo2 = $value->tel_fixo2;
                $favorecido->tel_movel1 = $value->tel_movel1;
                $favorecido->tel_movel2 = $value->tel_movel2;
                $favorecido->email_geral = $value->email_geral;
                $favorecido->email_nfe = $value->email_nfe;
                $favorecido->email_financ = $value->email_financ;
                $favorecido->contato_financ = $value->contato_financ;
                $favorecido->contato_geral = $value->contato_geral;
                $favorecido->contato_fiscal = $value->contato_fiscal;
                $favorecido->limite_credito = $value->limite_credito;
                $favorecido->data_validade = $value->data_validade;
                $favorecido->risco_credito = $value->risco_credito;
                         
                $favorecido->save();
            }
        }
        
        $planoSql = new PlanoContaSql();
        foreach ($planoSql->all() as $key => $value) {
            
            if($value->del_id == 0)  {
                
                $plano = new ContaContabel();
                
                $codigos = explode(".", $value->codigo);
                $count = count($codigos);

                $planoPai = $planoSql->where('codigo', substr($value->codigo, 0, strrpos($value->codigo, ".")))->first();

                $plano->id = $value->id; 
                $plano->codigo = $codigos[$count-1];
                $plano->descricao = utf8_encode($value->descricao);
                $plano->classe = $value->classe;
                
                if($value->natureza == null)
                    $plano->natureza = "";

                if($planoPai)
                    $plano->conta_superior = $planoPai->id;

                $plano->ativo = $value->ativo;
                $plano->conta_contabil_ext = $value->codigo;

                $plano->save();
            }
        }
        
       
        $formaSql = new FormaFinanceiraSql();
        foreach ($formaSql->all() as $key => $value) {
            
            $forma = new FormaFinanceira();
            
            $forma->id = $value->id;
            $forma->codigo = $value->codigo;
            $forma->descricao = $value->descricao;
            if($value->liquida_automatico == 1)
                $forma->liquida = 'S';
            else
                $forma->liquida = 'N';

            $forma->ativo = $value->ativo;
            
            $forma->save();
        }
        

        $agendamentoSql = new AgendamentoSql();
        foreach ($agendamentoSql->all() as $key => $value) {
            
            if($value->del_id == 0)  {

                $agendamento = new Agendamento();
                $agendamento = $agendamento->find($value->id);

                if(!isset($agendamento->id)) {

                    $agendamento = new Agendamento();

                    $agendamento->id = $value->id;
                    $agendamento->numero_titulo = $value->numero_titulo;
                    $agendamento->numero_parcela = $value->numero_parcela;
                    $agendamento->tipo_movimento = $value->tipo_movimento;
                    $agendamento->historico = $value->historico;
                    $agendamento->tags = $value->tags;
                    $agendamento->valor_titulo = $value->valor_titulo;
                    $agendamento->valor_saldo = $value->valor_saldo;
                    $agendamento->data_competencia = $value->data_competencia;
                    $agendamento->data_vencimento = $value->data_vencimento;
                    $agendamento->codigo_link = $value->codigo_link;
                    $agendamento->item_link = $value->item_link;
                    $agendamento->nfe_serie = $value->nfe_serie;
                    $agendamento->nfe_numero = $value->nfe_numero;
                    $agendamento->pedido = $value->pedido;
                    $agendamento->contrato = $value->contrato;
                    $agendamento->sequencia = $value->sequencia;
                    $agendamento->correcao_financeira_id = NULL;
                    $agendamento->favorecido_id = $value->favorecido_id;
                    $agendamento->bordero_id = $value->bordero_id;
                    
                    $agendamento->save();

                    $planos = new ContaContabel();
                    $planoContaAgendamento = $planos->where('conta_contabil_ext', $value->conta_financeira)->first();
                   

                    $centros = new CentroResultado();
                    $centroResultadoAgendamento = $centros->where('codigo_devra', $value->centro_resultado)->first();
                   

                    $rateio = new Rateio();
                    $rateio->plano_conta_id = $planoContaAgendamento->id;
                    $rateio->centro_resultado_id = $centroResultadoAgendamento->id;
                    $rateio->projeto_id = 1;
                    $rateio->porcentagem = 100;
                    $rateio->valor = $agendamento->valor_titulo;
                    $rateio->ordem =1 ;
                    $rateio->agendamento_id = $agendamento->id;
                    $rateio->lancamento_id = null;

                    $rateio->save();
                }
            }
        }

        $lancamentoSql = new LancamentoSql();
        foreach ($lancamentoSql->all() as $key => $value) {
            
            if($value->del_id == 0)  {

                $lancamento = new LancamentoBancario();
                $lancamento->id = $value->id;
                $lancamento->numero_titulo = $value->numero_titulo;
                $lancamento->numero_parcela = $value->numero_parcela;
                $lancamento->favorecido_id = $value->favorecido_id;
                $lancamento->tipo_movimento = $value->tipo_movimento;
                $lancamento->tipo_baixa = $value->tipo_baixa;
                $lancamento->sequencia_baixa = $value->sequencia_baixa;
                $lancamento->numero_cheque = $value->numero_cheque;
                $lancamento->historico = $value->historico;
                $lancamento->tags = $value->tags;
                $lancamento->banco_id = $value->banco_id;
                $lancamento->valor_lancamento = $value->valor_lancamento;
                $lancamento->valor_multa = $value->valor_multa;
                $lancamento->valor_juros = $value->valor_juros;
                $lancamento->valor_desconto = $value->valor_desconto;
                $lancamento->data_lancamento = $value->data_lancamento;
                $lancamento->data_liquidacao = $value->data_liquidacao;
                $lancamento->log_ins = $value->log_ins;
                $lancamento->log_upd = $value->log_upd;
                $lancamento->agendamento_id = $value->agendamento_id;
                $lancamento->agendamento_relacionado_id = $value->agendamento_relacionado_id;
                $lancamento->lancamento_relacionado_id = $value->lancamento_relacionado_id;
                $lancamento->status = $value->status;
                $lancamento->bordero_id = $value->bordero_id;
                $lancamento->baixa_id = NULL;

                $forma = new FormaFinanceira();
                $forma = $forma->where("codigo", $value->forma_financeira)->first();
                if(isset($forma->id))
                    $lancamento->forma_financeira_id = $forma->id;
                else
                    $lancamento->forma_financeira_id = 1;

                $lancamento->desconto_id = NULL;

                $lancamento->save();

                $planos = new ContaContabel();
                $planoContaAgendamento = $planos->where('conta_contabil_ext', $value->conta_financeira)->first();
               

                $centros = new CentroResultado();
                $centroResultadoAgendamento = $centros->where('codigo_devra', $value->centro_resultado)->first();
               

                $rateio = new Rateio();
                
                if(isset($planoContaAgendamento->id))
                   $rateio->plano_conta_id = $planoContaAgendamento->id;
                else
                    $rateio->plano_conta_id = 1000;

                
                if(isset($centroResultadoAgendamento->id))
                   $rateio->centro_resultado_id = $centroResultadoAgendamento->id;
                else
                    $rateio->centro_resultado_id = 1000;

                $rateio->projeto_id = 1;
                $rateio->porcentagem = 100;
                $rateio->valor = $lancamento->valor_lancamento;
                $rateio->ordem = 1 ;
                $rateio->agendamento_id = null;
                $rateio->lancamento_id = $lancamento->id;

                $rateio->save();

            }
        }

        $transferenciaSql = new TransferenciaSql();
        foreach ($transferenciaSql->all() as $key => $value) {

            if($value->del_id == 0)  {

                $tranferencia = new TransferenciaBancaria();
                $tranferencia->id = $value->id;
                $tranferencia->numero_titulo = $value->numero_titulo;
                $tranferencia->numero_parcela = $value->numero_parcela;
                $tranferencia->tipo_movimento = $value->tipo_movimento;
                $tranferencia->numero_cheque = $value->numero_cheque;
                $tranferencia->historico = $value->historico;
                $tranferencia->tags = $value->tags;
                $tranferencia->banco_origem_id = $value->banco_origem_id;
                $tranferencia->banco_destino_id = $value->banco_destino_id;
                $tranferencia->valor_lancamento = $value->valor_lancamento;
                $tranferencia->data_lancamento = $value->data_lancamento;
                $tranferencia->log_ins = $value->log_ins;
                $tranferencia->log_upd = $value->log_upd;
                $tranferencia->status = $value->status;

                $tranferencia->save();
            }
        }

        $lancamentoSql = new LancamentoGerencialSql();
        foreach ($lancamentoSql->all() as $key => $value) {

            if($value->del_id == 0)  {

                $lancamento = new LancamentoGerencial();
                $lancamento->id = $value->id;
                $lancamento->numero_titulo = $value->numero_titulo;
                $lancamento->numero_parcela = $value->numero_parcela;
                $lancamento->favorecido_id = $value->favorecido_id;
                $lancamento->historico = $value->historico;
                $lancamento->tags = $value->tags;
                $lancamento->valor_lancamento = $value->valor_lancamento;
                $lancamento->data_lancamento = $value->data_lancamento;
                $lancamento->log_ins = $value->log_ins;
                $lancamento->log_upd = $value->log_upd;
                $lancamento->status = $value->status;

                $planos = new ContaContabel();
                $planoContaAgendamento = $planos->where('conta_contabil_ext', $value->conta_financeira_debito)->first();
                $lancamento->plano_conta_debito_id = (isset($planoContaAgendamento)) ? $planoContaAgendamento->id : 1000;
                
                $planos = new ContaContabel();
                $planoContaAgendamento = $planos->where('conta_contabil_ext', $value->conta_financeira_credito)->first();
                $lancamento->plano_conta_credito_id = (isset($planoContaAgendamento)) ? $planoContaAgendamento->id : 1000;

                $centros = new CentroResultado();
                $centroResultadoAgendamento = $centros->where('codigo_devra', $value->centro_resultado_credito)->first();
                $lancamento->centro_resultado_credito_id = (isset($centroResultadoAgendamento)) ? $centroResultadoAgendamento->id : 1000;

                $centros = new CentroResultado();
                $centroResultadoAgendamento = $centros->where('codigo_devra', $value->centro_resultado_debito)->first();
                $lancamento->centro_resultado_debito_id = (isset($centroResultadoAgendamento)) ? $centroResultadoAgendamento->id : 1000;
                
                $lancamento->projeto_credito_id = 1;
                $lancamento->projeto_debito_id = 1;

                $lancamento->save();
            }
        }

        $orcamentoSql = new OrcamentoSql();
        foreach ($orcamentoSql->all() as $key => $value) {

            if($value->del_id == 0)  {

                $orcamento = new Orcamento();
                $orcamento->id = $value->id;
                $orcamento->tipo_movimento = $value->tipo_movimento;
                $orcamento->historico = $value->historico;
                $orcamento->tags = $value->tags;
                $orcamento->valor_lancamento = $value->valor_lancamento;
                $orcamento->data_competencia = $value->data_competencia;
                $orcamento->data_vencimento = $value->data_vencimento;
                $orcamento->log_ins = $value->log_ins;
                $orcamento->log_upd = $value->log_upd;
                
                $planos = new ContaContabel();
                $planoConta = $planos->where('conta_contabil_ext', $value->conta_financeira)->first();
                $orcamento->plano_conta_id = (isset($planoConta)) ? $planoConta->id : 1000;
                
                $planos = new CentroResultado();
                $centroResultado = $planos->where('codigo_devra', $value->centro_resultado)->first();
                $orcamento->centro_resultado_id = (isset($centroResultado)) ? $centroResultado->id : 1000;
                
                $orcamento->projeto_id = 1;

                $orcamento->save();
            }
        }

        echo "Migração efetuada com sucesso!";
    }
    
}
