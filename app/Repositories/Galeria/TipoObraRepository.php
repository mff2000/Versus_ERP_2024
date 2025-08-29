<?php
namespace App\Repositories\Galeria;

use App\Model\Galeria\TipoObra;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class TipoObraRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $tipoObra;

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
    public function __construct(TipoObra $tipoObra)
    {
        $this->tipoObra = $tipoObra;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->tipoObra->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $tipos = $this->tipoObra->get()->pluck('nome', 'id');
        if($addEmpty)
            $tipos->prepend('', '');

        return $tipos;
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->tipoObra->find($id);
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

            $this->tipoObra->fill($attributes)->save();

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

         $this->tipoObra = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->tipoObra->fill($attributes)->save();

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
  
        $query = TipoObra::orderBy($orderBy[0], $orderBy[1]);
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->tipoObra = $this->find($id);

        if($this->tipoObra != null) {
            
            $this->tipoObra->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Tipo de Obra', $this->getErrors());

    }
}