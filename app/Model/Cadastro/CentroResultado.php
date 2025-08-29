<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroResultado extends Model
{
    //
    protected $table = "centros_resultado";
    protected $fillable = array(
    	'id', 'codigo', 'descricao', 'classe', 'ativo', 'conta_superior'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
 	
 	public function parent()
    {
        return $this->belongsTo('App\Model\Cadastro\CentroResultado', 'conta_superior');
    }

    public function children()
    {
        return $this->hasMany('App\Model\Cadastro\CentroResultado', 'conta_superior');
    }

    public function rateios()
    {
        return $this->hasMany('App\Model\Financeiro\Rateio', 'centro_resultado_id');
    }

}
