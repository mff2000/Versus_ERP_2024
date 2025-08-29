<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    //
    protected $table = "produtos";
    protected $fillable = array (
    	'id', 'ncm','descricao', 'altura', 'largura', 'espessura','fator_multiplo', 'grupo_id', 'unidade_id', 'armazem_id', 'ativo'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function grupo()
    {
        return $this->belongsTo('App\Model\Cadastro\GrupoProduto', 'grupo_id');
    }

    public function unidade()
    {
        return $this->belongsTo('App\Model\Cadastro\Unidade', 'unidade_id');
    }

    public function armazem()
    {
        return $this->belongsTo('App\Model\Cadastro\Armazem', 'armazem_id');
    }
    
    public function tabelas()
    {
        return $this->belongsToMany('App\Model\Cadastro\TabelaPreco', 'tabela_has_produto');
    }

    public function favorecidos()
    {
        return $this->belongsToMany('App\Model\Cadastro\Favorecido', 'favorecidos_has_desconto');
    }

}
