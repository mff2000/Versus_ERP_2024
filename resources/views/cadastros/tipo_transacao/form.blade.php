
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Novo Tipo de Transação <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="tipoTransacao-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('tipoTransacao') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <input id="id_armazem" type="hidden" name="id" value="{{ isset($tipoTransacao) ? $tipoTransacao->id : 0 }}">

                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Dados Gerais </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="form-group">

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('tipo') ? ' has-error' : '' }}">
                        <label for="tipo" class="control-label">Tipo:*</label>
                        {!! Form::select('tipo', getTipoTransacao(), isset($tipoTransacao) ? $tipoTransacao->tipo : old('tipo'), array('class'=>'form-control', 'id' => 'tipo', 'required'=> 'required')) !!}
                        @if ($errors->has('tipo'))
                          <span class="help-block">
                              <strong>{{ $errors->first('tipo') }}</strong>
                          </span>
                        @endif
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('tipo') ? ' has-error' : '' }}">
                        <label for="descricao" class="control-label">Descrição:*</label>
                        <input id="descricao" class="form-control" name="descricao" value="{{ isset($tipoTransacao) ? $tipoTransacao->descricao : old('descricao') }}"  required="required"  type="text">
                        @if ($errors->has('descricao'))
                          <span class="help-block">
                              <strong>{{ $errors->first('descricao') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item ">
                        <label for="risco_credito" class="control-label">Ativo?:</label>
                        <p style="margin-top:10px;">
                          <label>
                            <input type="radio" class="flat" {!! (isset($tipoTransacao) && $tipoTransacao->ativo == 1) ? 'checked' : (!isset($tipoTransacao)) ? 'checked' : '' !!} name="ativo" value="1"> Sim
                          </label>
                        
                          <label>
                            <input type="radio" class="flat" {!! (isset($tipoTransacao) && $tipoTransacao->ativo == 0) ? 'checked' : "" !!}  name="ativo" value="0"> Não
                          </label>
                       
                        </p>
                    </div>

                  </div> 
                  
                  <div class="form-group">

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('integra_financeiro') ? ' has-error' : '' }}">
                        <label for="integra_financeiro" class="control-label">Integra Financeiro:*</label>
                        {!! Form::select('integra_financeiro', getTipoIntegraFinanceiro(), isset($tipoTransacao) ? $tipoTransacao->integra_financeiro : old('integra_financeiro'), array('class'=>'form-control', 'id' => 'integra_financeiro', 'required'=> 'required')) !!}
                        @if ($errors->has('integra_financeiro'))
                          <span class="help-block">
                              <strong>{{ $errors->first('integra_financeiro') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('integra_estoque') ? ' has-error' : '' }}">
                        <label for="integra_estoque" class="control-label">Integra Estoque:*</label>
                        {!! Form::select('integra_estoque', getTipoIntegraEstoque(), isset($tipoTransacao) ? $tipoTransacao->integra_estoque : old('integra_financeiro'), array('class'=>'form-control', 'id' => 'integra_estoque', 'required'=> 'required')) !!}
                        @if ($errors->has('integra_estoque'))
                          <span class="help-block">
                              <strong>{{ $errors->first('integra_estoque') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('cfop_id') ? ' has-error' : '' }}">
                        <label for="cfop_id" class="control-label">CFOP:*</label>
                        {!! Form::select('cfop_id', $cfops, isset($tipoTransacao) ? $tipoTransacao->cfop_id : old('cfop_id'), array('class'=>'form-control', 'id' => 'cfop', 'required'=> 'required')) !!}
                        @if ($errors->has('cfop_id'))
                          <span class="help-block">
                              <strong>{{ $errors->first('cfop_id') }}</strong>
                          </span>
                        @endif
                    </div>

                  </div>

                  <div class="form-group">

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('plano_conta_debito_id') ? ' has-error' : '' }}">
                        <label for="plano_conta_debito_id" class="control-label">Plano Conta Débito:*</label>
                        {!! Form::select('plano_conta_debito_id', getOnlyChildPlanosContas($planosConta), isset($tipoTransacao) ? $tipoTransacao->plano_conta_debito_id : old('plano_conta_debito_id'), array('class'=>'form-control', 'id' => 'plano_conta_debito_id')) !!}
                        @if ($errors->has('plano_conta_debito_id'))
                          <span class="help-block">
                              <strong>{{ $errors->first('plano_conta_debito_id') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('centro_resultado_debito_id') ? ' has-error' : '' }}">
                        <label for="centro_resultado_debito_id" class="control-label">Centro Resultado Débito:*</label>
                        {!! Form::select('centro_resultado_debito_id', getOnlyChildPlanosContas($centrosResultado), isset($tipoTransacao) ? $tipoTransacao->centro_resultado_debito_id : old('centro_resultado_debito_id'), array('class'=>'form-control', 'id' => 'centro_resultado_debito_id')) !!}
                        @if ($errors->has('centro_resultado_debito_id'))
                          <span class="help-block">
                              <strong>{{ $errors->first('centro_resultado_debito_id') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('plano_conta_credito_id') ? ' has-error' : '' }}">
                        <label for="plano_conta_credito_id" class="control-label">Plano Conta Crédito:*</label>
                        {!! Form::select('plano_conta_credito_id', getOnlyChildPlanosContas($planosConta), isset($tipoTransacao) ? $tipoTransacao->plano_conta_credito_id : old('plano_conta_credito_id'), array('class'=>'form-control', 'id' => 'plano_conta_credito_id')) !!}
                        @if ($errors->has('plano_conta_credito_id'))
                          <span class="help-block">
                              <strong>{{ $errors->first('plano_conta_credito_id') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('centro_resultado_credito_id') ? ' has-error' : '' }}">
                        <label for="centro_resultado_credito_id" class="control-label">Centro Resultado Crédito:*</label>
                        {!! Form::select('centro_resultado_credito_id', getOnlyChildPlanosContas($centrosResultado), isset($tipoTransacao) ? $tipoTransacao->centro_resultado_credito_id : old('centro_resultado_credito_id'), array('class'=>'form-control', 'id' => 'centro_resultado_credito_id')) !!}
                        @if ($errors->has('centro_resultado_credito_id'))
                          <span class="help-block">
                              <strong>{{ $errors->first('centro_resultado_credito_id') }}</strong>
                          </span>
                        @endif
                    </div>

                  </div>

              </div>

              <div class="form-group">
                  <div class="col-md-3 col-md-offset-9">
                    <span class="pull-right">
                      <a id="cancel" href="{{ url('/tipoTransacao') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                      <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    </span>
                  </div>
              </div>

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

<script>

$('#tipo').on('change', function() {

  $('#plano_conta_credito_id').removeAttr('required');
  $('#centro_resultado_credito_id').removeAttr('required');
  $('#plano_conta_credito_id').removeAttr('required');
  $('#centro_resultado_credito_id').removeAttr('required');

  if( this.value == 'E') {
    $('#plano_conta_credito_id').attr('required', 'required');
    $('#centro_resultado_credito_id').attr('required', 'required');
  } else {
    $('#plano_conta_debito_id').attr('required', 'required');
    $('#centro_resultado_debito_id').attr('required', 'required');
  }

});

</script>

@endsection