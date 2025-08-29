
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Incluir Lançamento Não Financeiro <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="lancamento-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('lancamento_gerencial') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                    <input id="id" type="hidden" name="id" value="{{ isset($lancamento) ? $lancamento->id : "" }}">

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Dados do Lançamento </h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">

                      <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('historico') ? ' has-error' : '' }}">
                          <label for="historico" class="control-label">Histórico:*</label>
                          <input id="historico" class="form-control" name="historico" value="{{ isset($lancamento) ? $lancamento->historico : old('historico') }}" required type="text">
                          @if ($errors->has('historico'))
                            <span class="help-block">
                                <strong>{{ $errors->first('historico') }}</strong>
                            </span>
                          @endif
                      </div>
                      
                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('favorecido_id') ? ' has-error' : '' }}">
                          <label for="favorecido_id" class="control-label">Favorecido:*</label>
                          {!! Form::select('favorecido_id', $favorecidos, isset($lancamento) ? $lancamento->favorecido_id : old('favorecido_id'), array('class'=>'form-control', 'id' => 'favorecido_id', 'required'=> 'required')) !!}
                          @if ($errors->has('favorecido_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('favorecido_id') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-9 item has-feedback {{ $errors->has('data_lancamento') ? ' has-error' : '' }}">
                          <label for="data_lancamento" class="control-label">Data Lançamento:*</label>
                          <input id="data_lancamento" class="form-control has-feedback-left date" name="data_lancamento" value="{{ isset($lancamento) ? $lancamento->data_lancamento : (old('data_lancamento')) ? old('data_lancamento') : <?=date('d/m/Y')?>  }}"  type="text">
                          <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                           @if ($errors->has('data_lancamento'))
                            <span class="help-block">
                                <strong>{{ $errors->first('data_lancamento') }}</strong>
                            </span>
                          @endif
                      </div>

                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('plano_conta_debito_id') ? ' has-error' : '' }}">
                          <label for="plano_conta_debito_id" class="control-label">Plano de Conta Débito:*</label>
                          {!! Form::select('plano_conta_debito_id', getOnlyChildPlanosContas($planosContas), (isset($lancamento)) ? $lancamento->plano_conta_debito_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'plano_conta_debito_id')) !!}
                           @if ($errors->has('plano_conta_debito_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('plano_conta_debito_id') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('centro_resultado_debito_id') ? ' has-error' : '' }}">
                          <label for="centro_resultado_debito_id" class="control-label">Centro de Resultado Débito:*</label>
                          {!! Form::select('centro_resultado_debito_id', getOnlyChildPlanosContas($centrosResultados), (isset($lancamento)) ? $lancamento->centro_resultado_debito_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'centro_resultado_debito_id')) !!}
                           @if ($errors->has('centro_resultado_debito_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('centro_resultado_debito_id') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('projeto_debito_id') ? ' has-error' : '' }}">
                          <label for="projeto_debito_id" class="control-label">Projeto Débito:*</label>
                          {!! Form::select('projeto_debito_id', getOnlyChildPlanosContas($projetos), (isset($lancamento)) ? $lancamento->projeto_debito_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'projeto_debito_id')) !!}
                           @if ($errors->has('projeto_debito_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('projeto_debito_id') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12">
                          <div class="row">
                            <div class="col-sm-12">
                              <label for="numero_titulo" class="control-label">Número/Parcela:</label>
                            </div>
                            <div class="col-sm-9">
                            <input id="numero_titulo" class="form-control span9" name="numero_titulo" value="{{ isset($lancamento) ? $lancamento->numero_titulo : old('numero_titulo') }}" type="text" />
                            </div>
                            <div class="col-sm-3">
                              <input id="numero_parcela" class="form-control span2" name="numero_parcela" value="{{ isset($lancamento) ? $lancamento->numero_parcela : old('numero_parcela') }}" type="text" />
                            </div>
                          </div>
                      </div>

                    </div>

                    <div class="form-group">

                       <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('plano_conta_credito_id') ? ' has-error' : '' }}">
                          <label for="plano_conta_credito_id" class="control-label">Plano de Conta Crédito:*</label>
                          {!! Form::select('plano_conta_credito_id', getOnlyChildPlanosContas($planosContas), (isset($lancamento)) ? $lancamento->plano_conta_credito_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'plano_conta_credito_id')) !!}
                           @if ($errors->has('plano_conta_credito_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('plano_conta_credito_id') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('centro_resultado_credito_id') ? ' has-error' : '' }}">
                          <label for="centro_resultado_credito_id" class="control-label">Centro de Resultado Crédito:*</label>
                          {!! Form::select('centro_resultado_credito_id', getOnlyChildPlanosContas($centrosResultados), (isset($lancamento)) ? $lancamento->centro_resultado_credito_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'centro_resultado_credito_id')) !!}
                           @if ($errors->has('centro_resultado_credito_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('centro_resultado_credito_id') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('projeto_credito_id') ? ' has-error' : '' }}">
                          <label for="projeto_credito_id" class="control-label">Projeto Crédito:*</label>
                          {!! Form::select('projeto_credito_id', getOnlyChildPlanosContas($projetos), (isset($lancamento)) ? $lancamento->projeto_credito_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'projeto_credito_id')) !!}
                           @if ($errors->has('projeto_credito_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('projeto_credito_id') }}</strong>
                            </span>
                          @endif
                      </div>
                      
                      <div class="col-md-3 col-sm-3 col-xs-12 tile_stats_count {{ $errors->has('valor_lancamento') ? ' has-error' : '' }}">
                          <label for="valor_lancamento" class="control-label">Valor:</label>
                          <input id="valor_lancamento" class="form-control count green input-lg currency" name="valor_lancamento" value="{{ isset($lancamento) ? $lancamento->valor_lancamento : old('valor_lancamento') }}" type="text" required='required' />
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
                        <a id="cancel" href="{{ url('/lancamento_gerencial') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
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