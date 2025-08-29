@extends('layouts.report')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
        	VersusERP
        	<small class="logo">Relação de Favorecidos</small>
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
				    @if(isset($colunas['nome_empresarial']))
				    <th class="column-title">Razão Social</th>
				    @endif

				    @if(isset($colunas['nome_empresarial']))
				    <th class="column-title">Nome Fantasia</th>
				    @endif

				    @if(isset($colunas['nome_empresarial']))
				    <th class="column-title">CNPJ/CPF</th>
				    @endif

				    @if(isset($colunas['tel_fixo1']))
				    <th class="column-title">Telefone</th>
				    @endif

				    @if(isset($colunas['tel_movel1']))
				    <th class="column-title">Celular</th>
				    @endif

				    @if(isset($colunas['email_geral']))
				    <th class="column-title">E-mail Geral</th>
				    @endif

				     @if(isset($colunas['limite_credito']))
				    <th class="column-title">Limite de Crédito</th>
				    @endif

				     @if(isset($colunas['risco_credito']))
				    <th class="column-title">Risco de Crédito</th>
				    @endif

				    @if(isset($colunas['created_at']))
				    <th class="column-title">Cadastro</th>
				    @endif

				  </tr>
				</thead>
				
				<tbody>
				  
				  @if (count($favorecidos) > 0)
				  	@foreach ($favorecidos as $favorecido)
				      <tr class="even pointer">
				        
				        <td class=" ">{{ $favorecido->id }} </td>
				        
				        @if(isset($colunas['nome_empresarial']))
				        <td class=" ">{{ $favorecido->nome_empresarial }}</td>
						@endif

				        @if(isset($colunas['nome_fantasia']))
				        <td class=" ">{{ $favorecido->nome_fantasia }}</td>
				        @endif

				        @if(isset($colunas['cnpj']))
				        <td class=" "> {{ $favorecido->cnpj }}</td>
				        @endif

				        @if(isset($colunas['tel_fixo1']))
				        <td class=" "> {{ $favorecido->tel_fixo1 }}</td>
				        @endif

				        @if(isset($colunas['tel_movel1']))
				        <td class=" "> {{ $favorecido->tel_movel1 }}</td>
				        @endif

				        @if(isset($colunas['email_geral']))
				        <td class=" "> {{ $favorecido->email_geral }}</td>
				        @endif

				        @if(isset($colunas['limite_credito']))
				        <td class=" "> {{ priceFormat($favorecido->limite_credito) }}</td>
				        @endif

				        @if(isset($colunas['risco_credito']))
				        <td class=" "> {{ $favorecido->risco_credito }}</td>
				        @endif

				        @if(isset($colunas['created_at']))
				        <td class=" ">{{ date('d/m/Y', strtotime($favorecido->created_at)) }}</td>
				        @endif
				      </tr>
				  	 @endforeach
				  @endif

				</tbody>
				</table>

		      	<div class="btn-toolbar right">
		      		
		      		<div class="col-md-4">
		      			<p> Total de <code>{!! count($favorecidos) !!}</code> registros</p>
		      		</div>
                    		      		
				</div>
		     
		    </div>

	 	</div>
	
	</div>

</div><!-- /#row -->

@endsection