
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Incluir Lançamento Orçamento <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right">
          
      </div>

    </div>
</div>


<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">

        @include('flash::message')
        @if(count($errors))
        <div class="alert alert-danger" id="notification">
            <i class="icon-exclamation-sign"></i> {!! 'Erro ao enviar o formulário, por favor verifique novamente seu dados.' !!}
            <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
            </ul>
        </div>
        @endif
        
        <div class="x_panel">

            <form id="orcamento-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('orcamento') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                    <input id="id" type="hidden" name="id" value="{{ isset($orcamento) ? $orcamento->id : "" }}">

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Dados do Lançamento </h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">

                      <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('historico') ? ' has-error' : '' }}">
                          <label for="historico" class="control-label">Histórico:*</label>
                          <input id="historico" class="form-control" name="historico" value="{{ isset($orcamento) ? $orcamento->historico : old('historico') }}" required type="text">
                          @if ($errors->has('historico'))
                            <span class="help-block">
                                <strong>{{ $errors->first('historico') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-9 item has-feedback {{ $errors->has('data_competencia') ? ' has-error' : '' }}">
                          <label for="data_competencia" class="control-label">Data Competência:*</label>
                          <input id="data_competencia" class="form-control has-feedback-left date" name="data_competencia" value="{{ isset($orcamento) ? $orcamento->data_competencia : old('data_lancamento') }}" required type="text">
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                           @if ($errors->has('data_competencia'))
                            <span class="help-block">
                                <strong>{{ $errors->first('data_competencia') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-9 item has-feedback {{ $errors->has('data_vencimento') ? ' has-error' : '' }}">
                          <label for="data_vencimento" class="control-label">Data Vencimento:*</label>
                          <input id="data_vencimento" class="form-control has-feedback-left date" name="data_vencimento" value="{{ isset($orcamento) ? $orcamento->data_vencimento : old('data_vencimento') }}" required type="text">
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                           @if ($errors->has('data_vencimento'))
                            <span class="help-block">
                                <strong>{{ $errors->first('data_vencimento') }}</strong>
                            </span>
                          @endif
                      </div>

                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('tipo_movimento') ? ' has-error' : '' }}">
                          <label for="tipo_movimento" class="control-label">Tipo Movimento:*</label>
                          {!! Form::select('tipo_movimento', [''=>'', 'RCT'=>'Recebimento', 'PGT'=>'Pagamento'], (isset($orcamento)) ? $orcamento->tipo_movimento : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'tipo_movimento')) !!}
                          @if ($errors->has('tipo_movimento'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tipo_movimento') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('plano_conta_id') ? ' has-error' : '' }}">
                          <label for="plano_conta_id" class="control-label">Plano de Conta:*</label>
                          {!! Form::select('plano_conta_id', getOnlyChildPlanosContas($planosContas), (isset($orcamento)) ? $orcamento->plano_conta_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'plano_conta_id')) !!}
                           @if ($errors->has('plano_conta_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('plano_conta_id') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('centro_resultado_id') ? ' has-error' : '' }}">
                          <label for="centro_resultado_id" class="control-label">Centro de Resultado Crédito:*</label>
                          {!! Form::select('centro_resultado_id', getOnlyChildPlanosContas($centrosResultados), (isset($orcamento)) ? $orcamento->centro_resultado_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'centro_resultado_id')) !!}
                           @if ($errors->has('centro_resultado_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('centro_resultado_id') }}</strong>
                            </span>
                          @endif
                      </div>


                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('projeto_id') ? ' has-error' : '' }}">
                          <label for="projeto_id" class="control-label">Projeto:*</label>
                          {!! Form::select('projeto_id', getOnlyChildPlanosContas($projetos), (isset($orcamento)) ? $orcamento->projeto_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'projeto_id')) !!}
                           @if ($errors->has('projeto_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('projeto_id') }}</strong>
                            </span>
                          @endif
                      </div>

                    </div>

                    <div class="form-group">

                      <div class="col-md-9 col-sm-9 col-xs-12">
                          <label for="tags" class="control-label">Tags:</label>
                          <input id="tags" class="form-control" name="valor_lancamento" value="{{ isset($orcamento) ? $orcamento->tags : old('tags') }}" type="text" />
                      </div>
                      
                      <div class="col-md-3 col-sm-3 col-xs-12 tile_stats_count {{ $errors->has('valor_lancamento') ? ' has-error' : '' }}">
                          <label for="valor_lancamento" class="control-label">Valor: *</label>
                          <input id="valor_lancamento" class="form-control count green input-lg currency" name="valor_lancamento" value="{{ isset($orcamento) ? $orcamento->valor_lancamento : old('valor_lancamento') }}" type="text" required='required' />
                           @if ($errors->has('valor_lancamento'))
                            <span class="help-block">
                                <strong>{{ $errors->first('valor_lancamento') }}</strong>
                            </span>
                          @endif
                      </div>

                      
                    </div>

                </div>

                <div class="form-group">
                    <div class="col-md-3 col-md-offset-9">
                      <span class="pull-right">
                        <a id="cancel" href="{{ url('/orcamento') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
                        <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i>Salvar</button>
                      </span>
                    </div>
                </div>

              </div><!-- /x-content -->

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection