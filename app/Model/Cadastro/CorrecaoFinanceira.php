<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorrecaoFinanceira extends Model
{
    //
    //protected $table = "bancos";
    protected $fillable = array (
    	'id', 'descricao', 'plano_conta_id', 'aliquota_juros', 'periodo_juros', 'aliquota_multa', 'periodo_multa', 'limite_multa'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function plano_conta()
    {
        return $this->belongsTo('App\Model\Cadastro\ContaContabel');
    }

}
