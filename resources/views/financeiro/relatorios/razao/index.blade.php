
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Relatório de Razão <small>Opções de Filtros.</small>
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

		    <form id="conta-form" class="form-horizontal form-label-left mode2" target='_blank' method="POST" action="{{ url('financeiro/relatorio/razao') }}" novalidate>
          	
            {{ csrf_field() }}

            <div class="x_content">

            		  <div class="form-group">

                      <label for="data_competencia_ini" class="control-label col-md-3 col-sm-3 col-xs-12">Data Competência:</label>
                      <div class="col-md-3 col-sm-3 col-xs-12 has-feedback">
                          <input id="data_competencia_ini" class="form-control has-feedback-left date" name="filtros[data_competencia_ini]" value="" type="text" />
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      </div>
                      <div class="col-md-3 col-sm-3 col-xs-12 has-feedback">
                          <input id="data_competencia_end" class="form-control has-feedback-left date" name="filtros[data_competencia_end]" value="" type="text" />
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      </div>

                  </div>

                  <div class="form-group">

                      <label for="data_vencimento_ini" class="control-label col-md-3 col-sm-3 col-xs-12">Data Vencimento:</label>
                      <div class="col-md-3 col-sm-3 col-xs-12 has-feedback">
                          <input id="data_vencimento_ini" class="form-control has-feedback-left date" name="filtros[data_vencimento_ini]" value="" type="text" />
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      </div>
                      <div class="col-md-3 col-sm-3 col-xs-12 has-feedback">
                          <input id="data_vencimento_end" class="form-control has-feedback-left date" name="filtros[data_vencimento_end]" value="" type="text" />
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      </div>

                  </div>

                  <div class="form-group">

                      <label for="data_baixa_ini" class="control-label col-md-3 col-sm-3 col-xs-12">Data Baixa:</label>
                      <div class="col-md-3 col-sm-3 col-xs-12 has-feedback">
                          <input id="data_baixa_ini" class="form-control has-feedback-left date" name="filtros[data_baixa_ini]" value="" type="text" />
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      </div>
                      <div class="col-md-3 col-sm-3 col-xs-12 has-feedback">
                          <input id="data_baixa_end" class="form-control has-feedback-left date" name="filtros[data_baixa_end]" value="" type="text" />
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      </div>

                  </div>

                  <div class="form-group">

                      <label for="nome_fantasia" class="control-label col-md-3 col-sm-3 col-xs-12">Status:</label>
                      <div class="col-md-4 col-sm-4 col-xs-12">
                          <ul class="nav navbar-left panel_toolbox">
                            <li>
                              <input type="checkbox" class="flat" name="filtros[agendamento_liquidados]" value="L" checked="checked">
                              <span class="label label-success" style="color:#fff">Liquidado</span>
                            </li>
                            <li>
                              <input type="checkbox" class="flat" name="filtros[agendamento_parcial]" value="P" checked="checked">
                              <span class="label label-warning" style="color:#fff">Liquidado Parcial</span>
                            </li>
                            <li>
                              <input type="checkbox" class="flat" name="filtros[agendamento_aberto]" value="A" checked="checked">
                              <span class="label label-danger" style="color:#fff">Aberto</span>
                            </li>
                            <li style="display: none;">
                              <input type="checkbox" class="flat" name="filtros[agendamento_bordero]" value="B">
                              <span class="label label-info" style="color:#fff">Borderô</span>
                            </li>
                          </ul>
                      </div>

                  </div>

                  <div class="form-group">

                      <label for="nome_fantasia" class="control-label col-md-3 col-sm-3 col-xs-12">Ordenar Por:</label>
                      <div class="col-md-4 col-sm-4 col-xs-12">
                          
                          <div class="checkbox">
                            <label>
                              <input type="radio" class="flat" checked="checked" value="plano_conta_id" name="orderByColumn"> Plano de Conta
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="radio" class="flat" value="centro_resultado_id" name="orderByColumn"> Centro de Resultado
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="radio" class="flat" value="projeto_id" name="orderByColumn"> Projeto
                            </label>
                          </div>

                      </div>

                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Orientação:</label>
                    <p class="col-md-2 col-sm-2 col-xs-12">
                      <label class="control-label">
                      <input type="radio" class="flat" name="orientacao" id="orientacaoV" value="V" /> Retrado</label>
                      <label class="control-label">
                      <input type="radio" class="flat" name="orientacao" id="orientacaoH" value="H" checked="" required/> Paisagem</label>
                    </p>
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Ordenação:</label>
                    <p class="col-md-2 col-sm-2 col-xs-12">
                      <label class="control-label">
                      <input type="radio" class="flat" name="orderByDirecion" id="orientacaoV" value="ASC" checked="" required /> Ascendente</label>
                      <label class="control-label">
                      <input type="radio" class="flat" name="orderByDirecion" id="orientacaoH" value="DESC" /> Descendente</label>
                    </p>
                    <label class="control-label col-md-1 col-sm-1 col-xs-12">Imprimir:</label>
                    <p class="col-md-2 col-sm-2 col-xs-12">
                      <label class="control-label">
                      <input type="radio" class="flat" name="printIn" id="orientacaoV" value="PDF" /> PDF</label>
                      <label class="control-label">
                      <input type="radio" class="flat" name="printIn" id="orientacaoH" value="SCREEN" checked="" required  /> Na Tela</label>
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