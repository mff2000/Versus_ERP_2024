<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelaPreco extends Model
{
    //
    protected $table = "tabelas_preco";
    protected $fillable = array (
    	'id', 'descricao'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function produtos()
    {
        return $this->belongsToMany('App\Model\Cadastro\Produto', 'tabela_has_produto')->withPivot('preco');
    }

    public function favorecidos()
    {
        return $this->hasMany('App\Model\Cadastro\Favorecido', 'tabela_preco_id');
    }
    
}
