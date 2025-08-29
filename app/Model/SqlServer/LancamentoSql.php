<?php

namespace App\Model\SqlServer;

use Illuminate\Database\Eloquent\Model;

class LancamentoSql extends Model
{
      
      protected $table = "lancamentos_bancarios";
      protected $connection = 'sqlsrv';
   

}
