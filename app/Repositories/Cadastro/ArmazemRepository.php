<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\Armazem;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ArmazemRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $armazem;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'codigo' => 'required',
        'descricao' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Armazem $armazem)
    {
        $this->armazem = $armazem;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->armazem->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $armazens = $this->armazem->get()->pluck('descricao', 'id');
        if($addEmpty)
            $armazens->prepend('', '');

        return $armazens;
    }


	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->armazem->find($id);
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
            
            $this->armazem->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Armazém', $this->getErrors());
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

         $this->armazem = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->armazem->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Armazémo', $this->getErrors());
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
    public function paginate($page = 1, $limit = 10, $all = false, $where = null)
    {
  
        $query = \App\Model\Cadastro\Armazem::orderBy('created_at', 'DESC');
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {

        $this->armazem = $this->find($id);

        if($this->armazem != null) {
            
            $this->armazem->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Armazém', $this->getErrors());

    }
}