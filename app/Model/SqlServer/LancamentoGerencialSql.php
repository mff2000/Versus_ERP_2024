<?php

namespace App\Model\SqlServer;

use Illuminate\Database\Eloquent\Model;

class LancamentoGerencialSql extends Model
{
      
      protected $table = "lancamentos_gerenciais";
      protected $connection = 'sqlsrv';
   

}
