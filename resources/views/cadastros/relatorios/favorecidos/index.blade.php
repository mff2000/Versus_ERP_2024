
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Favorecidos <small>Opções de Filtros.</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right">
          
      </div>

    </div>
</div>


<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">
	  
		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Gerar Relátorio</h2>

				<div class="clearfix"></div>

		    </div>

		    <form id="conta-form" class="form-horizontal form-label-left mode2" target='_blank' method="POST" action="{{ url('cadastro/relatorio/favorecido') }}" novalidate>
          	
            {{ csrf_field() }}

            <div class="x_content">

            		  <div class="form-group">

                      <label for="created_at" class="control-label col-md-3 col-sm-3 col-xs-12">Data Cadastro:</label>
                      <div class="col-md-3 col-sm-3 col-xs-12 has-feedback">
                          <input id="created_at" class="form-control has-feedback-left date" name="filtros[created_at_ini]" value="" type="text" />
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      </div>
                      <div class="col-md-3 col-sm-3 col-xs-12 has-feedback">
                          <input id="created_at" class="form-control has-feedback-left date" name="filtros[created_at_end]" value="" type="text" />
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      </div>

                  </div>

                  <div class="form-group">
                      <label for="nome_empresarial" class="control-label col-md-3 col-sm-3 col-xs-12">Razão Social Contém:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="nome_empresarial" class="form-control" name="filtros[nome_empresarial]" value="" type="text" />
                      </div>
                  </div>

                  <div class="form-group">

                      <label for="nome_fantasia" class="control-label col-md-3 col-sm-3 col-xs-12">Nome Fantasia Contém:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="nome_fantasia" class="form-control" name="filtros[nome_fantasia]" value="" type="text" />
                      </div>

                  </div>

                  <div class="form-group">

                      <label for="nome_fantasia" class="control-label col-md-3 col-sm-3 col-xs-12">Exibir:</label>
                      <div class="col-md-2 col-sm-2 col-xs-12">
                          @foreach($colunas as $coluna)
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" class="flat" checked="checked" name="colunas[{!!$coluna!!}]"> {!!trans('versus.'.$tabela.'.'.$coluna)!!}
                            </label>
                          </div>
                          @endforeach
                      </div>

                      <label for="nome_fantasia" class="control-label col-md-2 col-sm-2 col-xs-12">Ordenar Por:</label>
                      <div class="col-md-2 col-sm-2 col-xs-12">
                          @foreach($colunas as $key => $coluna)
                          <div class="checkbox">
                            <label>
                              <input type="radio" class="flat" {{($key == 0) ? 'checked="checked"': '' }} value="{!!$coluna!!}" name="orderByColumn"> {!!trans('versus.'.$tabela.'.'.$coluna)!!}
                            </label>
                          </div>
                          @endforeach
                      </div>

                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Orientação:</label>
                    <p class="col-md-2 col-sm-2 col-xs-12">
                      <label class="control-label">
                      <input type="radio" class="flat" name="orientacao" id="orientacaoV" value="V" checked="" required /> Retrado</label>
                      <label class="control-label">
                      <input type="radio" class="flat" name="orientacao" id="orientacaoH" value="H" /> Paisagem</label>
                    </p>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Ordenação:</label>
                    <p class="col-md-2 col-sm-2 col-xs-12">
                      <label class="control-label">
                      <input type="radio" class="flat" name="orderByDirecion" id="orientacaoV" value="ASC" checked="" required /> Ascendente</label>
                      <label class="control-label">
                      <input type="radio" class="flat" name="orderByDirecion" id="orientacaoH" value="DESC" /> Descendente</label>
                    </p>
                  </div>

                  <div class="form-group">
                    <div class="col-md-3 col-md-offset-9">
                      <span class="pull-right">
                        <button id="send" type="submit" class="btn btn-default"><i class="fa fa-file-text"></i> Imprimir</button>
                      </span>
                    </div>
                </div>

            </div>

		   	</form>
        </div>
    </div>
</div><!-- /#row -->



@endsection