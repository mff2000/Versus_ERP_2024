
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Correções Financeiras
	        <small>
	            Listagem de Registros
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('correcaofinanceira/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Correções Financeiras Cadastradas</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

    		  	{!! 
				  	getFilter('correcao_financeiras', [
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
			    <th class="column-title">{!! getTitleColumn('Descrição', 'descricao', $orderBy) !!} </th>
			    <th class="column-title">Plano de Conta </th>
			    <th class="column-title">{!! getTitleColumn('Alíquota Juros', 'aliquota_juros', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Período Juros', 'periodo_juros', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Alíquota Multa', 'aliquota_multa', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Período Multa', 'periodo_multa', $orderBy) !!} </th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($correcoes) > 0)
			  	@foreach ($correcoes as $correcao)
			      <tr class="even pointer">
			        <td class="a-center ">
			          <input type="checkbox" class="flat" name="table_records">
			        </td>
			        <td class=" ">{{ $correcao->id }} </td>
			        <td class=" ">{{ $correcao->descricao }} </i></td>
			        <td class=" ">{{ $correcao->plano_conta->descricao }}</td>
			        <td class=" ">{{ $correcao->aliquota_juros }} </i></td>
			        <td class=" ">{!! getPeriodoAliquota($correcao->periodo_juros) !!} </i></td>
			        <td class=" ">{{ $correcao->aliquota_multa }} </i></td>
			        <td class=" ">{!! getPeriodoAliquota($correcao->periodo_multa) !!} </i></td>
			        <td class=" last">
			        	<ul>
			        		{!! linksDefault('correcaofinanceira', $correcao->id) !!}
			        	</ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $correcoes->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $correcoes->links() !!}
               		</span>
                </div>
	      		
			</div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection