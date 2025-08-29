<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\Vendedor;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class VendedorRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $vendedor;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'categoria' => 'required',
        'nome_empresarial' => 'required',
        'tipo_pessoa' => 'required',
        'percentual_comissao' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Vendedor $vendedor)
    {
        $this->vendedor = $vendedor;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->vendedor->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $objs = $this->vendedor->get()->pluck('nome_empresarial', 'id');
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
        return $this->vendedor->find($id);
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
            
            $this->vendedor->fill($this->ajustaDados($attributes))->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Vendedor', $this->getErrors());
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

         $this->vendedor = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->vendedor->fill($this->ajustaDados($attributes))->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Vendedor', $this->getErrors());
    }

    private function ajustaDados($attributes) {

        $attributes['percentual_comissao'] = decimalFormat($attributes['percentual_comissao']);

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
    public function paginate($page = 1, $limit = 10, $all = false, $where = null)
    {
  
        $query = \App\Model\Cadastro\Vendedor::orderBy('created_at', 'DESC');
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {

        $this->vendedor = $this->find($id);

        if($this->vendedor != null) {
            
            $this->vendedor->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Vendedor', $this->getErrors());

    }
}