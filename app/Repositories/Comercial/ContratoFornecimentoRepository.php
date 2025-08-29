<?php
namespace App\Repositories\Comercial;

use App\Model\Comercial\ContratoFornecimento;
use App\Model\Comercial\ContratoFornecimentoItem;

use Input;
use DateTime;
use DateInterval;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class ContratoFornecimentoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $contrato;
    protected $item;
    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'favorecido_id' => 'required',
        'descricao' => 'required',
        'tipo_transacao_id' => 'required',
        'vendedor1_id' => 'required',
        'data_vigencia_inicio' => 'required',
        'data_vigencia_fim' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(ContratoFornecimento $contrato, ContratoFornecimentoItem $item)
    {
        $this->contrato = $contrato;
        $this->item = $item;
    }

    /**
     * @return mixed
     */
    public function all($where = null, $orderBy = null)
    {
        
        if($orderBy != null)
            $query = \App\Model\Comercial\ContratoFornecimento::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Comercial\ContratoFornecimento::orderBy('id', 'DESC');
       
        $query = getWhere($query, $where);
        //echo $query->toSql();
        return $query->get();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $objs =  $this->contrato->get()->lists('descricao', 'id');
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
        return $this->contrato->find($id);
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

            $attributes['pec_cliente'] = env('PEC_CLIENTE', 0);
            $attributes['etapa'] = '001'; // Etapa Inicio de Contrato

            $attributes = $this->ajustaValores($attributes);
            $this->contrato->fill($attributes)->save();
            $this->saveItens($attributes, $this->contrato);
            return $this->contrato;
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

        $this->contrato = $this->find($id);

        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);
            $this->contrato->fill($attributes)->save();
            $this->saveItens($attributes, $this->contrato);
            return $this->contrato;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function ajustaValores($attributes) {

        $attributes['data_vigencia_fim'] = convertDateEn($attributes['data_vigencia_fim']);
        $attributes['data_vigencia_inicio'] = convertDateEn($attributes['data_vigencia_inicio']);
        $attributes['valor'] = decimalFormat($attributes['valor']);

        if(empty($attributes['vendedor2_id'])) {
            $attributes['vendedor2_id'] = NULL;
        }
        if(empty($attributes['vendedor3_id'])) {
            $attributes['vendedor3_id'] = NULL;
        }
        return $attributes;
    }

    private function saveItens($attributes, $contrato) {

        $contrato->itens()->delete();
        
        if(!empty($attributes['tblAppendGrid_rowOrder'])) {
            $indexs = explode(",", $attributes['tblAppendGrid_rowOrder']);
            
            for ($row=0; $row<count($indexs); $row++) {
                
                $idItem = $attributes['tblAppendGrid_RecordId_'.$indexs[$row]];
                $item = new ContratoFornecimentoItem();

                $item->item = $indexs[$row];
                $item->contrato_id = $contrato->id;
                $item->produto_id = $attributes['tblAppendGrid_produto_id_'.$indexs[$row]];
                $item->quantidade = $attributes['tblAppendGrid_quantidade_'.$indexs[$row]];
                $item->pec_cliente = $attributes['tblAppendGrid_pec_cliente_'.$indexs[$row]];
                $item->preco_unitario = decimalFormat($attributes['tblAppendGrid_preco_unitario_'.$indexs[$row]]);
                $item->total = decimalFormat($item->preco_unitario * $item->quantidade);
                $item->observacoes = $attributes['tblAppendGrid_AdtComment_'.$indexs[$row]];

                $item->save();
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
    public function paginate($page = 1, $limit = 50, $all = false, $where = null)
    {
  
        $query = \App\Model\Comercial\ContratoFornecimento::orderBy('id', 'DESC');
        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);
    }

    public function delete($id) {
        
        $this->contrato = $this->find($id);

        if($this->contrato != null) {
            
            foreach ($this->contrato->itens as $item) {
                $item->delete();
            }
            $this->contrato->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Contrato', $this->getErrors());

    }

}