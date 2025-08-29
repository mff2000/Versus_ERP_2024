@extends('layouts.report')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
        	VersusERP
        	<small class="logo">Fluxo de Caixa</small>
        </h3>
        <h5>Banco: 
    		<small class="logo">Período: {{}}</small>
    	</h5>
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

				<table class="table">
					<thead class="pageheader">
					  <tr class="headings">

					   	<th></th>
					   	<th style="text-align: center;">Atrasados</th>
					   	<th style="text-align: center;">
			   				@if($periodo == 1)
			   				Hoje
			   				@elseif($periodo == 2)
			   				Semana Atual
			   				@elseif($periodo == 3)
			   				Quinzena Atual
			   				@else
			   				Mês Atual
			   				@endif
			   				<span style="display: block;">
			   					{{convertDatePt($datas[0][1]->toDateString())}}
			   					@if(isset($datas[0][2]))
			   					até {{convertDatePt($datas[0][2]->toDateString())}}
			   					@endif
			   				</span>
			   			</th>
					   	@for($i=1; $i<=5; $i++)
					   		@if($periodo == 1)
					   			<th style="text-align: center;">
					   				Dia 0{{$i}}
					   				<span style="display: block;">{{convertDatePt($datas[$i][1]->toDateString())}}</span>
					   			</th>
					   		@elseif($periodo == 2)
					   			<th style="text-align: center;">
					   				Semana 0{{$i}}
					   				<span style="display: block;">
					   					{{convertDatePt($datas[$i][1]->toDateString())}}
					   					à {{convertDatePt($datas[$i][2]->toDateString())}}
					   				</span>
					   			</th>
					   		@elseif($periodo == 3)
					   			<th style="text-align: center;">
					   				Quinzena 0{{$i}}
					   				<span style="display: block;">
					   					{{convertDatePt($datas[$i][1]->toDateString())}}
					   					à {{convertDatePt($datas[$i][2]->toDateString())}}
					   				</span>
					   			</th>
					   		@else
					   			<th style="text-align: center;">
					   				{{ getNameMonth($datas[$i][1]->month) }}
					   				<span style="display: block;">
					   					{{convertDatePt($datas[$i][1]->toDateString())}}
					   					à {{convertDatePt($datas[$i][2]->toDateString())}}
					   				</span>
					   			</th>
					   		@endif
					   	@endfor

					  </tr>
					  <tr class="headings">

					   	<th>Saldo Anterior/Disponibilidade</th>
					   	<th style="text-align: center;">-</th>
					   	<th style="text-align: center;">{{priceFormat($saldoAtual)}}</th>
					   	@for($i=1; $i<=5; $i++)
					   		<?php
					   			if($i>1) {
					   				$saldo[$i] = $saldo[$i-1];
					   				//for ($x=1; $x < $i; $x++) { 
					   				$saldo[$i] += ($totalAreceber[$i-1] - $totalApagar[$i-1]);
					   				//}
					   			}
					   			else {
					   				$saldo[$i] = ($saldoAtual + ($totalAreceber[$i-1] - $totalApagar[$i-1]));
					   			}
					   		?>
					   		<th style="text-align: center;">{{priceFormat($saldo[$i])}}</th>
					   	@endfor

					  </tr>
					  <tr class="headings">

					   	<th>Limite Especial Total</th>
					   	<th style="text-align: center;">-</th>
					   	@for($i=0; $i<=5; $i++)
					   		<th style="text-align: center;">{{priceFormat($limite)}}</th>
					   	@endfor

					  </tr>
					  <tr class="headings">

					   	<th>Saldo Total Atual</th>
					   	<th style="text-align: center;">-</th>
					   	<th style="text-align: center;">{{priceFormat($saldoAtual + $limite)}}</th>
					   	@for($i=1; $i<=5; $i++)
					   		<th style="text-align: center;">{{priceFormat($saldo[$i] + $limite)}}</th>
					   	@endfor

					  </tr>
					</thead>
					
					<tbody>
						
						<tr class="even pointer" style="background: #f2f2f2;">
					        
					    	<th>Contas à Receber</th>
					    	<th style="text-align: center;">{{priceFormat($saldoAtrasado['receber'])}}</th>
					    	@for($i=0; $i<=5; $i++)
						   		<th style="text-align: center;">{{priceFormat($totalAreceber[$i])}}</th>
						   	@endfor
					    	
					    </tr>

						<tr class="even pointer" style="background: #f2f2f2;">
					        
					    	<th>Contas à Pagar</th>
					    	<th style="text-align: center;">{{priceFormat($saldoAtrasado['apagar'])}}</th>
					    	@for($i=0; $i<=5; $i++)
						   		<th style="text-align: center;">{{priceFormat($totalApagar[$i])}}</th>
						   	@endfor
					    	
					    </tr>

					    <? /*
					    @if(isset($lancamentosApagar) && count($lancamentosApagar) > 0)
					    @foreach($lancamentosApagar as $lancamento)

					    	@if($lancamento['total'] > 0)
					    	<tr class="even pointer">
					        
						    	<td>{{$lancamento['plano']}}</td>
						    	<td style="text-align: center;">0.00</td>
						    	@for($i=1; $i<=5; $i++)
							   		<td style="text-align: center;">{{priceFormat($lancamento['valor'][$i])}}</td>
							   	@endfor
						    	
						    </tr>
						    @endif

					    @endforeach
					    @endif
						*/ ?>
						
					    
					    <? /*
					    @if(isset($lancamentosAreceber) && count($lancamentosAreceber) > 0)
					    @foreach($lancamentosAreceber as $lancamento)

					    	@if($lancamento['total'] > 0)
					    	<tr class="even pointer">
					        
						    	<td>{{$lancamento['plano']}}</td>
						    	<td style="text-align: center;">0.00</td>
						    	@for($i=1; $i<=5; $i++)
							   		<td style="text-align: center;">{{priceFormat($lancamento['valor'][$i])}}</td>
							   	@endfor
						    	
						    </tr>
						    @endif

					    @endforeach
					    @endif
					    */ ?>
					</tbody>
					<tfoot>
						<tr class="even pointer">
					        <th class="">Sub-total</th>
					        <th style="text-align: center;">-</th>
					        <th style="text-align: center;">{{priceFormat($saldoAtual + ($totalAreceber[0]-$totalApagar[0]))}}</th>
					        @for($i=1; $i<=5; $i++)
					        	<?php
					        		$subTotal[$i] = $saldo[$i] + ($totalAreceber[$i]-$totalApagar[$i]);
					        	?>
						   		<th style="text-align: center;">{{priceFormat($subTotal[$i])}}</th>
						   	@endfor
					     </tr>

					     <tr class="even pointer">

						   	<th>Limite Especial Total</th>
						   	<th style="text-align: center;">-</th>
						   	@for($i=0; $i<=5; $i++)
						   		<th style="text-align: center;">{{priceFormat($limite)}}</th>
						   	@endfor

						  </tr>

						  <tr class="even pointer" style="background: #f2f2f2;">

						   	<th>Total</th>
						   	<th style="text-align: center;">{{priceFormat($saldoAtrasado['receber']-$saldoAtrasado['apagar'])}}</th>
						   	<th style="text-align: center;">{{priceFormat($saldoAtual + ($totalAreceber[0]-$totalApagar[0]) + $limite)}}</th>
						   	@for($i=1; $i<=5; $i++)
						   		<th style="text-align: center;">{{priceFormat($subTotal[$i]+$limite)}}</th>
						   	@endfor

						  </tr>
					</tfoot>
				</table>
		     
		    </div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection