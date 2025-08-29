<?php

namespace App\Model\Comercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoItem extends Model
{
    use SoftDeletes;
    protected $table = "pedidos_contrato_itens";
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
		'ativo',
		'item',
        'pedido_id',
		'produto_id',
		'quantidade_pecas',
        'altura',
		'largura',
		'area_real',
		'pec_cliente',
		'pec_fabrica',
		'area_bruta_cliente',
        'area_bruta_fabrica',
        'quantidade_fabrica',
        'quantidade_cliente',
        'preco_unitario',
        'total_item',
        'armazem_id',
        'observacoes',
        'altura_corte',
        'largura_corte',
        'preco_tabela',
        'romaneio',
        'saldo_liberar'
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function pedido()
    {
        return $this->belongsTo('App\Model\Comercial\Pedido', 'pedido_id');
    }

    public function produto()
    {
        return $this->belongsTo('App\Model\Cadastro\Produto', 'produto_id');
    }

    public function armazem()
    {
        return $this->belongsTo('App\Model\Cadastro\Armazem', 'armazem_id');
    }

}
