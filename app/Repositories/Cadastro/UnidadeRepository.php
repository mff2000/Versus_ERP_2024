<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\Unidade;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class UnidadeRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $unidade;

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
    public function __construct(Unidade $unidade)
    {
        $this->unidade = $unidade;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->unidade->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $objs = $this->unidade->get()->pluck('descricao', 'id');
        if($addEmpty)
            $objs->prepend('', '');

        return $objs;
    }


	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->unidade->find($id);
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
            
            $this->unidade->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da Unidade de Medida', $this->getErrors());
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

         $this->unidade = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->unidade->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da Unidade de Medida', $this->getErrors());
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
  
        $query = \App\Model\Cadastro\Unidade::orderBy('created_at', 'DESC');
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {

        $this->unidade = $this->find($id);

        if($this->unidade != null) {
            
            $this->unidade->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Unidade de Medida', $this->getErrors());

    }
}