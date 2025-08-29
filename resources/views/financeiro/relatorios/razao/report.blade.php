@extends('layouts.report')

@section('content')

<style> 
.separator { border-bottom: 2px solid #ddd; background: #f2f2f2; }
</style>

<div class="page-title">
    <div class="title_left">
        <h3>
        	VersusERP
        	<small class="logo">Relatório de Razão</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right" style="text-align:right">
          <h6>Versus ERP - Administração Empresarial <br /><?=date('d/m/Y H:i:s')?></h6>
      </div>

    </div>
    <div style="float: left;width: 100%">
     {!!$filtrosDesc!!}
    </div>
</div>
   
<div class="clearfix"></div>

<div class="row">

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_content">

				<table class="table">
					<thead  class="pageheader">
					  <tr class="headings">
					    
					    <th class="column-title">ID</th>
					    <th class="column-title">Histórico</th>
					    <th class="column-title">Favorecido</th>
					    <th class="column-title">Tipo</th>
					    <th class="column-title">Plano de Contas</th>
					    <th class="column-title">Centro de Resultado</th>
					    <th class="column-title">Projeto</th>
					    <th class="column-title">Competência</th>
					    <th class="column-title">Vencimento</th>
					    <th class="column-title">Liquidação</th>
					    <th class="column-title">Valor</th>
					    <th class="column-title">Totalizador</th>
					    <th class="column-title">Número/Qtd Rateio</th>

					  </tr>
					</thead>
				
				<tbody>
				  <?php 
				  	$resultadoTotal = 0;
				  	$plano_conta_id = 0;
				  	$centro_resultado_id = 0;
				  	$projeto_id = 0;
				  	$totalizador = 0;
				  	$newSeparator = 1;
				  ?>
				  @if (count($agendamentos) > 0)
				  	
				  	@foreach ($agendamentos as $key => $agendamento)

				  		<?php
				  			if($orderBy == 'plano_conta_id') {
				  				if($plano_conta_id != $agendamento->plano_conta_id) {
					        		$totalizador = 0;
					        		$plano_conta_id = $agendamento->plano_conta_id;

					        		if($newSeparator)
					        			$newSeparator = 0;
					        		else
					        			$newSeparator = 1;
					        	}	
				  			} elseif ($orderBy == 'centro_resultado_id') {
				  				if($centro_resultado_id != $agendamento->centro_resultado_id) {
					        		$totalizador = 0;
					        		$centro_resultado_id = $agendamento->centro_resultado_id;

					        		if($newSeparator)
					        			$newSeparator = 0;
					        		else
					        			$newSeparator = 1;
					        	}	
				  			} else {
				  				if($projeto_id != $agendamento->projeto_id) {
					        		$totalizador = 0;
					        		$projeto_id = $agendamento->projeto_id;

					        		if($newSeparator)
					        			$newSeparator = 0;
					        		else
					        			$newSeparator = 1;
					        	}	
				  			}
				        	
				        ?>

				      	<tr class="even pointer {{($newSeparator) ? 'separator':''}}">
				        	
					        <td class="">{{ $agendamento->id }}</td>
					        <td class="">{{ $agendamento->historico }}</td>
					        <td class="">{{ $agendamento->nome_empresarial }}</td>
					        <td class="">{{ $agendamento->tipo_movimento }}</td>
					        <td class="">{{ getCodigoCompletoPlanoConta($agendamento->plano_conta_id) }}</td>
					        <td class="">{{ getCodigoCompletoCentroResultado($agendamento->centro_resultado_id) }}</td>
					        <td class="">{{ getCodigoCompletoProjeto($agendamento->projeto_id) }}</td>
					        <td class="">{{ convertDatePt($agendamento->data_competencia) }}</td>
					        <td class="">{{ convertDatePt($agendamento->data_vencimento) }}</td>
					        <td class="">{{ convertDatePt($agendamento->data_liquidacao) }}</td>
					        
					        <?php 
					        	$totalizador += $agendamento->valor;
					        	$resultadoTotal += $agendamento->valor;
					        ?>
					        <td class=""> {{ priceFormat($agendamento->valor) }}</td>

						    
					        <td class="" >{{ priceFormat($totalizador) }}</td>
					       

					        <td class="" >{{ $agendamento['ordem'] .'/'.count($agendamento->rateios) }}</td>
					        
				      	</tr>
				  	 @endforeach
				  @endif

				</tbody>
				<tfoot>
					<tr>
						<th colspan="11"></th>
						<th>{{ priceFormat($resultadoTotal) }}</th>
						<th></th>
					</tr>
				</tfoot>

				</table>
		     
		    </div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection