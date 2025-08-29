<?php

namespace App\Model\Financeiro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LancamentoGerencial extends Model
{
    //

    use SoftDeletes;
    
    protected $table = "lancamentos_gerenciais";
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
		'numero_titulo',
		'numero_parcela',
		'favorecido_id',
		'historico',
		'tags',
		'valor_lancamento',
		'data_lancamento',
		'plano_conta_credito_id',
        'plano_conta_debito_id',
        'centro_resultado_credito_id',
        'centro_resultado_debito_id',
        'projeto_credito_id',
        'projeto_debito_id',
		'log_ins',
		'log_upd',
        'status'
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function favorecido()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'favorecido_id');
    }

    public function plano_conta_credito()
    {
        return $this->belongsTo('App\Model\Cadastro\ContaContabel', 'plano_conta_credito_id');
    }

    public function plano_conta_debito()
    {
        return $this->belongsTo('App\Model\Cadastro\ContaContabel', 'plano_conta_debito_id');
    }

    public function centro_resultado_credito()
    {
        return $this->belongsTo('App\Model\Cadastro\CentroResultado', 'centro_resultado_credito_id');
    }

    public function centro_resultado_debito()
    {
        return $this->belongsTo('App\Model\Cadastro\CentroResultado', 'centro_resultado_debito_id');
    }

    public function projeto_credito()
    {
        return $this->belongsTo('App\Model\Cadastro\Projeto', 'projeto_credito_id');
    }

    public function projeto_debito()
    {
        return $this->belongsTo('App\Model\Cadastro\Projeto', 'projeto_debito_id');
    }

}
