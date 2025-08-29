<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    //
    protected $table = "unidades_medida";
    protected $fillable = array (
    	'id', 'codigo','descricao', 'ativo'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function produtos()
    {
        return $this->hasMany('App\Model\Cadastro\Produto', 'unidade_id');
    }

}
