<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\FormaFinanceira;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class FormaFinanceiraRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $forma;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'codigo' => 'required',
        'descricao' => 'required',
        'liquida' => 'required',
        'ativo' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(FormaFinanceira $forma)
    {
        $this->forma = $forma;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->forma->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $formas = $this->forma->get()->pluck('descricao', 'id');
        if($addEmpty)
            $formas->prepend('', '');

        return $formas;
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->forma->find($id);
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

            $this->forma->fill($attributes)->save();

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

         $this->forma = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->forma->fill($attributes)->save();

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
    public function paginate($page = 1, $limit = 1000, $orderBy = null, $where = null)
    {
  
        $query = \App\Model\Cadastro\FormaFinanceira::orderBy($orderBy[0], $orderBy[1]);
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->forma = $this->find($id);

        if($this->forma != null) {
            
            $this->forma->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Forma Financeira', $this->getErrors());

    }
}