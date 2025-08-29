<?php

namespace App\Model\SqlServer;

use Illuminate\Database\Eloquent\Model;

class TransferenciaSql extends Model
{
      
      protected $table = "transferencias_bancarias";
      protected $connection = 'sqlsrv';
   

}
