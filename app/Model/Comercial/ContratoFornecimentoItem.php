<?php

namespace App\Model\Comercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContratoFornecimentoItem extends Model
{
    use SoftDeletes;
    protected $table = "contratos_fornecimento_itens";
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
		'sequencia',
		'item',
        'produto_id',
		'quantidade',
		'preco_unitario',
        'total',
		'pec_cliente',
		'saldo_quantidade',
		'saldo_valor',
		'observacoes',
		'ativo',
        'contrato_id'
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function contrato()
    {
        return $this->belongsTo('App\Model\Comercial\ContratoFornecimento', 'contrato_id');
    }

    public function produto()
    {
        return $this->belongsTo('App\Model\Cadastro\Produto', 'produto_id');
    }

}
