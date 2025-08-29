<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\TabelaPreco;
use App\Model\Cadastro\Produto;

use Input;
use DateTime;

use App\Repositories\Cadastro\TabelaPrecoRepository;
use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class TabelaPrecoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $tabela;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'descricao' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(TabelaPreco $tabela)
    {
        $this->tabela = $tabela;
    }

    public function get() {
        return $this->tabela;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->tabela->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $objs = $this->tabela->get()->pluck('descricao', 'id');
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
        return $this->tabela->find($id);
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
            
            $this->tabela->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da Tabela de Preço', $this->getErrors());
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

         $this->tabela = $this->find($id);

        if ($this->isValid($attributes)) {

            $this->tabela->fill($attributes)->save();

            if(isset($attributes['produto_id'])) {
                
                $produtoRepository =  new ProdutoRepository(new Produto());
                
                foreach ($attributes['produto_id'] as $key => $value) {
                    $produto = $produtoRepository->find($key);
                    $this->tabela->produtos()->updateExistingPivot($produto->id, ['preco' => decimalFormat($value)]);
                }
            }
            return true;
        }

        throw new ValidationException('Erros ao validar dados da Tabela de Preço', $this->getErrors());
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
  
        $query = \App\Model\Cadastro\TabelaPreco::orderBy('descricao', 'ASC');
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {

        $this->tabela = $this->find($id);

        if($this->tabela != null) {
            
            $this->tabela->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Tabela de Preço', $this->getErrors());

    }
}