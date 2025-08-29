<?php

namespace App\Model\Galeria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemConsignacao extends Model
{
    //
    protected $table = "gal_consignacao_itens";
    protected $fillable = array (
    	'id', 'valor_obra', 'obra_id', 'consignacao_id', 'vendido'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function consignacao()
    {
        return $this->belongsTo('App\Model\Galeria\Consignacao', 'consignacao_id');
    }

    public function obra()
    {
        return $this->belongsTo('App\Model\Galeria\Obra', 'obra_id');
    }


}
