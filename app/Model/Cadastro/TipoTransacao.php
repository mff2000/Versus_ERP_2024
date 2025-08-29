<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoTransacao extends Model
{
    //
    protected $table = "tipos_transacao";
    protected $fillable = array (
    	'id', 'descricao', 'tipo', 'cfop_id', 'integra_financeiro', 'integra_estoque', 'plano_conta_debito_id', 
    	'centro_resultado_debito_id', 'plano_conta_credito_id', 'centro_resultado_credito_id', 'ativo'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function cfop()
    {
        return $this->belongsTo('App\Model\Cadastro\Cfop', 'cfop_id');
    }

}
