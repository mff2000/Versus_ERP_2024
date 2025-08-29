<div class="form-group">

	<div class="col-md-12 col-sm-12 col-xs-12 item">
      <label for="tipo_pessoa" class="control-label">Tabela de Preço:</label>
      {!! Form::select('tabela_preco_id', $tabelas, isset($favorecido) ? $favorecido->tabela_preco_id : old('tabela_preco_id'), array('class'=>'form-control', 'id' => 'tabela_preco_id')) !!}
  	</div>

</div>

<div class="form-group">

  	@if(isset($favorecido->tabela))

  	<div class="col-md-12 col-sm-12 col-xs-12 item">
  		<div class="x_panel">
          
          <div class="x_title">
            <h2>Produtos<small></small></h2>
            <div class="clearfix"></div>
          </div>

          <div class="x_content collaped">

            <table class="table table-striped">
            <thead>
              <tr class="headings">
                <th class="column-title">ID </th>
                <th class="column-title">Descrição </th>
                <th class="column-title">Unidade</th>
                <th class="column-title">Grupo</th>
                <th class="column-title">Preço</th>
                <th class="column-title">Desconto do Cliente(%)</th>
              </tr>
            </thead>

            <tbody>
				@foreach ($favorecido->tabela->produtos as $produto)

					@foreach ($favorecido->descontos as $desconto)
						@if($desconto->pivot->produto_id == $produto->id)
							<?php $percentual = $desconto->pivot->percentual ?>
						@endif
					@endforeach
					<tr class="even pointer">
						<td class=" ">{{ $produto->id }} </td>
						<td class=" ">{{ $produto->descricao }} </i></td>
						<td class=" ">{{ $produto->unidade->descricao }}</i></td>
						<td class=" ">{{ $produto->grupo->descricao }}</i></td>
						<td class=" ">{{ priceFormat($produto->pivot->preco) }}</i></td>
						<td class="" width="20%">
						  <input id="produto_{{$produto->id}}" class="form-control currency" name="produto[{{$produto->id}}]" value="{{ (isset($percentual)) ? $percentual : 0 }}"  type="text" />
						</td>
					</tr>
                	
                @endforeach
              
            </tbody>
            </table>

          </div>
        
        </div>

    </div>
  	@endif

</div>