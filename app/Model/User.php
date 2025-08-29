<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Empresa;
use App\Model\Perfil;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{

    //use SoftDeletes;
    use EntrustUserTrait; // add this trait to your user model

    protected $table = "usuarios";
    public  $timestamps  = true;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get all of the tasks for the user.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    
    /**
     * Get all of the tasks for the user.
     */
    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }
    

}
