<?php

namespace App\Model\Galeria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoObra extends Model
{
    //
    protected $table = "gal_tipos_obra";
    protected $fillable = array (
    	'id', 'nome'
    	
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

}
