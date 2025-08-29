
@extends('layouts.app')

@section('content')

<div class="page-title">
	<div class="title_left">
	  	<h3>
			Contratos de Fornecimento
	        <small>
	            Listagem
	        </small>
	    </h3>
	</div>

	<div class="title_right">
		
	  <div class="pull-right">

	  	<a class="btn btn-sm btn-primary" href="{{ url('contratoFornecimento/create') }}"><i class="fa fa-plus-square"></i> Incluir Registro</a>

	  </div>

	</div>

</div>
   
<div class="clearfix"></div>

<div class="row">

	@include('flash::message')

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Contratos de Fornecimento</h2>

				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link"><i class="fa fa-chevron-down"></i> Filtros</a>
					</li>
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <div class="x_content" style="display:none;">

		    	{!! 
				  	getFilter('contrato_fornecimento', [
				  		'favorecido_id'=>['select', $favorecidos ],
				  		'data_vigencia_inicio'=>'data',
				  		'data_vigencia_fim'=>'data',
				  		'historico'=>'text'
				  	], $filtros)
			  	!!}

		    </div>

			<table class="table table-striped responsive-utilities jambo_table bulk_action">
			<thead>
			  <tr class="headings">
			    <th class="column-title">ID </th>
			    <th class="column-title">Descrição </th>
			    <th class="column-title">Contratante</th>
			    <th class="column-title">Etapa </th>
			    <th class="column-title">Vigência</th>
			    <th class="column-title">Valor</th>
			    <th class="column-title">Ativo </th>
			    <th class="column-title no-link last">
			    	<span class="nobr">Ação</span>
			    </th>
			  </tr>
			</thead>

			<tbody>
			  
			  @if (count($contratos) > 0)
			  	@foreach ($contratos as $contrato)
			      <tr class="even pointer {{ (dataVencido($contrato->data_vigencia_fim)) ? 'alert alert-danger': ''  }}" >
			        <td class=" ">{{ $contrato->id }} </td>
			        <td class=" ">{{ $contrato->descricao }} </td>
			        <td class=" ">{{ $contrato->favorecido->nome_fantasia }} </td>			        
			        <td class=" ">{{ getEtapasComercial($contrato->etapa) }} </td>
			        <td class=" ">{{ convertDatePt($contrato->data_vigencia_inicio) }} à {{ convertDatePt($contrato->data_vigencia_fim) }}</td>
			        <td class=" ">{{ priceFormat($contrato->valor) }} </td>
			        <td class=" "><span class="label {!! ($contrato->ativo==1) ? 'label-primary' : 'label-danger' !!}">{{ ($contrato->ativo==1) ? 'SIM':'NÃO' }}</span></td>
			        <td class=" last">
			        	<ul>
			        		{!! linksDefault('contratoFornecimento', $contrato->id, true, true) !!}
			        	</ul>
			        </td>
			      </tr>
			  	 @endforeach
			  @endif

			</tbody>
			</table>

	      	<div class="btn-toolbar right">
	      		
	      		<div class="col-md-4">
	      			<p> Total de <code>{!! $contratos->total() !!}</code> registros</p>
	      		</div>
                <div class="col-md-8">
                	<span class='right'>
                   		{!! $contratos->links() !!}
               		</span>
                </div>
	      		
			</div>
		     
	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection