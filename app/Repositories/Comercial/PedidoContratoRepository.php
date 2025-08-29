<?php
namespace App\Repositories\Comercial;

use App\Model\Comercial\Pedido;
use App\Model\Comercial\PedidoItem;

use Input;
use DateTime;
use DateInterval;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class PedidoContratoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $pedido;
    protected $item;
    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'contrato_id' => 'required',
        'data_entrega' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Pedido $pedido, PedidoItem $item)
    {
        $this->pedido = $pedido;
        $this->item = $item;
    }

    /**
     * @return mixed
     */
    public function all($where = null, $orderBy = null)
    {
        
        if($orderBy != null)
            $query = \App\Model\Comercial\Pedido::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Comercial\Pedido::orderBy('id', 'DESC');
       
        $query = getWhere($query, $where);
        //echo $query->toSql();
        return $query->get();
    }


	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->pedido->find($id);
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
        //print_r($attributes);
        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);
            $attributes['etapa'] = '001';
            $attributes['data_emissao'] = date('Y-m-d');
            
            $this->pedido->fill($attributes)->save();
            $this->saveItens($attributes, $this->pedido);

            return $this->pedido;
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

        $this->pedido = $this->find($id);

        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);

            $this->pedido->fill($attributes)->save();
            $this->saveItens($attributes, $this->pedido);

            return $this->pedido;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function saveItens($attributes, $pedido) {

        $pedido->itens()->delete();

        if(!empty($attributes['tblAppendGrid_rowOrder'])) {
            $indexs = explode(",", $attributes['tblAppendGrid_rowOrder']);
            
            for ($row=0; $row<count($indexs); $row++) {
                
                $idItem = $attributes['tblAppendGrid_RecordId_'.$indexs[$row]];
                $item = new PedidoItem();

                $item->item = $indexs[$row];
                $item->pedido_id = $pedido->id;
                $item->produto_id = $attributes['tblAppendGrid_produto_id_'.$indexs[$row]];
                $item->quantidade_pecas = decimalFormat($attributes['tblAppendGrid_quantidade_pecas_'.$indexs[$row]]);
                $item->altura = $attributes['tblAppendGrid_altura_'.$indexs[$row]];
                $item->largura = $attributes['tblAppendGrid_largura_'.$indexs[$row]];
                $item->area_real = decimalFormat($attributes['tblAppendGrid_area_real_'.$indexs[$row]]);
                $item->preco_unitario = decimalFormat($attributes['tblAppendGrid_preco_unitario_'.$indexs[$row]]);
                $item->total_item = decimalFormat($item->preco_unitario * $item->quantidade_pecas);
                $item->pec_cliente = decimalFormat($attributes['tblAppendGrid_pec_cliente_'.$indexs[$row]]);
                $item->observacoes = $attributes['tblAppendGrid_AdtComment_'.$indexs[$row]];

                $item->save();
            }
        }


    }

    private function ajustaValores($attributes) {

        if(empty($attributes['vendedor2_id']))
            $attributes['vendedor2_id'] = NULL;
        if(empty($attributes['vendedor3_id']))
            $attributes['vendedor3_id'] = NULL;

        $attributes['data_entrega'] = convertDateEn($attributes['data_entrega']);
        $attributes['valor'] = decimalFormat($attributes['valor']);
        
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
    public function paginate($page = 1, $limit = 50, $all = false, $where = null)
    {
  
        $query = \App\Model\Comercial\Pedido::where('contrato_id', '!=', NULL)->orderBy('id', 'DESC');
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);
    }

    public function delete($id) {
        
        $this->pedido = $this->find($id);

        if($this->pedido != null) {
            
            foreach ($this->pedido->itens as $item) {
                $item->delete();
            }
            $this->pedido->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Pedido', $this->getErrors());

    }

}