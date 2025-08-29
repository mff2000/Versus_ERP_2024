<?php
namespace App\Repositories\Galeria;

use App\Model\Galeria\Tecnica;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class TecnicaRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $tecnica;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'nome' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Tecnica $tecnica)
    {
        $this->tecnica = $tecnica;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->tecnica->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $tecnicas = $this->tecnica->get()->pluck('nome', 'id');
        if($addEmpty)
            $tecnicas->prepend('', '');

        return $tecnicas;
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->tecnica->find($id);
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

            $this->tecnica->fill($attributes)->save();

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

         $this->tecnica = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->tecnica->fill($attributes)->save();

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
  
        $query = Tecnica::orderBy($orderBy[0], $orderBy[1]);
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->tecnica = $this->find($id);

        if($this->tecnica != null) {
            
            $this->tecnica->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Técnica', $this->getErrors());

    }
}