<?php

namespace App\Model\Financeiro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultaJuroDesconto extends Model
{
    //
    protected $table = "juros_multas_descontos";
    //protected $table = "bancos";
    protected $fillable = array (
    	'id',
		'valor_lancamento',
		'tipo',
		'data_lancamento',
		'plano_conta_id',
		'agendamento_id',
        'lancamento_id'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function agendamento()
    {
        return $this->belongsTo('App\Model\Financeiro\Agendamento', 'agendamento_id');
    }

    public function lancamento()
    {
        return $this->belongsTo('App\Model\Financeiro\LancamentoBancario', 'lancamento_id');
    }

    public function plano_conta()
    {
        return $this->belongsTo('App\Model\Cadastro\ContaContabel', 'plano_conta_id');
    }

}
