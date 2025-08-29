<?php

namespace App\Model\Cadastro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FavorecidoObs extends Model
{

    protected $table = "favorecidos_obs";
    protected $fillable = array (
    	'id', 'obs','favorecido_id', 'usuario'
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function agendamentos()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'favorecido_id');
    }

}
