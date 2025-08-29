<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorecido extends Model
{

    protected $table = "favorecidos";
    protected $fillable = array (
    	'id', 'tipo_pessoa','nome_empresarial', 'nome_fantasia', 'cnpj', 'inscricao_estadual', 'data_cadastro',
    	'tel_fixo1', 'tel_fixo2', 'tel_movel1', 'tel_movel2',
    	'email_geral', 'email_nfe', 'email_financ',
    	'contato_geral', 'contato_fiscal', 'contato_financ',
    	'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
    	'entrega_cep', 'entrega_endereco', 'entrega_numero', 'entrega_complemento', 'entrega_bairro', 'entrega_cidade', 'entrega_uf',
    	'cobranca_cep', 'cobranca_endereco', 'cobranca_numero', 'cobranca_complemento', 'cobranca_bairro', 'cobranca_cidade', 'cobranca_uf',
        'risco_credito', 'data_validade', 'limite_credito', 'limite_desconto', 'tabela_preco_id',
        'tipo_galeria'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function agendamentos()
    {
        return $this->hasMany('App\Model\Financeiro\Agendamento', 'favorecido_id');
    }

    public function vendedores()
    {
        return $this->belongsToMany('App\Model\Cadastro\Vendedor', 'favorecidos_has_vendedores')->withPivot('comissao');
    }

    public function condicoes_pagamento()
    {
        return $this->belongsToMany('App\Model\Cadastro\CondicaoPagamento', 'favorecidos_has_condicoes_pagamento');
    }

    public function obs()
    {
        return $this->hasMany('App\Model\Cadastro\FavorecidoObs', 'favorecido_id');
    }

    public function tabela()
    {
        return $this->belongsTo('App\Model\Cadastro\TabelaPreco', 'tabela_preco_id');
    }

    public function descontos()
    {
        return $this->belongsToMany('App\Model\Cadastro\Produto', 'favorecidos_has_desconto')->withPivot('percentual');
    }

}
