<?php

namespace App\Model\Financeiro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orcamento extends Model
{
    //

    use SoftDeletes;
    
    protected $table = "lancamentos_orcamento";
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
        'tipo_movimento',
		'historico',
		'tags',
		'valor_lancamento',
		'data_vencimento',
        'data_competencia',
		'plano_conta_id',
        'centro_resultado_id',
        'projeto_id',
		'log_ins',
		'log_upd'
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function plano_conta()
    {
        return $this->belongsTo('App\Model\Cadastro\ContaContabel', 'plano_conta_id');
    }

    public function centro_resultado()
    {
        return $this->belongsTo('App\Model\Cadastro\CentroResultado', 'centro_resultado_id');
    }

    public function projeto()
    {
        return $this->belongsTo('App\Model\Cadastro\Projeto', 'projeto_id');
    }

}
