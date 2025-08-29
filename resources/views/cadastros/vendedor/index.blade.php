
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Vendedores
	        <small>
	            Cadastro de Vendedores
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('vendedor/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Vendedores Cadastrados</h2>

				<ul class="nav navbar-right panel_toolbox">
					
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
					
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none">

		    	{!! getFilter('vendedores', [
		    		'nome_empresarial'=>'text',
		    		'nome_fantasia'=>'text',
		    		'cnpj'=>'cpf_cnpj'
		    	], $filtros)!!}

		    </div>
				<table class="table table-striped responsive-utilities jambo_table bulk_action">
				<thead>
				  <tr class="headings">
				    <th class="column-title">ID </th>
				    <th class="column-title">Razão Social </th>
				    <th class="column-title">Nome Fantasia </th>
				    <th class="column-title">CNPJ/CPF </th>
				    <th class="column-title">Categoria </th>
				    <th class="column-title">Comissão </th>
				    <th class="column-title no-link last" width="90">
				    	<span class="nobr">Ação</span>
				    </th>
				  </tr>
				</thead>

				<tbody>
				  
				  @if (count($vendedores) > 0)
				  	@foreach ($vendedores as $vendedor)
				      <tr class="even pointer">
				        <td class=" ">{{ $vendedor->id }}</td>
				        <td class=" ">{{ $vendedor->nome_empresarial }}</td>
				        <td class=" ">{{ $vendedor->nome_fantasia }}</td>
				        <td class=" ">{{ $vendedor->cnpj }}</td>
				        <td class=" ">{{ getCategoriaVendedor($vendedor->categoria) }}</td>
				        <td class=" ">{{ priceFormat($vendedor->percentual_comissao, false) }}%</td>
				        <td class=" last">
				        	<ul>
				        		{!! linksDefault('vendedor', $vendedor->id) !!}
				        	</ul>
				        </td>
				      </tr>
				  	 @endforeach
				  @endif

				</tbody>
				</table>

		      	<div class="btn-toolbar right">
		      		
		      		<div class="col-md-4">
		      			<p> Total de <code>{!! $vendedores->total() !!}</code> registros</p>
		      		</div>
                    <div class="col-md-8">
                    	<span class='right'>
                       		{!! $vendedores->links() !!}
                   		</span>
                    </div>
		      		
				</div>
		     
		    

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection