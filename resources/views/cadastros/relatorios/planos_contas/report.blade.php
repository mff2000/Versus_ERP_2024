@extends('layouts.report')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
        	VersusERP
        	<small class="logo">Relação de Planos de Contas</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right" style="text-align:right">
          <h6>Versus ERP - Administração Empresarial <br /><?=date('d/m/Y H:i:s')?></h6>
      </div>

    </div>
</div>
   
<div class="clearfix"></div>

<div class="row">

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_content">

				<table class="table table-striped">
				<thead>
				  <tr class="headings">

				    <th class="column-title">ID </th>

				    @if(isset($colunas['codigo']))
				    <th class="column-title">{{trans('versus.planos_contas.codigo')}}</th>
				    @endif

				    @if(isset($colunas['descricao']))
				    <th class="column-title">{{trans('versus.planos_contas.descricao')}}</th>
				    @endif

				    @if(isset($colunas['classe']))
				    <th class="column-title">{{trans('versus.planos_contas.classe')}}</th>
				    @endif

				    @if(isset($colunas['natureza']))
				    <th class="column-title">{{trans('versus.planos_contas.natureza')}}</th>
				    @endif

				  </tr>
				</thead>
				
				<tbody>
				  
				  @if (count($planos_contas) > 0)
				  	@foreach ($planos_contas as $plano)
				      <tr class="even pointer">
				        
				        <td class=" ">{{ $plano->id }} </td>
				        
				        @if(isset($colunas['codigo']))
				        <td class=" ">{{ $plano->codigo }}</td>
						@endif

				        @if(isset($colunas['descricao']))
				        <td class=" ">{{ $plano->descricao }}</td>
				        @endif

				        @if(isset($colunas['classe']))
				        <td class=" "> {{ ($plano->classe=='A') ? 'Analítica' : 'Sintetica' }}</td>
				        @endif

				        @if(isset($colunas['natureza']))
				        <td class=" "> {{ ($plano->natureza=='D') ? 'Devedora' : 'Credora' }}</td>
				        @endif

				      </tr>
				  	 @endforeach
				  @endif

				</tbody>
				</table>

		      	<div class="btn-toolbar right">
		      		
		      		<div class="col-md-4">
		      			<p> Total de <code>{!! count($planos_contas) !!}</code> registros</p>
		      		</div>
                    		      		
				</div>
		     
		    </div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection