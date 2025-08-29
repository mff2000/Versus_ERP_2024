<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\CorrecaoFinanceira;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class CorrecaoFinanceiraRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $correcao;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'descricao' => 'required',
        'plano_conta_id' => 'required',
        'aliquota_juros' => 'required',
        'periodo_juros' => 'required',
        'aliquota_multa' => 'required',
        'periodo_multa' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(CorrecaoFinanceira $correcao)
    {
        $this->correcao = $correcao;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->correcao->all();
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->correcao->find($id);
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

            $this->correcao->fill($attributes)->save();

            return true;
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

         $this->correcao = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->correcao->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
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
  
        $query = \App\Model\Cadastro\CorrecaoFinanceira::orderBy($orderBy[0], $orderBy[1]);
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->correcao = $this->find($id);

        if($this->correcao != null) {
            
            $this->correcao->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Correção Financeira', $this->getErrors());

    }
}