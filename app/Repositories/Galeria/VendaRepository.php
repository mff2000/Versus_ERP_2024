<?php
namespace App\Repositories\Galeria;

use App\Model\Galeria\Venda;
use App\Model\Galeria\ItemVenda;
use App\Model\Galeria\Obra;

use Input;
use DateTime;
use Auth;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class VendaRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $venda;

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
    public function __construct(Venda $venda)
    {
        $this->venda = $venda;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->venda->all();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $vendas = $this->venda->get()->pluck('titulo', 'id');
        if($addEmpty)
            $vendas->prepend('', '');

        return $vendas;
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->venda->find($id);
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
            $this->venda->fill($attributes)->save();
            $this->saveItens($attributes, $this->venda);
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

         $this->venda = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->venda->fill($attributes)->save();
            $this->saveItens($attributes, $this->venda);
            return true;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function saveItens($attributes, $venda) {
        
        $indexs = null;

        if(!empty($attributes['obra_id']))
            $indexs = $attributes['obra_id'];

        if(isset($indexs) && count($indexs) > 0) {
            foreach ($indexs as $value) {
                
                $obra = Obra::find($value);
                $item = ItemVenda::where("obra_id", "=", $obra->id)->where('venda_id', '=', $venda->id)->first();
                
                if($item == null) {
                    
                    $item = new ItemVenda();
                    
                    $item->obra_id = $obra->id;
                    $item->venda_id = $venda->id;
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
  
        $query = Venda::orderBy($orderBy[0], $orderBy[1]);
  
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->venda = $this->find($id);

        if($this->venda != null) {
            
            $this->venda->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Venda', $this->getErrors());

    }
}