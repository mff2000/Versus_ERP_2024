<?php

namespace App\Model\Financeiro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bordero extends Model
{
    use SoftDeletes;
    
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
		'codigo',
		'tipo_bordero',
        'descricao',
		'data_emissao',
		'valor',
        'saldo',
		'observacoes',
		'banco_id',
		'data_liquidacao',
		'log_ins',
		'log_upd'
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function banco()
    {
        return $this->belongsTo('App\Model\Cadastro\Banco', 'banco_id');
    }

    public function agendamentos()
    {
        return $this->hasMany('App\Model\Financeiro\Agendamento', 'bordero_id');
    }

    public function lancamentos()
    {
        return $this->hasMany('App\Model\Financeiro\LancamentoBancario', 'bordero_id')
            ->selectRaw('*, sum(valor_lancamento) as valor_lancamento')
            ->groupBy('baixa_id');
    }

}
