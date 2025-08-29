<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{

    protected $table = "empresas";
    protected $fillable = array(
    	'id', 'nome_empresarial', 'nome_fantasia', 'cnpj', 'codigo_autorizacao', 'data_validade',
    	'endereco', 'numero', 'complemento', 'bairro', 'cep', 'cidade', 'uf',
    	'tel_fixo1', 'tel_fixo2', 'tel_movel1', 'tel_movel2',
    	'email_geral', 'email_nfe', 'email_financ',
    	'contato_geral', 'contato_fiscal', 'contato_financ'
    );
    public  $timestamps  = false;
 
    
}
