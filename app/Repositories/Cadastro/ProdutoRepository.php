<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\Produto;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ProdutoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $produto;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'ncm' => 'required',
        'descricao' => 'required',
        'grupo_id' => 'required',
        'unidade_id' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Produto $produto)
    {
        $this->produto = $produto;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->produto->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $objs = $this->produto->get()->pluck('descricao', 'id');
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
        return $this->produto->find($id);
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

            $this->produto->fill($this->ajustaDados($attributes))->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Produto', $this->getErrors());
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

         $this->produto = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->produto->fill($this->ajustaDados($attributes))->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Produto', $this->getErrors());
    }

    private function ajustaDados($attributes) {

        if(empty($attributes['armazem_id']))
                $attributes['armazem_id'] = NULL;

        $attributes['altura'] = decimalFormat($attributes['altura']);
        $attributes['largura'] = decimalFormat($attributes['largura']);
        $attributes['espessura'] = decimalFormat($attributes['espessura']);
        $attributes['fator_multiplo'] = decimalFormat($attributes['fator_multiplo']);

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
  
        $query = \App\Model\Cadastro\Produto::orderBy('created_at', 'DESC');
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {

        $this->produto = $this->find($id);

        if($this->produto != null) {
            
            $this->produto->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Produto', $this->getErrors());

    }
}