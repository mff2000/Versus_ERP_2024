<?php

namespace App\Model\SqlServer;

use Illuminate\Database\Eloquent\Model;

class AgendamentoSql extends Model
{
      
      protected $table = "agendamentos";
      protected $connection = 'sqlsrv';
   

}
