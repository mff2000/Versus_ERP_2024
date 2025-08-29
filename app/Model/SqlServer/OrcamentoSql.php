<?php

namespace App\Model\SqlServer;

use Illuminate\Database\Eloquent\Model;

class OrcamentoSql extends Model
{
      
      protected $table = "lancamentos_orcamento";
      protected $connection = 'sqlsrv';
   

}
