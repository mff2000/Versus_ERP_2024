
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Contas Bancárias
	        <small>
	            Cadastro de Contas
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('banco/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Contas Cadastradas</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! getFilter('bancos', [
		    		'descricao'=>'text',
		    	], $filtros, $orderBy)!!}

		    </div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th>
			      <input type="checkbox" id="check-all" class="flat">
			    </th>
			    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!}</th>
			    <th class="column-title">Conta </th>
			    <th class="column-title">{!! getTitleColumn('Limite', 'limite', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Saldo', 'saldo_atual', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Descrição', 'descricao', $orderBy) !!} </th>
			    <th class="column-title">Gerente </th>
			    <th class="column-title no-link last" width="90">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($bancos) > 0)
			  	@foreach ($bancos as $banco)
			      <tr class="even pointer">
			        <td class="a-center ">
			          <input type="checkbox" class="flat" name="table_records">
			        </td>
			        <td class=" ">{{ $banco->id }} </td>
			        <td class=" ">{{ $banco->codigo . " " .$banco->agencia . "-" .$banco->dv_agencia. "  " .$banco->numero_conta. "-" .$banco->dv_conta }} </td>
			        <td class=" ">{{ priceFormat($banco->limite) }} </i></td>
			        <td class=" "><span class="label {!! ($banco->saldo_atual>=0) ? 'label-primary' : 'label-danger' !!}">{{ priceFormat($banco->saldo_atual) }}</span></td>
			        <td class=" ">{{ $banco->descricao }}</td>
			        <td class=" ">{{ $banco->nome_gerente }}</td>

			        <td class=" last">
			        	<ul>
			        		{!! linksDefault('banco', $banco->id) !!}
			        	</ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $bancos->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $bancos->links() !!}
               		</span>
                </div>
	      		
			</div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection