
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Borderôs de  Pagamentos 
	        <small>
	            Vários títulos unificados em um borderô
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('bordero/create') }}"><i class="fa fa-plus-square"></i> Incluir Borderô</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Borderôs Cadastrados</h2>
				<form id="status-form" class="form-horizontal form-label-left mode2" method="GET" action="{{ url('bordero') }}" novalidate>
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
				  	getFilter('borderos', [
				  		'id' => 'text',
				  		'tipo_bordero'=>['select', [''=>'', 'PGT'=>'Pagamento', 'RCT'=>'Recebimento'] ],
				  		'data_emissao'=>'data',
				  		'data_liquidacao'=>'data',
				  		'descricao'=>'text'
				  	], $filtros, $orderBy)
			  	!!}

			</div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th>
			      <input type="checkbox" id="check-all" class="flat">
			    </th>
			    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Tipo', 'tipo_bordero', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Descrição', 'descricao', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Data Emissão', 'data_emissao', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Data Liquidado', 'data_liquidacao', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Valor', 'valor', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Saldo', 'saldo', $orderBy) !!} </th>
			    <th class="column-title">Qtd Títulos</th>
			    <th class="column-title">Observações</th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  <?php $somaValor = $somaSaldo = 0 ?>

			  @if (count($borderos) > 0)
			  	@foreach ($borderos as $bordero)
			      	
			  		<?php 
			  			$somaValor += $bordero->valor;
			  			$somaSaldo += $bordero->saldo;
			  		?>

			      	<tr class="even pointer">
			        <td class="a-center ">
			        	@if($bordero->data_liquidacao)
			          		<span class="label label-success">L</span>
			          	@else
			          		<span class="label label-danger">A</span>
			          	@endif
			        </td>
			        <td class=" ">{{ $bordero->id }} </td>
			        <td class=" "><span class="label {!! ($bordero->tipo_bordero=='RCT') ? 'label-primary' : 'label-danger' !!}">{{ $bordero->tipo_bordero }}</span></td>
			        <td class=" ">{{ $bordero->descricao }} </td>
			        <td class=" ">{{ convertDatePt($bordero->data_emissao) }} </td>
			        <td class=" ">{{ convertDatePt($bordero->data_liquidacao) }} </td>
			        <td class=" ">{{ priceFormat($bordero->valor) }}</td>
			        <td class=" ">{{ priceFormat($bordero->saldo) }}</td>
			        <td class="">{{ count($bordero->agendamentos) }}</td>
			        <td class=" ">{{ $bordero->observacoes }} </td>
			        <td class=" last">
			        	<ul>
			        		@if($bordero->saldo > 0)
			        		<li style="float:left;">
						   		<button onclick="window.location='{{ url('bordero/baixa/'.$bordero->id) }}'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Baixar" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-money"></i> </button>
						   	</li>
						   	@endif
			        		{!! linksDefault('bordero', $bordero->id, true, (count($bordero->agendamentos) == 0) ? true : false) !!}
			        	</ul>
			        </td>
			      	</tr>
			  	 @endforeach
			  @endif

			</tbody>
			<tfoot>
				<tr>
					<th colspan="6"></th>
					<th>{{priceFormat($somaValor)}}</th>
					<th>{{priceFormat($somaSaldo)}}</th>
					<th colspan="3"></th>
				</tr>
			</tfoot>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $borderos->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $borderos->links() !!}
               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection