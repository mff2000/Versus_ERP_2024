
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Novo Tipo de Obra <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="forma-form" class="form-horizontal form-label-left mode2" method="POST" action="{{ url('galeria/tipo_obra') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <input id="id_forma" type="hidden" name="id" value="{{ isset($tipoObra) ? $tipoObra->id : 0 }}">

                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Dados Gerais </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="form-group">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 item {{ $errors->has('nome') ? ' has-error' : '' }}">
                        <label for="nome" class="control-label">Nome do Tipo:*</label>
                        <input id="nome" class="form-control" name="nome" value="{{ isset($tipoObra) ? $tipoObra->nome : old('nome') }}"  required="required"  type="text">
                        @if ($errors->has('nome'))
                          <span class="help-block">
                              <strong>{{ $errors->first('nome') }}</strong>
                          </span>
                        @endif
                    </div>

                  </div> 
                  
              </div>

              <div class="form-group">
                  <div class="col-md-3 col-md-offset-9">
                    <span class="pull-right">
                      <a id="cancel" href="{{ url('/galeria/tipo_obra') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                      <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    </span>
                  </div>
              </div>

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection