<?php

namespace App\Model\Galeria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consignacao extends Model
{
    //
    protected $table = "gal_consignacoes";
    protected $fillable = array (
    	'id', 'situacao', 'data_inclusao', 'valor', 'descontos', 'observacao',
        'data_devolucao', 'cliente_id', 'usuario_id'	
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function cliente()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'cliente_id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Model\User', 'usuario_id');
    }

    public function itens()
    {
        return $this->hasMany('App\Model\Galeria\ItemConsignacao', 'consignacao_id');
    }

}
