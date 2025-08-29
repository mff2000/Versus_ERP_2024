
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Novo Armazém <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="armazem-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('armazem') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <input id="id_armazem" type="hidden" name="id" value="{{ isset($armazem) ? $armazem->id : 0 }}">

                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Dados Gerais </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="form-group">

                    <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('codigo') ? ' has-error' : '' }}">
                        <label for="codigo" class="control-label">Código:*</label>
                        <input id="codigo" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" name="codigo" value="{{ isset($armazem) ? $armazem->codigo : old('codigo') }}" required="required" type="text">
                        @if ($errors->has('codigo'))
                          <span class="help-block">
                              <strong>{{ $errors->first('codigo') }}</strong>
                          </span>
                        @endif
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12 item">
                        <label for="descricao" class="control-label">Descrição:*</label>
                        <input id="descricao" class="form-control" name="descricao" value="{{ isset($armazem) ? $armazem->descricao : old('descricao') }}"  required="required"  type="text">
                        @if ($errors->has('descricao'))
                          <span class="help-block">
                              <strong>{{ $errors->first('descricao') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12 item">
                        <label for="risco_credito" class="control-label">Ativo?:</label>
                        <p style="margin-top:10px;">
                          <label>
                            <input type="radio" class="flat" {!! (isset($armazem) && $armazem->ativo == 1) ? 'checked' : (!isset($armazem)) ? 'checked' : '' !!} name="ativo" value="1"> Sim
                          </label>
                        
                          <label>
                            <input type="radio" class="flat" {!! (isset($armazem) && $armazem->ativo == 0) ? 'checked' : "" !!}  name="ativo" value="0"> Não
                          </label>
                       
                        </p>
                    </div>

                  </div> 
                  
              </div>

              <div class="form-group">
                  <div class="col-md-3 col-md-offset-9">
                    <span class="pull-right">
                      <a id="cancel" href="{{ url('/armazem') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                      <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    </span>
                  </div>
              </div>

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection