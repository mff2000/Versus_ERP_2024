<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cfop extends Model
{
    //
    protected $table = "cfop";
 	
 	public function tiposTransacao()
    {
        return $this->hasMany('App\Model\Cadastro\TipoTransacao', 'cfop_id');
    }

}
