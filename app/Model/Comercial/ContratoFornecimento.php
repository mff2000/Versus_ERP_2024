<?php

namespace App\Model\Comercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContratoFornecimento extends Model
{
    use SoftDeletes;
    protected $table = "contratos_fornecimento_cabec";
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
		'codigo',
		'sequencia',
        'favorecido_id',
		'descricao',
		'etapa',
        'condicao_id',
		'tipo_transacao_id',
        'vendedor1_id',
        'vendedor2_id',
        'vendedor3_id',
		'pec_cliente',
        'ativo',
        'data_vigencia_inicio',
        'data_vigencia_fim',
        'valor',
        'log_agendamento',
        'observacoes',
        'responsavel',
        'obra',
        'construtora_id',
        'serralheiro_id',
        'instalador_id'
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function favorecido()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'favorecido_id');
    }

    public function tipo_transacao()
    {
        return $this->belongsTo('App\Model\Cadastro\TipoTransacao', 'tipo_transacao_id');
    }

    public function pedidos()
    {
        return $this->hasMany('App\Model\Comercial\PedidoContrato', 'contrato_id');
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

    public function condicao_pagamento()
    {
        return $this->belongsTo('App\Model\Cadastro\CondicaoPagamento', 'condicao_id');
    }

    public function itens()
    {
        return $this->hasMany('App\Model\Comercial\ContratoFornecimentoItem', 'contrato_id');
    }

}
