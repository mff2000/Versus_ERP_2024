@extends('layouts.report')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
        	VersusERP
        	<small class="logo">Extrato Bancário</small>
        </h3>
        <h5>Banco: {{$banco->descricao}}
    		<small class="logo">Período: {{$dataIni}} à {{$dataEnd}}</small>
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

				<table class="table table-striped">
				<thead class="pageheader">
				  <tr class="headings">

				    @if(isset($colunas['data_liquidacao']))
				    <th class="column-title">Data</th>
				    @endif

				    @if(isset($colunas['historico']))
				    <th class="column-title">Histórico</th>
				    @endif

				    @if(isset($colunas['numero_titulo']))
				    <th class="column-title">Número-Parc</th>
				    @endif

				    @if(isset($colunas['valor_lancamento']))
				    <th class="column-title">Débito</th>
				    @endif

				    @if(isset($colunas['valor_lancamento']))
				    <th class="column-title">Crédito</th>
				    @endif

				    <th class="column-title">Saldo</th>

				  </tr>
				</thead>
				
				<tbody>
					<tr class="even pointer">
				        
				        @if(isset($colunas['data_liquidacao']))
				        <td class=" "><b>{{ $dataIni }}</b></td>
				        @endif

				        @if(isset($colunas['historico']))
				        <td class=" "><b>SALDO ANTERIOR</b></td>
				        @endif

				        @if(isset($colunas['numero_titulo']))
				        <td class=" ">--</td>
						@endif

				        @if(isset($colunas['valor_lancamento']))
				        <td class=""> </td>
				        @endif

				        @if(isset($colunas['valor_lancamento']))
				        <td class=""> </td>
				        @endif

				        <td class=" "><b>{{ priceFormat($saldoAnterior) }}</b></td>

				     </tr>
				  <?php $saldoDia = $saldoAnterior ?>
				  @if (count($lancamentos) > 0)
				  	@foreach ($lancamentos as $lancamento)
				  		<?php $valorLancamento = ($lancamento->valor_lancamento-$lancamento->valor_desconto) + ($lancamento->valor_juros+$lancamento->valor_multa) ?>
				      <tr class="even pointer">
				        
				        @if(isset($colunas['data_liquidacao']))
				        <td class=" "> {{ convertDatePt($lancamento->data_liquidacao) }}</td>
				        @endif

				        @if(isset($colunas['historico']))
				        	@if($lancamento->tipo == 'B')
				        		<td class=" "><a href="{{ url('bordero/'.$lancamento->id.'/edit') }}" target="_BLANK">{{ $lancamento->historico }}</a></td>
				        	@elseif($lancamento->tipo == 'L')
				        		<td class=" "><a href="{{ url('lancamento/'.$lancamento->id.'/edit') }}" target="_BLANK">{{ $lancamento->historico }}</a></td>
				        	@else
				        		<td class=" "><a href="{{ url('transferencia/'.$lancamento->id.'/edit') }}" target="_BLANK">{{ $lancamento->historico }}</a></td>
				        	@endif
				        @endif

				        @if(isset($colunas['numero_titulo']))
				        <td class=" ">{{ $lancamento->numero_titulo.'/'.$lancamento->numero_parcela }}</td>
						@endif

				        @if(isset($colunas['valor_lancamento']) && ($lancamento->tipo_movimento == 'PGT'))
				        <td class="red"> - {{ priceFormat($valorLancamento) }}</td>
				        <td class="blue"> </td>
				        @endif

				        @if(isset($colunas['valor_lancamento']) && ($lancamento->tipo_movimento == 'RCT'))
				        <td class="red"> </td>
				        <td class="blue"> + {{ priceFormat($valorLancamento) }}</td>
				        @endif

				        <?php
				        	($lancamento->tipo_movimento == 'PGT') ? $saldoDia -= $valorLancamento:$saldoDia += $valorLancamento
				        ?>
				        <td class=" ">{{priceFormat($saldoDia)}}</td>

				      </tr>
				  	 @endforeach
				  @endif

				</tbody>
					<tfoot>
						<tr class="even pointer">
				        <td class=""></td>
				        <td class=""></td>
				        <td class=""></td>
				        <td class=""></td>
				        <td class=""></td>
				        <td class=" "><span>Saldo em {{$dataEnd}}</span><h2><b>{{ priceFormat($saldoDia) }}</b></h2></td>
				     </tr>
					</tfoot>
				</table>

		      	<div class="btn-toolbar right">
		      		
		      		<div class="col-md-4">
		      			<p> Total de <code>{!! count($lancamentos) !!}</code> registros</p>
		      		</div>
                    		      		
				</div>
		     
		    </div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection