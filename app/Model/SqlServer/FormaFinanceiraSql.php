<?php

namespace App\Model\SqlServer;

use Illuminate\Database\Eloquent\Model;

class FormaFinanceiraSql extends Model
{
      protected $table = "formas_financeiras";
      protected $connection = 'sqlsrv';
   

}
