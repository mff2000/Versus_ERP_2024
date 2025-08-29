<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\CondicaoPagamento;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class CondicaoPagamentoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $condicao;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'tipo' => 'required',
        'descricao' => 'required',
        'quantidade_parcelas' => 'required',
        'dias_intervalo' => 'required',
        'dias_carencia' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(CondicaoPagamento $condicao)
    {
        $this->condicao = $condicao;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->condicao->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $condicoes = $this->condicao->get()->pluck('descricao', 'id');
        if($addEmpty)
            $condicoes->prepend('', '');

        return $condicoes;
    }


	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->condicao->find($id);
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
            
            $this->condicao->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da Condição de Pagamento', $this->getErrors());
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

         $this->condicao = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->condicao->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da Condição de Pagamento', $this->getErrors());
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
  
        $query = \App\Model\Cadastro\CondicaoPagamento::orderBy('created_at', 'DESC');
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {

        $this->condicao = $this->find($id);

        if($this->condicao != null) {
            
            $this->condicao->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Condição de Pagamento', $this->getErrors());

    }
}