
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
			Vendas
	        <small>
	            Listagem
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('galeria/venda/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Vendas</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('venda', [
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
			    <th class="column-title">Tipo</th>
			    <th class="column-title">Valor</th>
			    <th class="column-title">Situação</th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($vendas) > 0)
			  	@foreach ($vendas as $venda)
			      <tr class="even pointer" >
			        <td class=" ">{{ $venda->id }} </td>
			        <td class=" ">{{ convertDatePt($venda->data_inclusao) }} </td>
			        <td class=" ">{{ $venda->cliente->nome_empresarial }}</td>			        
			        <td class=" ">
			        	@if($venda->proposta_id !== null)
			        	Por consignação{{$venda->proposta_id}}
			        	@else
			        	Direta
			        	@endif
			        </td>
			        <td class=" ">{{ priceFormat($venda->valor) }}</td>
			        <td class=" ">{{ getSituacaoVenda($venda->situacao) }}</td>
			        <td class=" last">
			        	<ul>
			        		{!! linksDefault('galeria/venda', $venda->id, true, true) !!}
			        	</ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $vendas->total() !!}</code> registros</p>
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $vendas->links() !!}
               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection