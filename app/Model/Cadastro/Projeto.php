<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projeto extends Model
{
    //
    protected $table = "projetos";
    protected $fillable = array(
    	'id', 'codigo', 'descricao', 'classe', 'ativo', 'conta_superior'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
 	
 	public function parent()
    {
        return $this->belongsTo('App\Model\Cadastro\Projeto', 'conta_superior');
    }

    public function children()
    {
        return $this->hasMany('App\Model\Cadastro\Projeto', 'conta_superior');
    }

    public function rateios()
    {
        return $this->hasMany('App\Model\Financeiro\Rateio', 'projeto_id');
    }

}
