<?php
namespace App\Repositories\Financeiro;

use App\Model\Financeiro\LancamentoGerencial;

use Input;
use DateTime;
use DateInterval;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class LancamentoGerencialRepository extends RepositoryAbstract implements RepositoryInterface 
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
        'centro_resultado_credito_id' => 'required',
        'centro_resultado_debito_id' => 'required',
        'plano_conta_credito_id' => 'required',
        'plano_conta_debito_id' => 'required',
        'projeto_credito_id' => 'required',
        'projeto_debito_id' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(LancamentoGerencial $lancamento)
    {
        $this->lancamento = $lancamento;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->lancamento->all();
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
            
            $return = $this->lancamento->fill($attributes)->save();
            return $return;
            
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
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
            $this->lancamento->fill($attributes)->save();

            return true;
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
  
        $query = \App\Model\Financeiro\LancamentoGerencial::orderBy($orderBy[0], $orderBy[1]);     
        $query->join('favorecidos', function ($join) {
            $join->on('favorecidos.id', '=', 'favorecido_id');
        })->select('lancamentos_gerenciais.*', 'favorecidos.nome_empresarial');
        
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);
    }

    public function delete($id) {
        
        $this->lancamento = $this->find($id);

        if($this->lancamento != null) {
            
            $this->lancamento->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Lançamento Não Financeiro', $this->getErrors());

    }
}