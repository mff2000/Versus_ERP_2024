<?php

namespace App\Model\Financeiro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rateio extends Model
{
    //
    protected $table = "agendamento_rateio";
    //protected $table = "bancos";
    protected $fillable = array (
    	'id',
		'plano_conta_id',
		'centro_resultado_id',
		'projeto_id',
		'porcentagem',
		'valor',
		'ordem',
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

    public function projeto()
    {
        return $this->belongsTo('App\Model\Cadastro\Projeto', 'projeto_id');
    }

    public function centro_resultado()
    {
        return $this->belongsTo('App\Model\Cadastro\CentroResultado', 'centro_resultado_id');
    }

}
