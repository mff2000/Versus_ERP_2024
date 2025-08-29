<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\TipoTransacao;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class TipoTransacaoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $tipoTransacao;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'descricao' => 'required',
        'tipo' => 'required',
        'cfop_id' => 'required',
        'integra_financeiro' => 'required',
        'integra_estoque' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(TipoTransacao $tipoTransacao)
    {
        $this->tipoTransacao = $tipoTransacao;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->tipoTransacao->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $objs = $this->tipoTransacao->get()->pluck('descricao', 'id');
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
        return $this->tipoTransacao->find($id);
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
            
            $this->tipoTransacao->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Tipo de Transação', $this->getErrors());
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

         $this->tipoTransacao = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->tipoTransacao->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Tipo de Transação', $this->getErrors());
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
  
        $query = \App\Model\Cadastro\TipoTransacao::orderBy('created_at', 'DESC');
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {

        $this->tipoTransacao = $this->find($id);

        if($this->tipoTransacao != null) {
            
            $this->tipoTransacao->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Tipo de Transação', $this->getErrors());

    }
}