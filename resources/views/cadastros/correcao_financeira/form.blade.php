
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Nova Correção Financeira <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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
        
        <div class="x_panel">

            <form id="forma-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('correcaofinanceira') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <input id="id_correcao" type="hidden" name="id" value="{{ isset($correcao) ? $correcao->id : 0 }}">

                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Dados Gerais </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="form-group">
                    
                    <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('descricao') ? ' has-error' : '' }}">
                        <label for="descricao" class="control-label">Descrição:*</label>
                        <input id="descricao" class="form-control" name="descricao" value="{{ isset($correcao) ? $correcao->descricao : old('descricao') }}"  required="required"  type="text">
                        @if ($errors->has('descricao'))
                          <span class="help-block">
                              <strong>{{ $errors->first('descricao') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('plano_conta_id') ? ' has-error' : '' }}">
                        <label for="plano_conta_id" class="control-label">Plano de Conta:*</label>
                        {!! Form::select('plano_conta_id', getOnlyChildPlanosContas($planosContas), isset($correcao) ? $correcao->plano_conta->id : old('plano_conta_id'), array('class'=>'form-control', 'id' => 'plano_conta_id', 'required'=> 'required')) !!}
                    </div>

                  </div> 

                  <div class="form-group">
                    
                    <div class="col-md-2 col-sm-2 col-xs-6 item {{ $errors->has('aliquota_juros') ? ' has-error' : '' }}">
                        <label for="aliquota_juros" class="control-label">Alíquota Juros:*</label>
                        <input id="aliquota_juros" class="form-control currency" name="aliquota_juros" value="{{ isset($correcao) ? $correcao->aliquota_juros : old('aliquota_juros') }}"  required="required"  type="text">
                        @if ($errors->has('aliquota_juros'))
                          <span class="help-block">
                              <strong>{{ $errors->first('aliquota_juros') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('periodo_juros') ? ' has-error' : '' }}">
                        <label for="periodo_juros" class="control-label">Período Juros:*</label>
                        {!! Form::select('periodo_juros', getPeriodosAliquota(), (isset($correcao)) ? $correcao->periodo_juros : null, array('class'=>'form-control', 'id' => 'periodo_juros', 'required'=> 'required')) !!}
                        @if ($errors->has('periodo_juros'))
                          <span class="help-block">
                              <strong>{{ $errors->first('periodo_juros') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-6 item {{ $errors->has('aliquota_multa') ? ' has-error' : '' }}">
                        <label for="aliquota_multa" class="control-label">Alíquota Multa:*</label>
                        <input id="aliquota_multa" class="form-control currency" name="aliquota_multa" value="{{ isset($correcao) ? $correcao->aliquota_multa : old('aliquota_multa') }}"  required="required"  type="text">
                        @if ($errors->has('aliquota_multa'))
                          <span class="help-block">
                              <strong>{{ $errors->first('aliquota_multa') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('periodo_multa') ? ' has-error' : '' }}">
                        <label for="periodo_multa" class="control-label">Período Multa:*</label>
                        {!! Form::select('periodo_multa', getPeriodosAliquota(), (isset($correcao)) ? $correcao->periodo_multa : null, array('class'=>'form-control', 'id' => 'periodo_multa', 'required'=> 'required')) !!}
                        @if ($errors->has('periodo_multa'))
                          <span class="help-block">
                              <strong>{{ $errors->first('periodo_multa') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-6 item {{ $errors->has('limite_multa') ? ' has-error' : '' }}">
                        <label for="limite_multa" class="control-label">Límite Multa:*</label>
                        <input id="limite_multa" class="form-control currency" name="limite_multa" value="{{ isset($correcao) ? $correcao->limite_multa : old('limite_multa') }}"  required="required"  type="text">
                        @if ($errors->has('limite_multa'))
                          <span class="help-block">
                              <strong>{{ $errors->first('limite_multa') }}</strong>
                          </span>
                        @endif
                    </div>

                  </div> 
                  
              </div>

              <div class="form-group">
                  <div class="col-md-3 col-md-offset-9">
                    <span class="pull-right">
                      <a id="cancel" href="{{ url('/correcaofinanceira') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                      <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    </span>
                  </div>
              </div>

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection