
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Fluxo de Caixa <small>Opções de Filtros.</small>
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
		      
				<h2>Parâmetros do Relátorio</h2>

				<div class="clearfix"></div>

		    </div>

		    <form id="conta-form" class="form-horizontal form-label-left mode2" target='_blank' method="POST" action="{{ url('financeiro/relatorio/fluxo_caixa') }}" novalidate>
          	
            {{ csrf_field() }}

            <div class="x_content">

                  <div class="form-group">

                      <label for="nome_fantasia" class="control-label col-md-3 col-sm-3 col-xs-12">Tipo de Período:</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                          
                          <div class="radio-inline">
                            <label>
                              <input type="radio" class="flat" checked="true" name="periodo" value="1"> Diário
                            </label>
                          </div>
                          
                          <div class="radio-inline">
                            <label>
                              <input type="radio" class="flat" name="periodo" value="2"> Semanal
                            </label>
                          </div>

                          <div class="radio-inline">
                            <label>
                              <input type="radio" class="flat" name="periodo" value="3"> Quinzenal
                            </label>
                          </div>

                          <div class="radio-inline">
                            <label>
                              <input type="radio" class="flat" name="periodo" value="4"> Mensal
                            </label>
                          </div>
                      </div>

                  </div>

                  <div class="form-group">
                    
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Orientação:</label>
                    
                    <div class="col-md-2 col-sm-2 col-xs-12">
                      
                      <div class="checkbox">
                        <label class="control-label">
                        <input type="radio" class="flat" name="orientacao" id="orientacaoV" value="V" checked="" required /> Retrado</label>
                      </div>
                      <div class="checkbox">
                        <label class="control-label">
                        <input type="radio" class="flat" name="orientacao" id="orientacaoH" value="H" /> Paisagem</label>
                      </div>

                    </div>

                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Ordenação:</label>
                    
                    <div class="col-md-2 col-sm-2 col-xs-12">
                      <div class="checkbox">
                        <label class="control-label">
                        <input type="radio" class="flat" name="orderByDirecion" id="orientacaoV" value="ASC" checked="" required /> Ascendente</label>
                      </div>
                      <div class="checkbox">
                        <label class="control-label">
                        <input type="radio" class="flat" name="orderByDirecion" id="orientacaoH" value="DESC" /> Descendente</label>
                      </div>
                    </div>

                    <label class="control-label col-md-1 col-sm-1 col-xs-12">Imprimir:</label>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                      <div class="checkbox">
                          <label class="control-label">
                          <input type="radio" class="flat" name="printIn" id="orientacaoV" value="PDF" /> PDF</label>
                      </div>
                      <div class="checkbox">
                        <label class="control-label">
                        <input type="radio" class="flat" name="printIn" id="orientacaoH" value="SCREEN" checked="" required  /> Na Tela</label>
                      </div>
                    </div>

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