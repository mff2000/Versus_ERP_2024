
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Obras
	        <small>
	            Cadastro de Obras
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('galeria/obra/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Obras Cadastradas</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! getFilter('galeria/obra', [
		    		'titulo'=>'text',
		    	], $filtros, $orderBy)!!}

		    </div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th>
			      <input type="checkbox" id="check-all" class="flat">
			    </th>
			    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Artista', 'artista_id', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Título', 'titulo', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Tipo de Obra', 'tipo_obra_id', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Técnica', 'tecnica_id', $orderBy) !!} </th>
			    <th class="column-title">Imagem</th>
			    <th class="column-title">Estoque </th>
			    <th class="column-title no-link last" width="90">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($obras) > 0)
			  	@foreach ($obras as $obra)
			      <tr class="even pointer">
			        <td class="a-center ">
			          <input type="checkbox" class="flat" name="table_records">
			        </td>
			        <td class=" ">{{ $obra->id }} </td>
			        <td class=" ">{{ $obra->artista->nome_empresarial }} </td>
			        <td class=" ">{{ $obra->titulo }} </i></td>
			        <td class=" ">{{ $obra->tipo_obra->nome }}</td>
			        <td class=" ">{{ $obra->tecnica->nome }}</td>
			        <td class=" ">
			        	@if( isset($obra->foto) && !empty($obra->foto) )
				    	<img src="{{ url($obra->foto) }}" height="90" class="margin image" />
				    	@endif
			        </td>
			        <td class=" ">{{($obra->estoque)}}</td>
			        <td class=" last">
			        	<ul>
			        		{!! linksDefault('galeria/obra', $obra->id) !!}
			        	</ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $obras->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $obras->links() !!}
               		</span>
                </div>
	      		
			</div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection