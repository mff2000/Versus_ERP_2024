
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Armazéns
	        <small>
	            Listagem de Registros
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('armazem/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Armazéns Cadastrados</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('armazem', [
				  		'descricao'=>'text'
				  	], $filtros)
			  	!!}

			</div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th>
			      <input type="checkbox" id="check-all" class="flat">
			    </th>
			    <th class="column-title">ID </th>
			    <th class="column-title">Código </th>
			    <th class="column-title">Descrição </th>
			    <th class="column-title">Ativo </th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($armazens) > 0)
			  	@foreach ($armazens as $armazem)
			      <tr class="even pointer">
			        <td class="a-center ">
			          <input type="checkbox" class="flat" name="table_records">
			        </td>
			        <td class=" ">{{ $armazem->id }} </td>
			        <td class=" ">{{ $armazem->codigo }} </td>
			        <td class=" ">{{ $armazem->descricao }} </i></td>
			        <td class=" ">{!! ($armazem->ativo == 1) ? 'SIM' : 'NÃO' !!}</td>

			        <td class=" last">
			        	<ul>
				        	{!! linksDefault('armazem', $armazem->id) !!}
				        </ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $armazens->total() !!}</code> registros</p>
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $armazens->links() !!}
               		</span>
                </div>
	      		
			</div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection