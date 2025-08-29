@extends('layouts.report')

@section('content')

<style>

.class_0 {
	background:#f3f3f3 !important; font-weight:bold;
}

.class_0 > td {  font-size: 16px !important; text-transform: uppercase; }

.class_1 { background:#f9f9f9 ; }
.class_1 > td {  font-size: 16px !important; padding-left: 32px !important; }

.class_2 { background:#f2f2f2; }
.class_2 > td {  font-size: 14px !important; padding-left: 48px !important; }

.class_3 { background:#ffffff; }
.class_3 > td {  font-size: 12px !important; padding-left: 72px !important; }

</style>

<div class="page-title">
    <div class="title_left">
        <h3>
        	VersusERP
        	<small class="logo">Demostrativo de Resultados</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right" style="text-align:right">
          <h6>Versus ERP - Administração Empresarial <br /><?=date('d/m/Y H:i:s')?></h6>
      </div>

    </div>
    <div style="float: left;width: 100%">
     {!!$filtrosDesc!!}
    </div>
</div>
   
<div class="clearfix"></div>

<div class="row">

	<div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_content">

				<table class="table table-striped table-hover">
				<thead class="pageheader">
				  <tr class="headings">

				    @if(isset($colunas['codigo']))
				    <th class="column-title">Código</th>
				    @endif

				    @if(isset($colunas['descricao']))
				    <th class="column-title">Descrição</th>
				    @endif

				    @if($orcado)
				    <th class="column-title">Orçando</th>
				    @endif
				    <th class="column-title">Total</th>
				    
				    @if($orcado)
				    <th class="column-title">Resultado</th>
				    @endif
				  </tr>
				</thead>
				
				<tbody>
				  <?php 
				  	$orcadoTotal = 0;
				  	$resultadoTotal = array_shift ($planosContas);
				  ?>
				  @if (count($planosContas) > 0)
				  	@foreach ($planosContas as $planosConta)
				  		@if(isset($planosConta['id']) && ($planosConta['total'] != 0 || ($orcado && $planosConta['orcado']!=0) ))
					  		<?php $count = substr_count($planosConta['codigo'], '.'); ?>

							<tr class="even pointer {{ ($count == 0) ? 'parent': '' }} class_{{$count}}">

							@if(isset($colunas['codigo']))
							<td class=" ">{{ $planosConta['codigo']	}}</td>
							@endif

							@if(isset($colunas['descricao']))
								@if($planosConta['classe'] != 'A')
									<td class=" ">{{ $planosConta['descricao'] }}</td>
								@else
									<td class=" ">
										<a href="{{ url('/financeiro/relatorio/demonstrativo_resultado/'.$planosConta['id'].'/?query='.$where) }}" target="_blank">
											{{ $planosConta['descricao'] }}
										</a>
									</td>
								@endif
							@endif

							@if($orcado && isset($planosConta['orcado']))
						    	<td class="column-title">
						    		@if($planosConta['natureza'] == 'C' && $planosConta['desconto'] == 'N')
										+
									@elseif($planosConta['natureza'] == 'D' && $planosConta['desconto'] == 'N')
										-
									@elseif($planosConta['natureza'] == 'C' && $planosConta['desconto'] == 'S')
										-
									@elseif($planosConta['natureza'] == 'C')
										+
									@elseif($planosConta['natureza'] == 'D')
										-
									@endif
									{{ priceFormat($planosConta['orcado']) }}
									<?php 
										if(isset($planosConta['childrens']) && $planosConta['childrens'] == 0)
											$orcadoTotal += $planosConta['orcado'];
									?>
						    	</td>
						    @endif

							<td class=" ">
								@if($planosConta['natureza'] == 'C' && $planosConta['desconto'] == 'N')
									+
								@elseif($planosConta['natureza'] == 'D' && $planosConta['desconto'] == 'N')
									-
								@elseif($planosConta['natureza'] == 'C' && $planosConta['desconto'] == 'S')
									-
								@elseif($planosConta['natureza'] == 'C')
									+
								@elseif($planosConta['natureza'] == 'D')
									-
								@endif
								{{ priceFormat($planosConta['total']) }}
							</td>
							@if($orcado && isset($planosConta['orcado']))
							<td>
								<?php

									if($planosConta['orcado'] > 0) {
										$resultado = round(($planosConta['total']*100)/$planosConta['orcado']).'%';
									} else {
										if($planosConta['total'] > 0)
											$resultado = round($planosConta['total']).'%';
										else
											$resultado = '0%';
									}
									echo $resultado;
								?>
							</td>
							@endif
							</tr>
						@endif
				  	 @endforeach
				  @endif

				</tbody>
				</table>
		     
		    </div>

	 	</div>
	
	</div>

	<div class="col-md-12 col-sm-12 col-xs-12">
		<ul class="stats-overview">
          <li style="{{($orcado) ? 'width:33%' : 'width:45%'}}">
            <span class="name"> Total de  registros </span>
            <span class="value text-success"> <code>{!! count($planosContas) !!}</code> </span>
          </li>
          @if($orcado) 
          <li style="width:33%">
            <span class="name"> Orçado </span>
            <span class="value text-success"> {{ priceFormat($orcadoTotal) }} </span>
          </li>
          @endif
          <li style="{{($orcado) ? 'width:33%' : 'width:45%'}}">
            <span class="name"> Resultado </span>
            <span class="value text-success"> {{ priceFormat($resultadoTotal) }} </span>
          </li>
        </ul>
	</div>

</div><!-- /#row -->

@endsection