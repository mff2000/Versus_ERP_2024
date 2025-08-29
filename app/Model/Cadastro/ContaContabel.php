<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaContabel extends Model
{
     //
    protected $table = "planos_contas";
    protected $fillable = array(
        'id', 'codigo', 'descricao', 'classe', 'natureza', 'conta_contabil_ext', 'ativo', 'conta_superior'
    );

    use SoftDeletes;
    public  $timestamps  = true;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function parent()
    {
        return $this->belongsTo('App\Model\Cadastro\ContaContabel', 'conta_superior');
    }

    public function children()
    {
        return $this->hasMany('App\Model\Cadastro\ContaContabel', 'conta_superior');
    }

    public function rateios()
    {
        return $this->hasMany('App\Model\Financeiro\Rateio', 'plano_conta_id');
    }

    public function descontos()
    {
        return $this->hasMany('App\Model\Cadastro\Desconto', 'plano_conta_id');
    }

    public function correcoes()
    {
        return $this->hasMany('App\Model\Cadastro\CorrecaoFinanceira', 'plano_conta_id');
    }
}
