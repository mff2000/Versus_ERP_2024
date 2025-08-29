<?php

namespace App\Model\SqlServer;

use Illuminate\Database\Eloquent\Model;

class FavorecidoSql extends Model
{
      protected $table = "favorecidos";
      protected $connection = 'sqlsrv';
   

}
