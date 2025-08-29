
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
			Consignações
	        <small>
	            Listagem
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('galeria/consignacao/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Consignações</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('consignacao', [
				  		'cliente_id'=>['select', $clientes ]
				  	], $filtros)
			  	!!}

		    </div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th class="column-title">ID </th>
			    <th class="column-title">Data </th>
			    <th class="column-title">Cliente</th>
			    <th class="column-title">Data Devolução</th>
			    <th class="column-title">Valor</th>
			    <th class="column-title">Situação</th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($consignacoes) > 0)
			  	@foreach ($consignacoes as $consignacao)
			      <tr class="even pointer" >
			        <td class=" ">{{ $consignacao->id }}</td>
			        <td class=" ">{{ convertDatePt($consignacao->data_inclusao) }}</td>
			        <td class=" ">{{ $consignacao->cliente->nome_empresarial }}</td>			        
			        <td class=" ">{{ convertDatePt($consignacao->data_devolucao) }}</td>
			        <td class=" ">{{ priceFormat($consignacao->valor) }}</td>
			        <td class=" ">{{ getSituacaoConsignacao($consignacao->situacao) }}</td>
			        <td class=" last" style="width: 140px">
			        	<ul>
			        		@if($consignacao->situacao=='A')
			        		<li style="float:left;">
						   		<button onclick="window.location='{{ url('galeria/consignacao/retorno/'.$consignacao->id) }}'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Retornar" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-download"></i> </button>
						   	</li>
						   	@endif
			        		{!! linksDefault('galeria/consignacao', $consignacao->id, ($consignacao->situacao=='A'), true) !!}
			        	</ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $consignacoes->total() !!}</code> registros</p>
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $consignacoes->links() !!}
               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection