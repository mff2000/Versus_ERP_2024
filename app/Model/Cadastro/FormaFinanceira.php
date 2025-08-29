<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormaFinanceira extends Model
{
    //
    //protected $table = "bancos";
    protected $fillable = array (
    	'id', 'codigo','descricao', 'liquida', 'ativo'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function lancamentos()
    {
        return $this->hasMany('App\Model\Financeiro\LancamentoBancario', 'forma_financeira_id');
    }
}
