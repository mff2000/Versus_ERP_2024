
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
			Pedidos de Contratos de Fornecimento
	        <small>
	            Listagem
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('pedidoContrato/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Pedidos de Contratos de Fornecimento</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('pedido_contrato', [
				  		'contrato_id'=>['select', $contratos ],
				  		'data_vigencia_inicio'=>'data',
				  		'data_vigencia_fim'=>'data',
				  		'historico'=>'text'
				  	], $filtros)
			  	!!}

		    </div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th class="column-title">ID </th>
			    <th class="column-title">Favorecido </th>
			    <th class="column-title">Nº Pedido Cliente</th>
			    <th class="column-title">Vendedor </th>
			    <th class="column-title">Etapa</th>
			    <th class="column-title">Emissão</th>
			    <th class="column-title">Prazo Entrega </th>
			    <th class="column-title">Valor </th>
			    <th class="column-title">Ativo </th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($pedidos) > 0)
			  	@foreach ($pedidos as $pedido)
			      <tr class="even pointer" >
			        <td class=" ">{{ $pedido->id }} </td>
			        <td class=" ">{{ $pedido->contrato->favorecido->nome_empresarial }} </td>
			        <td class=" ">{{ $pedido->pedido_favorecido }}</td>			        
			        <td class=" ">{{ $pedido->contrato->vendedor1->id .'-'. $pedido->contrato->vendedor1->nome_empresarial }} </td>
			        <td class=" ">{{ getEtapasComercial($pedido->etapa) }}</td>
			        <td class=" ">{{ convertDatePt($pedido->data_emissao) }}</td>
			        <td class=" ">{{ convertDatePt($pedido->data_entrega) }}</td>
			        <td class=" ">{{ priceFormat($pedido->valor) }} </td>
			        <td class=" "><span class="label {!! ($pedido->ativo==1) ? 'label-primary' : 'label-danger' !!}">{{ ($pedido->ativo==1) ? 'SIM':'NÃO' }}</span></td>
			        <td class=" last">
			        	<ul>
			        		{!! linksDefault('pedidoContrato', $pedido->id, true, true) !!}
			        	</ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $pedidos->total() !!}</code> registros</p>
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $pedidos->links() !!}
               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection