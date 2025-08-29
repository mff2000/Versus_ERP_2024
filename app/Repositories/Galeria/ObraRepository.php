<?php
namespace App\Repositories\Galeria;

use App\Model\Galeria\Obra;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class ObraRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $obra;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'titulo' => 'required',
        'dimensao' => 'required',
        'tipo_obra_id' => 'required',
        'artista_id' => 'required',
        'tecnica_id' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Obra $obra)
    {
        $this->obra = $obra;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->obra->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $obras = $this->obra->where('estoque', '>', 0)->get()->pluck('titulo', 'id');
        if($addEmpty)
            $obras->prepend('', '');

        return $obras;
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->obra->find($id);
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
            $attributes['data_inclusao'] = date('Y-m-d');
            
            $this->obra->fill($attributes)->save();

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

         $this->obra = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->obra->fill($attributes)->save();

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
  
        $query = Obra::orderBy($orderBy[0], $orderBy[1]);
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->obra = $this->find($id);

        if($this->obra != null) {
            
            $this->obra->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Obra', $this->getErrors());

    }
}