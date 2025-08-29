<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\GrupoProduto;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class GrupoProdutoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $grupoProduto;

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
    public function __construct(GrupoProduto $grupoProduto)
    {
        $this->grupoProduto = $grupoProduto;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->grupoProduto->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $objs = $this->grupoProduto->get()->pluck('descricao', 'id');
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
        return $this->grupoProduto->find($id);
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
            
            $this->grupoProduto->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da Grupo de Produto', $this->getErrors());
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

         $this->grupoProduto = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->grupoProduto->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da Grupo de Produto', $this->getErrors());
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
  
        $query = \App\Model\Cadastro\GrupoProduto::orderBy('created_at', 'DESC');
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {

        $this->grupoProduto = $this->find($id);

        if($this->grupoProduto != null) {
            
            $this->grupoProduto->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Grupo de Produto', $this->getErrors());

    }
}