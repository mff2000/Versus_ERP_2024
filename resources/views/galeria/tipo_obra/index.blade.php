
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Tipos de Obra
	        <small>
	            Listagem de Registros
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('galeria/tipo_obra/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Tipos de Obras Cadastradas</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('descontos', [
				  		'name'=>'text'
				  	], $filtros, $orderBy)
			  	!!}

			</div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Nome', 'nome', $orderBy) !!} </th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($tiposObra) > 0)
			  	@foreach ($tiposObra as $tipo)
			      <tr class="even pointer">
			        <td class=" ">{{ $tipo->id }} </td>
			        <td class=" ">{{ $tipo->nome }} </i></td>
			        <td class=" last">
			        	<ul>
				        	{!! linksDefault('galeria/tipo_obra', $tipo->id) !!}
				        </ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $tiposObra->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $tiposObra->links() !!}
               		</span>
                </div>
	      		
			</div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection