
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Nova Condição de Pagamento <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="condicao-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('condicaoPagamento') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <input id="id_armazem" type="hidden" name="id" value="{{ isset($condicao) ? $condicao->id : 0 }}">

                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Dados Gerais </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="form-group">

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('tipo') ? ' has-error' : '' }}">
                        <label for="tipo" class="control-label">Tipo:*</label>
                        {!! Form::select('tipo', getTipoCondicoes(), isset($condicao) ? $condicao->tipo : old('tipo'), array('class'=>'form-control', 'id' => 'tipo', 'required'=> 'required')) !!}
                        @if ($errors->has('tipo'))
                          <span class="help-block">
                              <strong>{{ $errors->first('tipo') }}</strong>
                          </span>
                        @endif
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('tipo') ? ' has-error' : '' }}">
                        <label for="descricao" class="control-label">Descrição:*</label>
                        <input id="descricao" class="form-control" name="descricao" value="{{ isset($condicao) ? $condicao->descricao : old('descricao') }}"  required="required"  type="text">
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
                            <input type="radio" class="flat" {!! (isset($condicao) && $condicao->ativo == 1) ? 'checked' : (!isset($condicao)) ? 'checked' : '' !!} name="ativo" value="1"> Sim
                          </label>
                        
                          <label>
                            <input type="radio" class="flat" {!! (isset($condicao) && $condicao->ativo == 0) ? 'checked' : "" !!}  name="ativo" value="0"> Não
                          </label>
                       
                        </p>
                    </div>

                  </div> 
                  
                  <div class="form-group">

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('quantidade_parcelas') ? ' has-error' : '' }}">
                        <label for="quantidade_parcelas" class="control-label">Quant. de Parcelas:*</label>
                        <input id="quantidade_parcelas" class="form-control numeric" name="quantidade_parcelas" value="{{ isset($condicao) ? $condicao->quantidade_parcelas : old('quantidade_parcelas') }}"  required="required"  type="text">
                        @if ($errors->has('quantidade_parcelas'))
                          <span class="help-block">
                              <strong>{{ $errors->first('quantidade_parcelas') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('dias_intervalo') ? ' has-error' : '' }}">
                        <label for="dias_intervalo" class="control-label">Dias de Intervalo:*</label>
                        <input id="dias_intervalo" class="form-control numeric" name="dias_intervalo" value="{{ isset($condicao) ? $condicao->dias_intervalo : old('quantidade_parcelas') }}"  required="required"  type="text">
                        @if ($errors->has('dias_intervalo'))
                          <span class="help-block">
                              <strong>{{ $errors->first('dias_intervalo') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('dias_carencia') ? ' has-error' : '' }}">
                        <label for="dias_carencia" class="control-label">Dias de Carência:*</label>
                        <input id="dias_carencia" class="form-control numeric" name="dias_carencia" value="{{ isset($condicao) ? $condicao->dias_carencia : old('quantidade_parcelas') }}"  required="required"  type="text">
                        @if ($errors->has('dias_carencia'))
                          <span class="help-block">
                              <strong>{{ $errors->first('dias_carencia') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item">
                        <label for="percentuais" class="control-label">Percentuais:</label>
                        <input id="percentuais" class="form-control" name="percentuais" value="{{ isset($condicao) ? $condicao->percentuais : old('quantidade_parcelas') }}"  type="text">
                    </div>

                  </div>

              </div>

              <div class="form-group">
                  <div class="col-md-3 col-md-offset-9">
                    <span class="pull-right">
                      <a id="cancel" href="{{ url('/condicaoPagamento') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                      <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    </span>
                  </div>
              </div>

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection