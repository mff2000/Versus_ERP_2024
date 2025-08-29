
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
	        Lançamentos Não Financeiros
	        <small>
	            Listagem de Registros
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('lancamento_gerencial/create') }}"><i class="fa fa-plus-square"></i> Incluir Lançamento</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Lançamentos Não Financeiros</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('lancamentos_gerenciais', [
				  		'data_lancamento'=>'data',
				  		'favorecido_id'=>['select', $favorecidos],
				  		'historico'=>'text'
				  	], $filtros, $orderBy)
			  	!!}

			</div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Data Lanç.', 'data_lancamento', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Histórico', 'historico', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Favorecido', 'favorecidos.nome_fantasia', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Valor', 'valor_lancamento', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Número\Parc.', 'numero_titulo', $orderBy) !!}</th>
			    <th class="column-title">Plano de Conta </th>
			    <th class="column-title">Centro de Resultado </th>
			    <th class="column-title">Projeto </th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  <?php $soma = 0 ?>
			  @if (count($lancamentos) > 0)
			  	@foreach ($lancamentos as $lancamento)

			  		<?php $soma += $lancamento->valor_lancamento; ?>

			      	<tr class="even pointer">
				        <td class=" ">{{ $lancamento->id }} </td>
				        <td class=" ">{{ convertDatePt($lancamento->data_lancamento) }} </td>
				        <td class=" ">{{ $lancamento->historico }} </td>
				        <td class=" ">{{ $lancamento->favorecido->nome_fantasia }} </td>
				        <td class="">{{ priceFormat($lancamento->valor_lancamento) }} </td>
				        <td class=" ">{{ $lancamento->numero_titulo }} - {{ $lancamento->numero_parcela }} </td>
				        <td> 
				        	<button type="button" class="btn btn-danger btn-xs">{{ $lancamento->plano_conta_debito->descricao }}</button>
				        	<i class="fa fa-long-arrow-right"></i>
				        	<button type="button" class="btn btn-success btn-xs">{{ $lancamento->plano_conta_credito->descricao }}</button>
				        </td>
				        <td>
				        	<button type="button" class="btn btn-danger btn-xs">{{ $lancamento->centro_resultado_debito->descricao }}</button>
				        	<i class="fa fa-long-arrow-right"></i>
				        	<button type="button" class="btn btn-success btn-xs">{{ $lancamento->centro_resultado_credito->descricao }} </button>				        	
				       </td>
				        <td>
				        	<button type="button" class="btn btn-danger btn-xs">{{ $lancamento->projeto_debito->descricao }}</button>
				        	<i class="fa fa-long-arrow-right"></i>
				        	<button type="button" class="btn btn-success btn-xs">{{ $lancamento->projeto_credito->descricao }} </button>				        	
				        </td>
				        <td class=" last">
				        	<ul>
				        		{!! linksDefault('lancamento_gerencial', $lancamento->id, true, true) !!}
				        	</ul>
				        </td>
			      	</tr>
			  	 @endforeach
			  @endif

			</tbody>
			<tfoot>
				<tr>
					<th colspan="4"></th>
					<th>{{priceFormat($soma)}}</th>
					<th colspan="5"></th>
				</tr>
			</tfoot>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $lancamentos->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $lancamentos->links() !!}
               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection