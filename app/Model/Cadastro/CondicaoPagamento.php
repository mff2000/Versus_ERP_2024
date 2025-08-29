<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CondicaoPagamento extends Model
{
    //
    protected $table = "condicoes_pagamento";
    protected $fillable = array (
    	'id', 'descricao', 'tipo', 'quantidade_parcelas', 'dias_intervalo', 'dias_carencia', 'percentuais', 'ativo'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function favorecidos()
    {
        return $this->belongsToMany('App\Model\Cadastro\Favorecido', 'favorecidos_has_condicoes_pagamento');
    }

}
