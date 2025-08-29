
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Lançamentos Bancários
	        <small>
	            Listagem de Registros
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('lancamento/create') }}"><i class="fa fa-plus-square"></i> Incluir Lançamento</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Lançamentos Cadastrados</h2>
				<form id="status-form" class="form-horizontal form-label-left mode2" method="GET" action="{{ url('lancamento') }}" novalidate>
					<ul class="nav navbar-right panel_toolbox">
						<li>
							<input type="checkbox" class="flat status" name="status[]" value="L" {{ (isset($status) && in_array('L', $status)) ? 'checked':'' }}>
							<span class="label label-success" style="color:#fff">Liquidado</span>
						</li>
						<li>
							<input type="checkbox" class="flat status" name="status[]" value="A" {{ (isset($status) && in_array('A', $status)) ? 'checked':'' }}>
							<span class="label label-danger" style="color:#fff">Aberto</span>
						</li>
						<li>
							<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
						</li>
					</ul>
				</form>
				<script>
					$('.status').on('ifChanged', function(event){
						$('#status-form').submit();
					});
				</script>
				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('lancamentos_bancarios', [
				  		'id' => 'text',
				  		'tipo_movimento'=>['select', [''=>'', 'PGT'=>'Pagamento', 'RCT'=>'Recebimento'] ],
				  		'data_lancamento'=>'data',
				  		'data_liquidacao'=>'data',
				  		'favorecido_id'=>['select', $favorecidos],
				  		'banco_id'=>['select', $bancos]
				  	], $filtros, $orderBy)
			  	!!}

			</div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th>
			      <input type="checkbox" id="check-all" class="flat">
			    </th>
			    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Tipo', 'tipo_movimento', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Baixa', 'tipo_baixa', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Histórico', 'historico', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Número\Parc.', 'numero_titulo', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Data Lanç.', 'data_lancamento', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Data Liq.', 'data_liquidacao', $orderBy) !!}</th>
			    <th class="column-title">Cheque </th>
			    <th class="column-title">{!! getTitleColumn('Banco', 'bancos.descricao', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Valor', 'valor_lancamento', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Multa', 'valor_multa', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Juros', 'valor_juros', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Desconto', 'valor_desconto', $orderBy) !!}</th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
				<?php $somaValor = $somaMulta = $somaJuros = $somaDesconto = 0 ?>
				
				@if (count($lancamentos) > 0)
					@foreach ($lancamentos as $lancamento)
						<?php
							$somaValor += $lancamento->valor_lancamento;
							$somaMulta += ($lancamento->valorMulta) ? $lancamento->valorMulta->valor_lancamento : 0;
							$somaJuros += ($lancamento->valorJuros) ? $lancamento->valorJuros->valor_lancamento : 0;
							$somaDesconto += ($lancamento->valorDesconto) ? $lancamento->valorDesconto->valor_lancamento : 0;
						?>

						<tr class="even pointer">
							<td class="a-center ">
								@if($lancamento->data_liquidacao)
							  		<span class="label label-success">L</span>
							  	@else
							  		<span class="label label-danger">A</span>
							  	@endif
							</td>
							<td class=" ">{{ $lancamento->id }} </td>
							<td class=" "><span class="label {!! ($lancamento->tipo_movimento=='RCT') ? 'label-primary' : 'label-danger' !!}">{{ $lancamento->tipo_movimento }}</span></td>
							<td>{{$lancamento->tipo_baixa}}</td>
							<td class=" ">{{ $lancamento->historico }} </i></td>
							<td class=" ">{{ $lancamento->numero_titulo }} - {{ $lancamento->numero_parcela }} </i></td>
							<td class=" ">{{ convertDatePt($lancamento->data_lancamento) }} </i></td>
							<td class=" ">{{ convertDatePt($lancamento->data_liquidacao) }} </i></td>
							<td class=" ">{{ $lancamento->numero_cheque }} </i></td>
							<td class=" ">{{ $lancamento->conta->descricao }}</i></td>
							<td class="">{{ priceFormat($lancamento->valor_lancamento) }} </i></td>
							<td class="">
								@if($lancamento->valorMulta)
									{{ priceFormat($lancamento->valorMulta->valor_lancamento) }}
								@else
									{{ priceFormat(0) }}
								@endif
							</td>
							<td class="">
								@if($lancamento->valorJuros)
									{{ priceFormat($lancamento->valorJuros->valor_lancamento) }}
								@else
									{{ priceFormat(0) }}
								@endif
							</td>
							<td class="">
								@if($lancamento->valorDesconto)
									{{ priceFormat($lancamento->valorDesconto->valor_lancamento) }}
								@else
									{{ priceFormat(0) }}
								@endif
							</td>
							<td class=" last">
								<ul>
									@if(isset($lancamento->agendamento) && $lancamento->tipo_baixa == 'AGD')
									<li style="float:left;">
								   		<button onclick="window.location='{{ url('agendamento/'.$lancamento->agendamento->id.'/edit') }}'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Ver Agendamento" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-eye"></i> </button>
								   	</li>
								   	@endif
									{!! linksDefault('lancamento', $lancamento->id, ($lancamento->data_liquidacao && $lancamento->tipo_baixa == 'AGD') ? false : true, ($lancamento->data_liquidacao && $lancamento->tipo_baixa == 'AGD') ? false : true) !!}
								</ul>
							</td>
						</tr>
					 @endforeach
				@endif

			</tbody>
			<tfoot>
				<tr>
					<th colspan="10"></th>
					<th>{{priceFormat($somaValor)}}</th>
					<th>{{priceFormat($somaMulta)}}</th>
					<th>{{priceFormat($somaJuros)}}</th>
					<th>{{priceFormat($somaDesconto)}}</th>
					<th></th>
				</tr>
			</tfoot>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $lancamentos->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $lancamentos->links() !!}
               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection