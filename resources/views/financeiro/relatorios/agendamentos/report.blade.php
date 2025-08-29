@extends('layouts.report')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
        	VersusERP
        	<small class="logo">Pagamentos Agendados</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right" style="text-align:right">
          <h6>Versus ERP - Administração Empresarial <br /><?=date('d/m/Y H:i:s')?></h6>
      </div>

    </div>
    <div style="float: left; width: 100%">
     {!!$filtrosDesc!!}
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

				    @if(isset($colunas['numero_titulo']))
				    <th class="column-title">Número-Parc</th>
				    @endif

				    @if(isset($colunas['historico']))
				    <th class="column-title">Histórico</th>
				    @endif

				    @if(isset($colunas['favorecido_id']))
				    <th class="column-title">Favorecido</th>
				    @endif

				    @if(isset($colunas['data_competencia']))
				    <th class="column-title">Competência</th>
				    @endif

				    @if(isset($colunas['data_vencimento']))
				    <th class="column-title">Vencimento</th>
				    @endif

				    @if(isset($colunas['valor_titulo']))
				    <th class="column-title">Valor</th>
				    @endif

				     @if(isset($colunas['valor_saldo']))
				    <th class="column-title">Saldo</th>
				    @endif

				  </tr>
				</thead>
				
				<tbody>
				  <?php 
				  	$resultadoTotal = 0;
				  	$resultadoSaldo = 0;
				  ?>
				  @if (count($agendamentos) > 0)
				  	@foreach ($agendamentos as $agendamento)
				      <tr class="even pointer">
				        
				        @if(isset($colunas['numero_titulo']))
				        <td class=" ">{{$agendamento->id}} - {{ $agendamento->numero_titulo.'/'.$agendamento->numero_parcela }}</td>
						@endif

				        @if(isset($colunas['historico']))
				        <td class=" ">{{ $agendamento->historico }}</td>
				        @endif

				        @if(isset($colunas['favorecido_id']))
				        <td class=" "> 
				        	@if($agendamento->favorecido)
				        	{{ $agendamento->favorecido->nome_fantasia }}
				        	@else
				        	Sem favorecido. [id:{{$agendamento->id}}]
				        	@endif
				        </td>
				        @endif

				        @if(isset($colunas['data_competencia']))
				        <td class=" "> {{ convertDatePt($agendamento->data_competencia) }}</td>
				        @endif

				        @if(isset($colunas['data_vencimento']))
				        <td class=" "> {{ convertDatePt($agendamento->data_vencimento) }}</td>
				        @endif

				        @if(isset($colunas['valor_titulo']))
				        <?php $resultadoTotal += $agendamento->valor_titulo ?>
				        <td class=" "> {{ priceFormat($agendamento->valor_titulo) }}</td>
				        @endif

				        @if(isset($colunas['valor_saldo']))
				        <?php $resultadoSaldo += $agendamento->valor_saldo ?>
				        <td class=" "> {{ priceFormat($agendamento->valor_saldo) }}</td>
				        @endif

				      </tr>
				  	 @endforeach
				  @endif

				</tbody>
				<tfoot>
					<tr>
						<th colspan="5"></th>
						<th>{{ priceFormat($resultadoTotal) }}</th>
						<th>{{ priceFormat($resultadoSaldo) }}</th>
					</tr>
				</tfoot>
				</table>

		      	<div class="btn-toolbar right">
		      		
		      		<div class="col-md-4">
		      			<p> Total de <code>{!! count($agendamentos) !!}</code> registros</p>
		      		</div>
                    		      		
				</div>
		     
		    </div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection