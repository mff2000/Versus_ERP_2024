<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desconto extends Model
{
    //
    //protected $table = "bancos";
    protected $fillable = array (
    	'id', 'descricao', 'plano_conta_id'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function plano_conta()
    {
        return $this->belongsTo('App\Model\Cadastro\ContaContabel', 'plano_conta_id');
    }

    public function lancamentos()
    {
        return $this->hasMany('App\Model\Financeiro\LancamentoBancario', 'desconto_id');
    }

}
