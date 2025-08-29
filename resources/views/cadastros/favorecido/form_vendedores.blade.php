@if(isset($vendedores))

    <div class="form-group">
        
        <div class="x_panel">
          
          <div class="x_title">
            <h2>Lista de Vendedores<small></small></h2>
            <div class="clearfix"></div>
          </div>

          <div class="x_content collaped">

            <table class="table table-striped">
            <thead>
              <tr class="headings">
              	<th>
                  <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" id="check-all" class="flat" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
                </th>
                <th class="column-title">ID </th>
                <th class="column-title">Nome Empresarial </th>
                <th class="column-title">Nome Fantasia</th>
                <th class="column-title">Comissão Cadastro</th>
                <th class="column-title">Comissão Cliente </th>
              </tr>
            </thead>

            <tbody>
                
				@foreach ($vendedores as $vendedor)

					<?php $checked = $comissao = 0; ?>
        	@if(isset($favorecido) && count($favorecido->vendedores))
        		@foreach ($favorecido->vendedores as $v)
        			@if($vendedor->id == $v->id)
        				<?php $comissao = $v->pivot->comissao ?>
        				<?php $checked = 1 ?>
        			@endif
        		@endforeach
        	@endif
					<tr class="even pointer">
						<td class="a-center ">
                            <input type="checkbox" class="flat" {{($checked == 1) ? 'checked=checked': '' }} name="vendedor_id[{{$vendedor->id}}]" style="position: relative; opacity: 1;">
                        </td>
						<td class=" ">{{ $vendedor->id }} </td>
						<td class=" ">{{ $vendedor->nome_empresarial }} </i></td>
						<td class=" ">{{ $vendedor->nome_fantasia }}</i></td>
						<td class=" ">{{ $vendedor->percentual_comissao }}</i></td>
						<td class="" width="20%">
						  <input id="vendedor_{{$vendedor->id}}" class="form-control currency" name="vendedor_comissao[{{$vendedor->id}}]" value="{{ $comissao }}"  type="text" />
						</td>
					</tr>
                	
                @endforeach
              
            </tbody>
            </table>

          </div>
        
        </div>

    </div>
@endif
 