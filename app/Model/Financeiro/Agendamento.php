<?php

namespace App\Model\Financeiro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Model
{
    use SoftDeletes;
    
    //protected $table = "bancos";
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
		'numero_titulo',
		'numero_parcela',
		'favorecido_id',
		'tipo_movimento',
		'correcao_financeira_id',
		'historico',
		'tags',
		'valor_titulo',
		'valor_saldo',
		'data_digitacao',
		'data_competencia',
		'data_vencimento',
		'codigo_link',
		'item_link',
		'nfe_serie',
		'nfe_numero',
		'pedido',
		'contrato',
		'sequencia',
		'bordero_id'
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function favorecido()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'favorecido_id');
    }

    public function correcao_financeira()
    {
        return $this->belongsTo('App\Model\Cadastro\CorrecaoFinanceira', 'correcao_financeira_id');
    }

    public function rateios()
    {
        return $this->hasMany('App\Model\Financeiro\Rateio', 'agendamento_id');
    }

    public function lancamentos()
    {
        return $this->hasMany('App\Model\Financeiro\LancamentoBancario', 'agendamento_id');
    }

	public function multasJurosDescontos() {
        return $this->hasMany('App\Model\Financeiro\MultaJuroDesconto', 'agendamento_id');   
    }
}
