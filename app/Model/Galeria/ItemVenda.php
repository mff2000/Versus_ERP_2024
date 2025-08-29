<?php

namespace App\Model\Galeria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemVenda extends Model
{
    //
    protected $table = "gal_venda_itens";
    protected $fillable = array (
    	'id', 'valor_obra', 'obra_id', 'venda_id'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function venda()
    {
        return $this->belongsTo('App\Model\Galeria\Venda', 'venda_id');
    }

    public function obra()
    {
        return $this->belongsTo('App\Model\Galeria\Obra', 'obra_id');
    }


}
