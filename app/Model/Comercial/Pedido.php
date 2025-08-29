<?php

namespace App\Model\Comercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;
    protected $table = "pedidos_contrato_cabec";
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
		'ativo',
        'favorecido_id',
		'contrato_id',
		'condicao_id',
        'vendedor1_id',
        'vendedor2_id',
        'vendedor3_id',
        'tipo_transacao_id',
		'mensagens_danfe',
		'observacoes',
        'pedido_favorecido',
		'etapa',
        'total_itens',
        'valor',
        'pec_cliente',
        'pec_fabrica',
        'log_credito',
        'log_estoque',
        'log_faturado',
        'log_entregue',
        'data_emissao',
        'data_entrega',
        'data_faturamento',
        'nfe_serie',
        'nfe_numero',
        'romaneio',
        'peso_liquido',
        'peso_bruto',
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function contrato()
    {
        return $this->belongsTo('App\Model\Comercial\ContratoFornecimento', 'contrato_id');
    }

    public function favorecido()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'favorecido_id');
    }

    public function tipo_transacao()
    {
        return $this->belongsTo('App\Model\Cadastro\TipoTransacao', 'tipo_transacao_id');
    }

    public function condicao_pagamento()
    {
        return $this->belongsTo('App\Model\Cadastro\CondicaoPagamento', 'condicao_id');
    }

    public function vendedor1()
    {
        return $this->belongsTo('App\Model\Cadastro\Vendedor', 'vendedor1_id');
    }

    public function vendedor2()
    {
        return $this->belongsTo('App\Model\Cadastro\Vendedor', 'vendedor2_id');
    }
    
    public function vendedor3()
    {
        return $this->belongsTo('App\Model\Cadastro\Vendedor', 'vendedor3_id');
    }

    public function itens()
    {
        return $this->hasMany('App\Model\Comercial\PedidoItem', 'pedido_id');
    }

}
