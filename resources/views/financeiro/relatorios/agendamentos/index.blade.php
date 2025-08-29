
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Relatório de Agendamentos <small>Opções de Filtros.</small>
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

		    <form id="conta-form" class="form-horizontal form-label-left mode2" target='_blank' method="POST" action="{{ url('financeiro/relatorio/agendamento') }}" novalidate>
          	
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
                      
                        <label for="favorecido_id" class="control-label col-md-3 col-sm-3 col-xs-12">Favorecido:</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 has-feedback">
                            {!! Form::select('filtros[favorecido_id][]', $favorecidos, old('favorecido_id'), array('class'=>'form-control', 'id' => 'favorecido_id', 'multiple'=>'multiple ')) !!}
                        </div>
                  </div>

                  <div class="form-group">
                      
                        <label for="favorecido_id" class="control-label col-md-3 col-sm-3 col-xs-12">Planos de Conta:</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 has-feedback">
                            @include('common/plano_projeto_centro_tree', ['contas'=> $planosContas, 'title'=>'Planos de Contas', 'table'=>'plano_conta'])
                        </div>
                  </div>

                  <div class="form-group">
                      
                        <label for="favorecido_id" class="control-label col-md-3 col-sm-3 col-xs-12">Centros de Resultado:</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 has-feedback">
                            @include('common/plano_projeto_centro_tree', ['contas'=> $centrosResultados, 'title'=>'Centros de Resultados', 'table'=>'centro_resultado'])
                        </div>
                  </div>

                  <div class="form-group">
                      
                        <label for="favorecido_id" class="control-label col-md-3 col-sm-3 col-xs-12">Projetos:</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 has-feedback">
                            @include('common/plano_projeto_centro_tree', ['contas'=> $projetos, 'title'=>'Projetos', 'table'=>'projeto'])
                        </div>
                  </div>

                  <div class="form-group">

                      <label for="nome_fantasia" class="control-label col-md-3 col-sm-3 col-xs-12">Status:</label>
                      <div class="col-md-4 col-sm-4 col-xs-12">
                          <ul class="nav navbar-left panel_toolbox">
                            <li>
                              <input type="checkbox" class="flat" name="filtros[agendamento_liquidados]" value="L">
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
                            <li>
                              <input type="checkbox" class="flat" name="filtros[agendamento_bordero]" value="B">
                              <span class="label label-info" style="color:#fff">Borderô</span>
                            </li>
                          </ul>
                      </div>

                  </div>

                  <div class="form-group">

                      <label for="nome_fantasia" class="control-label col-md-3 col-sm-3 col-xs-12">Tipo:</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                          <ul class="nav navbar-left panel_toolbox">
                            <li>
                              <input type="checkbox" class="flat" name="filtros[tipo_pagamento]" value="PGT" checked="checked">
                              <span class="label label-success" style="color:#fff">Pagamento</span>
                            </li>
                            <li>
                              <input type="checkbox" class="flat" name="filtros[tipo_recebimento]" value="RCT" checked="checked">
                              <span class="label label-warning" style="color:#fff">Recebimento</span>
                            </li>
                          </ul>
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