<?php
namespace App\Repositories\Galeria;

use App\Model\Galeria\Consignacao;
use App\Model\Galeria\ItemConsignacao;
use App\Model\Galeria\Obra;

use Input;
use DateTime;
use Auth;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class ConsignacaoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $consignacao;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'cliente_id' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Consignacao $consignacao)
    {
        $this->consignacao = $consignacao;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->consignacao->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $consignacoes = $this->consignacao->get()->pluck('titulo', 'id');
        if($addEmpty)
            $consignacoes->prepend('', '');

        return $consignacoes;
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->consignacao->find($id);
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
            $attributes['usuario_id'] = Auth::user()->id;
            $this->consignacao->fill($attributes)->save();
            $this->saveItens($attributes, $this->consignacao);
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

         $this->consignacao = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->consignacao->fill($attributes)->save();
            $this->saveItens($attributes, $this->consignacao);
            return true;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function saveItens($attributes, $consignacao) {
        
        $indexs = null;

        if(!empty($attributes['obra_id']))
            $indexs = $attributes['obra_id'];

        if(isset($indexs) && count($indexs) > 0) {
            foreach ($indexs as $value) {
                
                $obra = Obra::find($value);
                $item = ItemConsignacao::where("obra_id", "=", $obra->id)->where('consignacao_id', '=', $consignacao->id)->first();
                
                if($item == null) {
                    
                    $item = new ItemConsignacao();
                    
                    $item->obra_id = $obra->id;
                    $item->consignacao_id = $consignacao->id;
                    $obra->estoque--;

                    $item->valor_obra = $obra->valor_venda;
                    $item->save();
                    $obra->save();
                }

                
            }
        }

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
  
        $query = Consignacao::orderBy($orderBy[0], $orderBy[1]);
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->consignacao = $this->find($id);

        if($this->consignacao != null) {
            
            $this->consignacao->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Venda', $this->getErrors());

    }
}