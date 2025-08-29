<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendedor extends Model
{
    //
    protected $table = "vendedores";
    protected $fillable = array (
    	'id', 'tipo_pessoa', 'categoria', 'cnpj', 'nome_empresarial', 'nome_fantasia',
    	'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
    	'tel_fixo1', 'tel_fixo2', 'tel_movel1', 'tel_movel2',
    	'email_geral', 'contato_geral', 'percentual_comissao',
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function favorecidos()
    {
        return $this->belongsToMany('App\Model\Cadastro\Favorecido', 'favorecidos_has_vendedores');
    }
    
}
