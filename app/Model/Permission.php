<?php 

namespace App\Model;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

	public  $timestamps  = true;
    protected $dates = ['created_at', 'updated_at'];
    
	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'display_name', 'description'
    ];

}