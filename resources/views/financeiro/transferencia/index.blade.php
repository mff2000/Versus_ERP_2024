
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Transferências Bancária
	        <small>
	            Listagem de Registros
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('transferencia/create') }}"><i class="fa fa-plus-square"></i> Incluir Transferência</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Transferências entre contas bancárias</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('transferencias_bancarias', [
				  		'data_lancamento'=>'data',
				  		'banco_origem_id'=>['select', $bancos],
				  		'banco_destino_id'=>['select', $bancos],
				  		'historico'=>'text'
				  	], $filtros, $orderBy)
			  	!!}

			</div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Data Lanç.', 'data_lancamento', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Origem', 'descricao_origem', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Destino', 'descricao_destino', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Histórico', 'historico', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Número\Parc.', 'numero_titulo', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Valor', 'valor_lancamento', $orderBy) !!} </th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  <?php $soma = 0 ?>
			  @if (count($transferencias) > 0)
			  	@foreach ($transferencias as $transferencia)

			  		<?php $soma += $transferencia->valor_lancamento; ?>

			      	<tr class="even pointer">
				        <td class=" ">{{ $transferencia->id }} </td>
				        <td class=" ">{{ convertDatePt($transferencia->data_lancamento) }} </i></td>
				        <td class=" ">{{ $transferencia->banco_origem->descricao }}</i></td>
				        <td class=" ">{{ $transferencia->banco_destino->descricao }}</i></td>
				        <td class=" ">{{ $transferencia->historico }} </i></td>
				        <td class=" ">{{ $transferencia->numero_titulo }} - {{ $transferencia->numero_parcela }} </i></td>
				        <td class="">{{ priceFormat($transferencia->valor_lancamento) }} </i></td>
				        <td class=" last">
				        	<ul>
				        		{!! linksDefault('transferencia', $transferencia->id, ($transferencia->data_liquidacao) ? false : true, ($transferencia->data_liquidacao) ? false : true) !!}
				        	</ul>
				        </td>
			      	</tr>
			  	 @endforeach
			  @endif

			</tbody>
			<tfoot>
				<tr>
					<th colspan="6"></th>
					<th>{{priceFormat($soma)}}</th>
					<th></th>
				</tr>
			</tfoot>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $transferencias->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $transferencias->links() !!}
               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection