
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
			Lançamentos Orçamento
	        <small>
	            Lançamentos para previsão de orçamento
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('orcamento/create') }}"><i class="fa fa-plus-square"></i> Incluir Orçamento</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Lançamentos Orçamento</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('lancamentos_orcamento', [
				  		'tipo_movimento'=>['select', [''=>'', 'PGT'=>'Pagamento', 'RCT'=>'Recebimento'] ],
				  		'data_competencia'=>'data',
				  		'data_vencimento'=>'data',
				  		'historico'=>'text'
				  	], $filtros, $orderBy)
			  	!!}

		    </div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th class="column-title">{!! getTitleColumn('ID', 'id', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Histórico', 'historico', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Tipo', 'tipo_movimento', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Valor', 'valor_lancamento', $orderBy) !!} </th>
			    <th class="column-title">{!! getTitleColumn('Data Competência', 'data_competencia', $orderBy) !!}</th>
			    <th class="column-title">{!! getTitleColumn('Data Vencimento', 'data_vencimento', $orderBy) !!}</th>
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
			  @if (count($orcamentos) > 0)
			  	@foreach ($orcamentos as $orcamento)

			  		<?php $soma += $orcamento->valor_lancamento; ?>

			      	<tr class="even pointer">
				        <td class=" ">{{ $orcamento->id }} </td>
				        <td class=" ">{{ $orcamento->historico }} </td>
				        <td class=" "><span class="label {!! ($orcamento->tipo_movimento=='RCT') ? 'label-primary' : 'label-danger' !!}">{{ $orcamento->tipo_movimento }}</span></td>
				        <td class=" ">{{ priceFormat($orcamento->valor_lancamento) }} </td>
				        <td class=" ">{{ convertDatePt($orcamento->data_competencia) }} </td>
				        <td class=" ">{{ convertDatePt($orcamento->data_vencimento) }} </td>
				        
				        <td> 
				        	{{ $orcamento->plano_conta->descricao }}
				        </td>
				        <td>
				        	{{ $orcamento->centro_resultado->descricao }}
				       </td>
				        <td>
				        	{{ $orcamento->projeto->descricao }}
				        </td>
				        <td class=" last">
				        	<ul>
				        		{!! linksDefault('orcamento', $orcamento->id, true, true) !!}
				        	</ul>
				        </td>
			      	</tr>
			  	 @endforeach
			  @endif

			</tbody>
			<tfoot>
				<tr>
					<th colspan="3"></th>
					<th>{{priceFormat($soma)}}</th>
					<th colspan="6"></th>
				</tr>
			</tfoot>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $orcamentos->total() !!}</code> registros</p>
	      			{!! getLinksPerPage($per_page) !!}
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $orcamentos->links() !!}
               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection