<?php
namespace App\Repositories\Financeiro;

use App\Model\Financeiro\LancamentoBancario;
use App\Model\Financeiro\Rateio;
use App\Model\Financeiro\MultaJuroDesconto;

use Input;
use DateTime;
use DateInterval;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class LancamentoBancarioRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $lancamento;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'historico' => 'required',
        'favorecido_id' => 'required',
        'valor_lancamento' => 'required',
        'data_lancamento' => 'required',
        'tipo_movimento' => 'required',
        'forma_financeira_id' => 'required',
        'banco_id' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(LancamentoBancario $lancamento)
    {
        $this->lancamento = $lancamento;
    }

    /**
     * @return mixed
     */
    public function all($where = null, $orderBy = null)
    {
        
        if($orderBy != null)
            $query = \App\Model\Financeiro\LancamentoBancario::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Financeiro\LancamentoBancario::orderBy('data_lancamento', 'DESC');

        //$query->leftJoin('agendamento_rateio', 'agendamentos.id', '=', 'agendamento_rateio.agendamento_id');

        $query = getWhere($query, $where);
                //echo $query->toSql();
        return $query->get();
    }

    /**
     * @return mixed
     */
    public function getToDreDetailsDeJurosMulta($where = null, $orderBy = null)
    {
        
        if($orderBy != null)
            $query = \App\Model\Financeiro\LancamentoBancario::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Financeiro\LancamentoBancario::orderBy('data_lancamento', 'ASC');

        
        $query->selectRaw('
            agendamentos.id, agendamentos.data_competencia, agendamentos.data_vencimento, agendamentos.historico, agendamentos.tipo_movimento,
            lancamentos_bancarios.data_lancamento, lancamentos_bancarios.valor_lancamento as valor_titulo,
            agendamento_rateio.porcentagem,
            projetos.descricao as projeto, centros_resultado.descricao as centro');

        $query->leftJoin('agendamentos', 'agendamentos.id', '=', 'lancamentos_bancarios.agendamento_id');
        $query->leftJoin('agendamento_rateio', 'agendamentos.id', '=', 'agendamento_rateio.agendamento_id');
        $query->leftJoin('projetos', 'projetos.id', '=', 'agendamento_rateio.projeto_id');
        $query->leftJoin('centros_resultado', 'centros_resultado.id', '=', 'agendamento_rateio.centro_resultado_id');

        $query->where("agendamentos.deleted_at", NULL);
        $query->where('lancamentos_bancarios.valor_multa', '>', '0'); 
        $query->where('lancamentos_bancarios.valor_juros', '>', '0');

        $query = getWhere($query, $where);
        $query->groupBy('agendamentos.id');
                //echo $query->toSql();
        return $query->get();
    }

    /**
     * @return mixed
     */
    public function getToDreDetailsPorDesconto($where = null, $orderBy = null)
    {
        
        if($orderBy != null)
            $query = \App\Model\Financeiro\LancamentoBancario::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Financeiro\LancamentoBancario::orderBy('data_lancamento', 'ASC');

        
        $query->selectRaw('
            agendamentos.id, agendamentos.data_competencia, agendamentos.data_vencimento, agendamentos.historico, agendamentos.tipo_movimento,
            lancamentos_bancarios.data_lancamento, SUM(lancamentos_bancarios.valor_lancamento) as valor_titulo,
            agendamento_rateio.porcentagem,
            projetos.descricao as projeto, centros_resultado.descricao as centro');

        $query->leftJoin('agendamentos', 'agendamentos.id', '=', 'lancamentos_bancarios.agendamento_id');
        $query->leftJoin('agendamento_rateio', 'agendamentos.id', '=', 'agendamento_rateio.agendamento_id');
        $query->leftJoin('projetos', 'projetos.id', '=', 'agendamento_rateio.projeto_id');
        $query->leftJoin('centros_resultado', 'centros_resultado.id', '=', 'agendamento_rateio.centro_resultado_id');

        $query->where("agendamentos.deleted_at", NULL);
        $query->where('lancamentos_bancarios.valor_desconto', '>', '0');

        $query = getWhere($query, $where);
        $query->groupBy('agendamentos.id');
                //echo $query->toSql();
        return $query->get();
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->lancamento->find($id);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findByBaixa($id)
    {
        return $this->lancamento->where('baixa_id', $id)->get();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getNaoLiquidadosAgendamentos($where = null)
    {
        $query = $this->lancamento->where('data_liquidacao', NULL)->where('bordero_id', '=', NULL);
        $query = getWhere($query, $where);

        return $query->get();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getNaoLiquidadosBordero($where = null)
    {
        $query = $this->lancamento->where('data_liquidacao', NULL)->where('bordero_id', '!=', NULL)
            ->selectRaw('*, 
                sum(valor_lancamento) as valor_lancamento,
                (SELECT COALESCE(sum(valor_lancamento),0) as valor_lancamento FROM juros_multas_descontos where tipo IN("M") and lancamento_id = lancamentos_bancarios.id) as valor_multa,
                (SELECT COALESCE(sum(valor_lancamento),0) as valor_lancamento FROM juros_multas_descontos where tipo IN("J") and lancamento_id = lancamentos_bancarios.id) as  valor_juros, 
                (SELECT COALESCE(sum(valor_lancamento),0) as valor_lancamento FROM juros_multas_descontos where tipo IN("D") and lancamento_id = lancamentos_bancarios.id) as valor_desconto
            ')
            ->groupBy('baixa_id');

        $query = getWhere($query, $where);
        //echo $query->toSql();
        return $query->get();
    }

    public function findIdenticador($iden) {
        $query = $this->lancamento->where('baixa_id', $iden);
        return $query->get();
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
        //print_r($attributes);
        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);

            if( 
                (isset($attributes['valor_desconto']) && $attributes['valor_desconto'] > 0) && 
                (!isset($attributes['desconto_id']) || !is_numeric($attributes['desconto_id']))
            ) {
                
                return false;
                
            } else {
                $return = $this->lancamento->fill($attributes)->save();
            
                if($return) {
                    $this->saveRateio($this->lancamento, $attributes);
                    $this->saveMultaJuroDesconto($attributes, $this->lancamento);
                }

                return $this->lancamento;
            }
            
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function saveRateio($lancamento, $attributes) {
        if($lancamento && $attributes['tipo_baixa'] == 'NOR') {
            $rateio = new Rateio();    
            $rateio = $rateio->find($lancamento->agendamento->rateios[0]->id);

            $rateio->lancamento_id = $lancamento->id;
            
            $rateio->save();
        }
    }

    private function saveMultaJuroDesconto($attributes, $lancamento) {

        if(isset($attributes['valor_multa']) && $attributes['valor_multa'] > 0) {
            MultaJuroDesconto::create([
                'valor_lancamento' => $attributes['valor_multa'],
                'tipo' => 'M',
                'data_lancamento' => $attributes['data_lancamento'],
                'plano_conta_id' => $lancamento->agendamento->correcao_financeira->plano_conta->id,
                'agendamento_id' => $lancamento->agendamento->id,
                'lancamento_id' => $lancamento->id
            ]);
        }

        if(isset($attributes['valor_juros']) && $attributes['valor_juros'] > 0) {
            MultaJuroDesconto::create([
                'valor_lancamento' => $attributes['valor_juros'],
                'tipo' => 'J',
                'data_lancamento' => $attributes['data_lancamento'],
                'plano_conta_id' => $lancamento->agendamento->correcao_financeira->plano_conta->id,
                'agendamento_id' => $lancamento->agendamento->id,
                'lancamento_id' => $lancamento->id
            ]);
        }

        if(isset($attributes['valor_desconto']) && $attributes['valor_desconto'] > 0) {
            MultaJuroDesconto::create([
                'valor_lancamento' => $attributes['valor_desconto'],
                'tipo' => 'D',
                'data_lancamento' => $attributes['data_lancamento'],
                'plano_conta_id' => $lancamento->desconto->plano_conta->id,
                'agendamento_id' => $lancamento->agendamento->id,
                'lancamento_id' => $lancamento->id
            ]);
        }

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

        $this->lancamento = $this->find($id);

        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);
            $return = $this->lancamento->fill($attributes)->save();

            if($return)
                $this->saveRateio($this->lancamento, $attributes);

            return $this->lancamento;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function ajustaValores($attributes) {

        $attributes['valor_lancamento'] = decimalFormat($attributes['valor_lancamento']);
        $attributes['data_lancamento'] = convertDateEn($attributes['data_lancamento']);
        
        return $attributes;
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
        
        $query = \App\Model\Financeiro\LancamentoBancario::orderBy($orderBy[0], $orderBy[1]);
        $query->leftjoin('bancos', function ($join) {
            $join->on('bancos.id', '=', 'banco_id');
        })->select('lancamentos_bancarios.*', 'bancos.descricao');

        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->lancamento = $this->find($id);

        if($this->lancamento != null) {
            
             if($this->lancamento->valorJuros)
                $this->lancamento->valorJuros->delete();
            if($this->lancamento->valorMulta)
                $this->lancamento->valorMulta->delete();
            if($this->lancamento->valorDesconto)
                $this->lancamento->valorDesconto->delete();

            $this->lancamento->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Lançamento Bancario', $this->getErrors());

    }
}