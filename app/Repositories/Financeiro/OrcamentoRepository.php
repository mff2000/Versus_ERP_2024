<?php
namespace App\Repositories\Financeiro;

use Input;
use DateTime;
use DateInterval;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

use App\Model\Financeiro\Orcamento;

class OrcamentoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $orcamento;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'historico' => 'required',
        'tipo_movimento' => 'required',
        'valor_lancamento' => 'required',
        'data_vencimento' => 'required',
        'data_competencia' => 'required',
        'centro_resultado_id' => 'required',
        'plano_conta_id' => 'required',
        'projeto_id' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Orcamento $orcamento)
    {
        $this->orcamento = $orcamento;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->orcamento->all();
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->orcamento->find($id);
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
            
            $return = $this->orcamento->fill($attributes)->save();
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

        $this->orcamento = $this->find($id);

        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);
            $this->orcamento->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function ajustaValores($attributes) {

        $attributes['valor_lancamento'] = decimalFormat($attributes['valor_lancamento']);
        $attributes['data_vencimento'] = convertDateEn($attributes['data_vencimento']);
        $attributes['data_competencia'] = convertDateEn($attributes['data_competencia']);
        
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
  
        $query = \App\Model\Financeiro\Orcamento::orderBy($orderBy[0], $orderBy[1]);     

        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);
    }

    public function delete($id) {
        
        $this->orcamento = $this->find($id);

        if($this->orcamento != null) {
            
            $this->orcamento->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Orçamento', $this->getErrors());

    }
}