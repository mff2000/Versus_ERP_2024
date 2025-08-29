
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Agendamentos
	        <small>
	            Listagem de Registros
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('agendamento/create/rct') }}"><i class="fa fa-plus-square"></i> Agendar Recebimento</a>
	  	<a class="btn btn-sm btn-primary" href="{{ url('agendamento/create/pgt') }}"><i class="fa fa-plus-square"></i> Agendar Pagamento</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  	
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Agendamentos Cadastrados</h2>
				<form id="status-form" class="form-horizontal form-label-left mode2" method="GET" action="{{ url('agendamento') }}" novalidate>
					<ul class="nav navbar-right panel_toolbox">
						<li>
							<input type="checkbox" class="flat status" id="liquidados" name="status[]" value="L" {{ (isset($status) && in_array('L', $status)) ? 'checked':'' }} />
							<span class="label label-success" style="color:#fff">Liquidado</span>
						</li>
						<li>
							<input type="checkbox" class="flat status" name="status[]" value="P" {{ (isset($status) && in_array('P', $status)) ? 'checked':'' }} >
							<span class="label label-warning" style="color:#fff">Liquidado Parcial</span>
						</li>
						<li>
							<input type="checkbox" class="flat status" name="status[]" value="A" {{ (isset($status) && in_array('A', $status)) ? 'checked':'' }} >
							<span class="label label-danger" style="color:#fff">Aberto</span>
						</li>
						<li>
							<input type="checkbox" class="flat status" name="status[]" value="B" {{ (isset($status) && in_array('B', $status)) ? 'checked':'' }} >
							<span class="label label-info" style="color:#fff">Borderô</span>
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
				  	getFilter('agendamentos', [
				  		'id' => 'text',
				  		'tipo_movimento'=>['select', [''=>'', 'PGT'=>'Pagamento', 'RCT'=>'Recebimento'] ],
				  		'data_vencimento'=>'data',
				  		'data_competencia'=>'data',
				  		'favorecido_id'=>['select', $favorecidos],
				  		'valor_titulo'=>'currency',
				  		'valor_saldo'=>'currency'
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
			    <th class="column-title">{!! getTitleColumn('Histócio', 'historico', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Tipo', 'tipo_movimento', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Valor Título', 'valor_titulo', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Saldo à Pagar', 'valor_saldo', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Favorecido', 'favorecidos.nome_empresarial', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Número\Parc', 'numero_titulo', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Competência', 'data_competencia', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Vencimento', 'data_vencimento', $orderBy) !!}</th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ações</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  <?php $somatorioValor = $somatorioApagar = 0 ?>
			  @if (count($agendamentos) > 0)
			  	@foreach ($agendamentos as $agendamento)

			  		<?php $somatorioValor += $agendamento->valor_titulo ?>
					<?php $somatorioApagar += $agendamento->valor_saldo ?>

					<tr class="even pointer">
						<td class="a-center ">
							@if($agendamento->valor_saldo==0)
						  		<span class="label label-success">L</span>
						  	@elseif($agendamento->valor_titulo==$agendamento->valor_saldo)
						  		<span class="label label-danger">A</span>
						  	@else
						  		<span class="label label-warning">P</span>
						  	@endif
						  	@if(isset($agendamento->bordero_id))
						  		<span class="label label-info">B</span>
						  	@endif
						</td>
						<td class=" ">{{ $agendamento->id }} </td>
						<td class=" ">{{ $agendamento->historico }} </i></td>
						<td class=" "><span class="label {!! ($agendamento->tipo_movimento=='RCT') ? 'label-primary' : 'label-danger' !!}">{{ $agendamento->tipo_movimento }}</span></td>
						<td class="">{{ priceFormat($agendamento->valor_titulo) }} </i></td>
						<td class="">{{ priceFormat($agendamento->valor_saldo) }} </i></td>
						<td class=" ">{{ $agendamento->favorecido->nome_fantasia }} </i></td>
						<td class=" ">{{ $agendamento->numero_titulo }} - {{ $agendamento->numero_parcela }} </i></td>
						<td class=" ">{{ convertDatePt($agendamento->data_competencia) }} </i></td>
						<td class=" ">{{ convertDatePt($agendamento->data_vencimento) }} </i></td>
						<td class=" last">
							<ul>
								@if($agendamento->valor_saldo > 0 && $agendamento->bordero_id == NULL)
								<li style="float:left;">
							   		<button onclick="window.location='{{ url('agendamento/baixa/'.$agendamento->id) }}'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Baixar" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-money"></i> </button>
							   	</li>
							   	@endif
								{!! linksDefault('agendamento', $agendamento->id, ($agendamento->bordero_id == NULL), ($agendamento->valor_titulo == $agendamento->valor_saldo && ($agendamento->bordero_id == NULL))) !!}
							</ul>
						</td>
					</tr>
			  	 @endforeach
			  @endif

			</tbody>
			<tfoot>
				<tr>
					<th colspan="4"></th>
					<th colspan="">{{priceFormat($somatorioValor)}}</th>
					<th colspan="">{{priceFormat($somatorioApagar)}}</th>
					<th colspan="5"></th>
				</tr>
			</tfoot>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $agendamentos->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $agendamentos->links() !!}

               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection