
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Formas Financeiras
	        <small>
	            Listagem de Registros
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('formafinanceira/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Formas Financeiras Cadastradas</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('forma_financeiras', [
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
			    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!}</th>
			    <th class="column-title">Código </th>
			    <th class="column-title">{!! getTitleColumn('Descrição', 'descricao', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Liquida', 'liquida', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Ativo', 'ativo', $orderBy) !!} </th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($formas) > 0)
			  	@foreach ($formas as $forma)
			      <tr class="even pointer">
			        <td class="a-center ">
			          <input type="checkbox" class="flat" name="table_records">
			        </td>
			        <td class=" ">{{ $forma->id }} </td>
			        <td class=" ">{{ $forma->codigo }} </td>
			        <td class=" ">{{ $forma->descricao }} </i></td>
			        <td class=" "><span class="label {!! ($forma->liquida == 'S') ? 'label-primary' : 'label-danger' !!}">{!! ($forma->liquida == 'S') ? 'SIM' : 'NÃO' !!}</span></td>
			        <td class=" ">{!! ($forma->ativo == 1) ? 'SIM' : 'NÃO' !!}</td>

			        <td class=" last">
			        	<ul>
				        	{!! linksDefault('formafinanceira', $forma->id) !!}
				        </ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $formas->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $formas->links() !!}
               		</span>
                </div>
	      		
			</div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection