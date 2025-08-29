
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Favorecidos
	        <small>
	            Cadastro de Favorecidos
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('favorecido/create') }}"><i class="fa fa-plus-square"></i> Incluir Favorecido</a>

	  	<a class="btn btn-sm btn-primary" href="{{ url('favorecido/exportar/excel') }}"><i class="fa fa-file-excel-o"></i></a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Favorecido Cadastrados</h2>

				<ul class="nav navbar-right panel_toolbox">
					
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
					
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none">

		    	{!! getFilter('favorecidos', [
		    		'nome_empresarial'=>'text',
		    		'nome_fantasia'=>'text',
		    		'cnpj'=>'cpf_cnpj'
		    	], $filtros, $orderBy)!!}

		    </div>
				<table class="table table-striped responsive-utilities jambo_table bulk_action">
				<thead>
				  <tr class="headings">
				    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!}</th>
				    <th class="column-title">{!! getTitleColumn('Razão Social', 'nome_empresarial', $orderBy) !!}</th>
				    <th class="column-title">{!! getTitleColumn('Nome Fantasia', 'nome_fantasia', $orderBy) !!}</th>
				    <th class="column-title">CNPJ/CPF </th>
  					@if(env('GALERIA', FALSE))
  						<th class="column-title">Tipo (Galeria) </th>
  					@endif
				    <th class="column-title">{!! getTitleColumn('Cadastro em ', 'created_at', $orderBy) !!}</th>
				    <th class="column-title no-link last" width="90">
				    	<span class="nobr">Ação</span>
				    </th>
				  </tr>
				</thead>

				<tbody>
				  
				  @if (count($favorecidos) > 0)
				  	@foreach ($favorecidos as $favorecido)
				      <tr class="even pointer">
				        <td class=" ">{{ $favorecido->id }} </td>
				        <td class=" ">{{ $favorecido->nome_empresarial }} </td>
				        <td class=" ">{{ $favorecido->nome_fantasia }} </i></td>
				        <td class=" "> {{ $favorecido->cnpj }} </td>
				        @if(env('GALERIA', FALSE))
	  						<td class="column-title">
	  							@if($favorecido->tipo_galeria)
	  							<?php $tipos = explode(",", $favorecido->tipo_galeria); ?>
	  								@foreach($tipos as $tipo)
	  									{{getTipoPessoaGaleria($tipo)." -"}}
	  								@endforeach
	  							@endif
	  						</td>
	  					@endif
				        <td class=" ">{{ date('d M Y', strtotime($favorecido->created_at)) }} </td>
				        
				        <td class=" last">
				        	<ul>
				        		{!! linksDefault('favorecido', $favorecido->id) !!}
				        	</ul>
				        </td>
				      </tr>
				  	 @endforeach
				  @endif

				</tbody>
				</table>

		      	<div class="btn-toolbar right">
		      		
		      		<div class="col-md-4">
		      			<p> Total de <code>{!! $favorecidos->total() !!}</code> registros</p>
		      			{!! getLinksPerPage($per_page) !!}
		      		</div>
                    <div class="col-md-8">
                    	<span class='right'>
                       		{!! $favorecidos->links() !!}
                   		</span>
                    </div>
		      		
				</div>
		     
		    

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection