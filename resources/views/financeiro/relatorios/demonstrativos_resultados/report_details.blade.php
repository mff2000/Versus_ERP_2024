@extends('layouts.report')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
        	VersusERP
        	<small class="logo">Movimentação detalhada [ Plano de Conta: {{$planoConta->descricao}} ]</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right" style="text-align:right">
          <h6>Versus ERP - Administração Empresarial <br /><?=date('d/m/Y H:i:s')?></h6>
      </div>

    </div>
</div>
   
<div class="clearfix"></div>

<div class="row">

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_content">

				<table class="table table-striped">
				<thead>
				  <tr class="headings">

				  	<th class="column-title">Competência</th>

				    <th class="column-title">Vencimento</th>
				    
				    <th class="column-title">Baixa</th>

				    <th class="column-title">Histórico</th>
				    
				    <th class="column-title">Entrada</th>
	
				    <th class="column-title">Saída</th>
				    
				    <th class="column-title">Centro Result.</th>

				    <th class="column-title">Projeto</th>

				  </tr>
				</thead>
				
				<tbody>
				  <?php $entradas = $saidas = 0 ?>
				  @if (count($agendamentos) > 0)
				  	@foreach ($agendamentos as $agendamento)
				  		
					    <tr class="even pointer">
					        
							<td class=" "> {{ convertDatePt($agendamento->data_competencia) }}</td>
					        <td class=" "> {{ convertDatePt($agendamento->data_vencimento) }}</td>
					        <td class=" "> {{ convertDatePt($agendamento->data_lancamento) }}</td>
					        <td class=" ">
					        	<a href="{{ url('/agendamento/'.$agendamento->id.'/edit') }}" target="_blank">
					        		{{ $agendamento->historico }}
					        	</a>
					        </td>
					        <td class=" ">

					        	@if($planoConta->natureza == 'C' && $agendamento->tipo_movimento == 'PGT')
					        		{{ priceFormat($agendamento->valor_titulo * ($agendamento->porcentagem/100)) }}
					        		<?php $entradas += $agendamento->valor_titulo * ($agendamento->porcentagem/100) ?>
					        	@endif

					        	@if($planoConta->natureza == 'D' && $agendamento->tipo_movimento == 'RCT')
						        	{{ priceFormat($agendamento->valor_titulo * ($agendamento->porcentagem/100)) }}
						        	<?php $entradas += $agendamento->valor_titulo * ($agendamento->porcentagem/100) ?>
					        	@endif

					        	
					        </td>
					        <td class=" "> 

					        	@if($planoConta->natureza == 'C' && $agendamento->tipo_movimento == 'RCT')
					        		{{ priceFormat($agendamento->valor_titulo * ($agendamento->porcentagem/100)) }}
					        		<?php $saidas += $agendamento->valor_titulo * ($agendamento->porcentagem/100) ?>
					        	@endif

					        	@if($planoConta->natureza == 'D' && $agendamento->tipo_movimento == 'PGT')
					        		{{ priceFormat($agendamento->valor_titulo * ($agendamento->porcentagem/100)) }}
					        		<?php $saidas += $agendamento->valor_titulo * ($agendamento->porcentagem/100) ?>
					        	@endif

					        	
					        </td>
					        <td class=" "> {{ $agendamento->centro }}</td>
					        <td class=" "> {{ $agendamento->projeto }}</td>

					    </tr>
					    
				  	 @endforeach
				  @endif

				</tbody>
				</table>
		     
		    </div>

	 	</div>

	</div>

	<div class="col-md-12">
		<ul class="stats-overview">
          <li>
            <span class="name"> Total de  registros </span>
            <span class="value text-success"> <code>{!! count($agendamentos) !!}</code> </span>
          </li>
          <li>
            <span class="name"> Entradas </span>
            <span class="value text-success"> {{ priceFormat($entradas) }} </span>
          </li>
          <li class="hidden-phone">
            <span class="name"> Saídas </span>
            <span class="value text-success"> {{ priceFormat($saidas) }} </span>
          </li>
          <li>
            <span class="name"> Resultado </span>
            <span class="value text-success"> {{ priceFormat($entradas - $saidas) }} </span>
          </li>
        </ul>
	</div>
	
	<div class="text-center mtop20 no-print">
      	<div class="actionBar">
          <a class="btn btn-sm btn-primary" onclick="javascript:window.print();"><i class="fa fa-print"></i> Imprimir</a>
          <a class="btn btn-sm btn-warning" onclick="javascript:window.close();"><i class="fa fa-times"></i> Fechar</a>
        </div>
    </div>

</div><!-- /#row -->

@endsection