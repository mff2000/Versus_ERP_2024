<?php

namespace App\Model\SqlServer;

use Illuminate\Database\Eloquent\Model;

class PlanoContaSql extends Model
{
      protected $table = "contas_financeiras";
      protected $connection = 'sqlsrv';
   

}
