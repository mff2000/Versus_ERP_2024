<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banco extends Model
{
    //
    protected $table = "bancos";
    protected $fillable = array (
    	'id', 'codigo','agencia', 'dv_agencia', 'numero_conta', 'dv_conta', 'descricao', 'limite', 'saldo_atual',
    	'tel_fixo1', 'tel_fixo2', 'tel_movel1', 'tel_movel2',
    	'nome_gerente', 'email_geral',
    	'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
    	
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];


    public function lancamentos()
    {
        return $this->hasMany('App\Model\Financeiro\LancamentoBancario', 'banco_id');
    }

    public function transferencias_saida()
    {
        return $this->hasMany('App\Model\Financeiro\TransferenciaBancaria', 'banco_origem_id');
    }

    public function transferencias_entrada()
    {
        return $this->hasMany('App\Model\Financeiro\TransferenciaBancaria', 'banco_destino_id');
    }

}
