<?php

namespace App\Model\Galeria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Obra extends Model
{
    //
    protected $table = "gal_obras";
    protected $fillable = array (
    	'id', 'titulo', 'dimensao', 'anoexecucao', 'data_aquisicao', 'valor_custo', 'valor_venda',
        'foto', 'data_inclusao', 'valor_minimo_venda', 'estoque',
        'data_venda', 'artista_id', 'tecnica_id', 'tipo_obra_id', 'proprietario_id', 'proposta_id'
    	
    );

    use SoftDeletes;
    public  $timestamps  = true;
 	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

 	public function tipo_obra()
    {
        return $this->belongsTo('App\Model\Galeria\TipoObra', 'tipo_obra_id');
    }

    public function tecnica()
    {
        return $this->belongsTo('App\Model\Galeria\Tecnica', 'tecnica_id');
    }

    public function artista()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'artista_id');
    }

    public function proprietario()
    {
        return $this->belongsTo('App\Model\Cadastro\Favorecido', 'proprietario_id');
    }


}
