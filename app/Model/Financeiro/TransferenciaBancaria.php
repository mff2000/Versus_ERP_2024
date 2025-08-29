<?php

namespace App\Model\Financeiro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferenciaBancaria extends Model
{
    //

    use SoftDeletes;
    
    protected $table = "transferencias_bancarias";
    protected $fillable = array (
    	'id',
		'codigo_empresa',
		'codigo_filial',
		'numero_titulo',
		'numero_parcela',
		'tipo_movimento',
		'numero_cheque',
        'historico',
        'tags',
        'banco_origem_id',
		'banco_destino_id',
		'valor_lancamento',
		'data_lancamento',
		'log_ins',
		'log_upd',
		'status'
    );

    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function banco_origem()
    {
        return $this->belongsTo('App\Model\Cadastro\Banco', 'banco_origem_id');
    }

    public function banco_destino()
    {
        return $this->belongsTo('App\Model\Cadastro\Banco', 'banco_destino_id');
    }

}
