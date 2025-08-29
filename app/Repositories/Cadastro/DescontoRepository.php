<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\Desconto;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class DescontoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $desconto;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'descricao' => 'required',
        'plano_conta_id' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Desconto $desconto)
    {
        $this->desconto = $desconto;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->desconto->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $descontos = $this->desconto->get()->pluck('descricao', 'id');
        if($addEmpty)
            $descontos->prepend('', '');

        return $descontos;
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->desconto->find($id);
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

            $this->desconto->fill($attributes)->save();

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

         $this->desconto = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->desconto->fill($attributes)->save();

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
  
        $query = \App\Model\Cadastro\Desconto::orderBy($orderBy[0], $orderBy[1]);
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->desconto = $this->find($id);

        if($this->desconto != null) {
            
            $this->desconto->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Desconto', $this->getErrors());

    }
}