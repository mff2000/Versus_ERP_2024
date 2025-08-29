<?php

namespace App\Model\Financeiro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LancamentoBancario extends Model
{
    //

    use SoftDeletes;
    
    protected $table = "lancamentos_bancarios";
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
		'numero_titulo',
		'numero_parcela',
		'favorecido_id',
		'tipo_movimento',
		'tipo_baixa',
		'sequencia_baixa',
		'numero_cheque',
		'historico',
		'tags',
		'banco_id',
		'valor_lancamento',
		'data_lancamento',
		'data_liquidacao',
		'agendamento_id',
        'agendamento_relacionado_id',
        'lancamento_relacionado_id',
        'forma_financeira_id',
        'desconto_id',
        'status',
        'bordero_id',
        'baixa_id'
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function favorecido()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'favorecido_id');
    }

    public function conta()
    {
        return $this->belongsTo('App\Model\Cadastro\Banco', 'banco_id');
    }

    public function agendamento()
    {
        return $this->belongsTo('App\Model\Financeiro\Agendamento', 'agendamento_id');
    }

    public function forma_financeira()
    {
        return $this->belongsTo('App\Model\Cadastro\FormaFinanceira', 'forma_financeira_id');
    }

    public function desconto()
    {
        return $this->belongsTo('App\Model\Cadastro\Desconto', 'desconto_id');
    }

    public function bordero()
    {
        return $this->belongsTo('App\Model\Financeiro\Bordero', 'bordero_id');
    }

    public function rateio()
    {
        return $this->hasOne('App\Model\Financeiro\Rateio', 'lancamento_id');
    }

    public function valorMulta() {
        return $this->hasOne('App\Model\Financeiro\MultaJuroDesconto', 'lancamento_id')->where('tipo', '=', 'M');     
    }

    public function valorJuros() {
        return $this->hasOne('App\Model\Financeiro\MultaJuroDesconto', 'lancamento_id')->where('tipo', '=', 'J'); 
    }

    public function valorDesconto() {
        return $this->hasOne('App\Model\Financeiro\MultaJuroDesconto', 'lancamento_id')->where('tipo', '=', 'D');   
    }

}
