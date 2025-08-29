<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\Banco;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class BancoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $banco;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'codigo' => 'required',
        'agencia' => 'required',
        'dv_agencia' => 'required',
        'numero_conta' => 'required',
        'dv_conta' => 'required',
        'descricao' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Banco $banco)
    {
        $this->banco = $banco;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->banco->all();
    }

    public function calcSaldoAt($dateIni, $bancoId = null) {
    //---calculo do saldo anterior-----------------------------------------------------------------------

       $valor_adp = DB::table('lancamentos_bancarios as L')
                    ->whereIn('tipo_movimento', ['ADP'])
                    ->where('deleted_at', '=', NULL)
                    ->whereDate('data_liquidacao', '<', new DateTime($dateIni))
                    ->selectRaw('COALESCE( 
                        SUM(
                            L.valor_lancamento + (SELECT COALESCE(sum(valor_lancamento),0) as valor_lancamento FROM juros_multas_descontos where tipo IN("J", "M") and lancamento_id = L.id) - (SELECT COALESCE(sum(valor_lancamento),0) FROM juros_multas_descontos where tipo = "D" and lancamento_id = L.id) 
                        )
                    ,0) as valor_lancamento');
        
        if(isset($bancoId))            
            $valor_adp = $valor_adp->where('banco_id', '=', $bancoId);

        $valor_adp = $valor_adp->first();

        $valor_adr = DB::table('lancamentos_bancarios as L')
                     ->whereIn('tipo_movimento', ['ADR'])
                     ->where('deleted_at', '=', NULL)
                     ->whereDate('data_liquidacao', '<', new DateTime($dateIni))
                     ->selectRaw('COALESCE( 
                        SUM(
                            L.valor_lancamento + (SELECT COALESCE(sum(valor_lancamento),0) FROM juros_multas_descontos where tipo IN("J", "M") and lancamento_id = L.id) - (SELECT COALESCE(sum(valor_lancamento),0) FROM juros_multas_descontos where tipo = "D" and lancamento_id = L.id) 
                        )
                    ,0) as valor_lancamento');

        if(isset($bancoId))            
            $valor_adr = $valor_adr->where('banco_id', '=', $bancoId);

        $valor_adr = $valor_adr->first();

        $valor_pgt = DB::table('lancamentos_bancarios as L')
                     ->where('tipo_movimento', '=', 'PGT')
                     ->where('deleted_at', '=', NULL)
                     ->whereDate('data_liquidacao', '<', new DateTime($dateIni))
                     ->selectRaw('COALESCE( 
                        SUM(
                            L.valor_lancamento + (SELECT COALESCE(sum(valor_lancamento),0) FROM juros_multas_descontos where tipo IN("J", "M") and lancamento_id = L.id) - (SELECT COALESCE(sum(valor_lancamento),0) FROM juros_multas_descontos where tipo = "D" and lancamento_id = L.id) 
                        )
                    ,0) as valor_lancamento');

        if(isset($bancoId))            
            $valor_pgt = $valor_pgt->where('banco_id', '=', $bancoId);

        $valor_pgt = $valor_pgt->first();

        $valor_rct = DB::table('lancamentos_bancarios as L')
                     ->where('tipo_movimento', '=', 'RCT')
                     ->where('deleted_at', '=', NULL)
                     ->whereDate('data_liquidacao', '<', new DateTime($dateIni))
                     ->selectRaw('COALESCE( 
                        SUM(
                            L.valor_lancamento + (SELECT COALESCE(sum(valor_lancamento),0) FROM juros_multas_descontos where tipo IN("J", "M") and lancamento_id = L.id) - (SELECT COALESCE(sum(valor_lancamento),0) FROM juros_multas_descontos where tipo = "D" and lancamento_id = L.id) 
                        )
                    ,0) as valor_lancamento');

        if(isset($bancoId))            
            $valor_rct = $valor_rct->where('banco_id', '=', $bancoId);

        $valor_rct = $valor_rct->first();

        $valor_trforig = DB::table('transferencias_bancarias')
                     ->where('deleted_at', '=', NULL)
                     ->whereDate('data_lancamento', '<', new DateTime($dateIni));

        if(isset($bancoId))            
            $valor_trforig = $valor_trforig->where('banco_origem_id', '=', $bancoId);
        
        $valor_trforig = $valor_trforig->sum('valor_lancamento');

        $valor_trfdest = DB::table('transferencias_bancarias')
                     ->where('deleted_at', '=', NULL)
                     ->whereDate('data_lancamento', '<', new DateTime($dateIni));
                     
        
        if(isset($bancoId))            
            $valor_trfdest = $valor_trfdest->where('banco_destino_id', '=', $bancoId);
        
        $valor_trfdest = $valor_trfdest->sum('valor_lancamento');

        //echo ($valor_adr->valor_lancamento ."-". $valor_rct->valor_lancamento ."-". $valor_trfdest ) ."-". ($valor_adp->valor_lancamento ."-". $valor_pgt->valor_lancamento ."-". $valor_trforig);
        return ($valor_adr->valor_lancamento + $valor_rct->valor_lancamento + $valor_trfdest ) - ($valor_adp->valor_lancamento + $valor_pgt->valor_lancamento + $valor_trforig);
        // 
    }

    public function getSaldoTotalAtual($banco_id = null) {
        
        $saldo = DB::table('bancos');

        if($banco_id)
            $saldo->where('id', '=', $banco_id);

        return $saldo->sum('saldo_atual');
    }

    public function getLimiteTotalAtual($banco_id = null) {
        $saldo = DB::table('bancos');
        
        if($banco_id)
            $saldo->where('id', '=', $banco_id);

        return $saldo->sum('limite');
    }

    public function getLancamentosExtrato($where = null, $orderBy = null) {
        
        if($orderBy != null)
            $query = \App\Model\Financeiro\LancamentoBancario::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Financeiro\LancamentoBancario::orderBy('data_liquidacao', 'ASC');
        $query->selectRaw('
            lancamentos_bancarios.id,
            lancamentos_bancarios.historico,
            lancamentos_bancarios.data_liquidacao,
            lancamentos_bancarios.tipo_movimento,
            lancamentos_bancarios.valor_lancamento, lancamentos_bancarios.numero_titulo, lancamentos_bancarios.numero_parcela, "L" as tipo
        ')->whereNull('baixa_id')->whereNull('deleted_at');
        $query = getWhere($query, $where);
        //echo $query->toSql();

        if($orderBy != null)
            $query4 = \App\Model\Financeiro\LancamentoBancario::orderBy($orderBy[0], $orderBy[1]);
        else
            $query4 = \App\Model\Financeiro\LancamentoBancario::orderBy('lancamentos_bancarios.data_liquidacao', 'ASC');

        $query4->leftJoin('agendamentos', 'lancamentos_bancarios.agendamento_id', '=', 'agendamentos.id');
        $query4->leftJoin('borderos', 'agendamentos.bordero_id', '=', 'borderos.id');
        $query4->selectRaw('
            borderos.id,
            lancamentos_bancarios.historico,
            lancamentos_bancarios.data_liquidacao,
            lancamentos_bancarios.tipo_movimento,
            SUM(valor_lancamento), borderos.id as numero_titulo, "B" as numero_parcela, "B" as tipo
        ')->whereNotNull('baixa_id')->whereNull('lancamentos_bancarios.deleted_at')->groupBy('baixa_id');
        $query4 = getWhere($query4, $where, "lancamentos_bancarios.");
        $query->union($query4);
        //echo $query4->toSql();
        //
        //if($orderBy[0] = 'data_liquidacao');
          //      $orderBy[0] = 'data_lancamento';

        $where['data_lancamento'] = $where['data_liquidacao'];
        unset($where['data_liquidacao']);
        
        if($orderBy != null) {
            $query2 = \App\Model\Financeiro\TransferenciaBancaria::orderBy($orderBy[0], $orderBy[1]);
        }
        else
            $query2 = \App\Model\Financeiro\TransferenciaBancaria::orderBy('data_lancamento', 'ASC');
        
        $query2->selectRaw('
            id,
            historico,
            data_lancamento as data_liquidacao,
            IF(tipo_movimento="TRF","PGT","") as tipo_movimento,
            valor_lancamento, numero_titulo, numero_parcela, "T" as tipo
        ')->whereNull('deleted_at');
        $where['banco_origem_id'] = $where['banco_id'];
        unset($where['banco_id']);
        $query2 = getWhere($query2, $where);

        $query->union($query2);

        if($orderBy != null) {
            $query3 = \App\Model\Financeiro\TransferenciaBancaria::orderBy($orderBy[0], $orderBy[1]);
        }
        else
            $query3 = \App\Model\Financeiro\TransferenciaBancaria::orderBy('data_lancamento', 'ASC');
        
        $query3->selectRaw('
            id,
            historico,
            data_lancamento as data_liquidacao,
            IF(tipo_movimento="TRF","RCT","") as tipo_movimento,
            valor_lancamento, numero_titulo, numero_parcela, "T" as tipo
        ')->whereNull('deleted_at');
        $where['banco_destino_id'] = $where['banco_origem_id'];
        unset($where['banco_origem_id']);
        $query3 = getWhere($query3, $where);

        $query->union($query3)->orderBy($orderBy[0], $orderBy[1]);
        //echo $query->toSql();
        return $query->get();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $bancos = $this->banco->get()->pluck('descricao', 'id');
        if($addEmpty)
            $bancos->prepend('', '');

        return $bancos;
    }


	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->banco->find($id);
    }

    /**
     * @param $attributes
     *
     * @return bool|mixed
     *
     * @throws \Fully\Exceptions\Validation\ValidationException
     */
    public function create($attributes)
    {
        if ($this->isValid($attributes)) {

            $attributes['limite'] = decimalFormat($attributes['limite']);
            $attributes['saldo_atual'] = decimalFormat($attributes['saldo_atual']);
            
            $this->banco->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Banco', $this->getErrors());
    }

    /**
     * @param $attributes
     *
     * @return bool|mixed
     *
     * @throws \Fully\Exceptions\Validation\ValidationException
     */
    public function update($id, $attributes)
    {

         $this->banco = $this->find($id);

        if ($this->isValid($attributes)) {

            $attributes['limite'] = decimalFormat($attributes['limite']);
            $attributes['saldo_atual'] = decimalFormat($attributes['saldo_atual']);
            
            $this->banco->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da Banco', $this->getErrors());
    }
    /**
     * Get paginated pages.
     *
     * @param int  $page  Number of pages per page
     * @param int  $limit Results per page
     * @param bool $all   Show published or all
     *
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function paginate($page = 1, $limit = 10, $orderBy = null, $where = null)
    {
  
        $query = \App\Model\Cadastro\Banco::orderBy($orderBy[0], $orderBy[1]);
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {

        $this->banco = $this->find($id);

        if($this->banco != null) {
            
            $this->banco->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Conta Bancária', $this->getErrors());

    }
}