<?php

namespace App\Model\Galeria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venda extends Model
{
    //
    protected $table = "gal_vendas";
    protected $fillable = array (
    	'id', 'situacao', 'data_inclusao', 'valor', 'desconto', 'perc_comissao', 'observacao',
        'cliente_id', 'vendedor_id', 'condicao_pagamento_id', 'usuario_id', 'proposta_id'	
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function cliente()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'cliente_id');
    }

    public function proposta()
    {
        return $this->belongsTo('App\Model\Galeria\Consignacao', 'proposta_id');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\Model\Cadastro\Vendedor', 'vendedor_id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Model\User', 'usuario_id');
    }

    public function condicaoPagamento()
    {
        return $this->belongsTo('App\Model\Cadastro\CondicaoPagamento', 'condicao_pagamento_id');
    }

    public function itens()
    {
        return $this->hasMany('App\Model\Galeria\ItemVenda', 'venda_id');
    }

}
