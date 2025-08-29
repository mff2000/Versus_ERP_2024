<?php
namespace App\Repositories\Financeiro;

use App\Model\Financeiro\TransferenciaBancaria;

use Input;
use DateTime;
use DateInterval;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class TransferenciaBancariaRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $transferencia;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'historico'         => 'required',
        'banco_origem_id'   => 'required',
        'banco_destino_id'  => 'required',
        'valor_lancamento'  => 'required',
        'data_lancamento'   => 'required',
        'tipo_movimento'    => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(TransferenciaBancaria $transferencia)
    {
        $this->transferencia = $transferencia;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->transferencia->all();
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->transferencia->find($id);
    }

      /**
     * @param $id
     *
     * @return mixed
     */
    public function getNaoLiquidados($where = null)
    {
        $query = $this->transferencia->where('data_liquidacao', NULL);
        $query = getWhere($query, $where);

        return $query->get();
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
            $return = $this->transferencia->fill($attributes)->save();

            return $return;
            
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

        $this->transferencia = $this->find($id);

        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);
            $this->transferencia->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function ajustaValores($attributes) {

        $attributes['valor_lancamento'] = decimalFormat($attributes['valor_lancamento']);
        $attributes['data_lancamento'] = convertDateEn($attributes['data_lancamento']);
        
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
    public function paginate($page = 1, $limit = 10, $orderBy = null, $where = null)
    {
  
        $query = \App\Model\Financeiro\TransferenciaBancaria::orderBy($orderBy[0], $orderBy[1]);     
        $query->join('bancos as banco_origem', function ($join) {
            $join->on('banco_origem.id', '=', 'banco_origem_id');
        })->join('bancos as banco_destino', function ($join) {
            $join->on('banco_destino.id', '=', 'banco_destino_id');
        })->select('transferencias_bancarias.*', 'banco_origem.descricao AS descricao_origem', 'banco_destino.descricao AS descricao_destino');

        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);
    }

    public function delete($id) {
        
        $this->transferencia = $this->find($id);

        if($this->transferencia != null) {
            
            $this->transferencia->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Transferência Bancaria', $this->getErrors());

    }
}