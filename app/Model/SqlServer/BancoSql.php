<?php

namespace App\Model\SqlServer;

use Illuminate\Database\Eloquent\Model;

class BancoSql extends Model
{
      protected $table = "bancos";
      protected $connection = 'sqlsrv';
   

}
