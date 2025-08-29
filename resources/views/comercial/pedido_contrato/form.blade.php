
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            {{isset($pedido) ? 'Editar':'Novo'}} Pedido de Contrato<small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="orcamento-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('pedidoContrato') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <input id="id" type="hidden" name="id" value="{{ isset($pedido) ? $pedido->id : "" }}">

                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Pedido Nº <b>{{ isset($pedido) ? $pedido->id : "###" }}</b> </h2>
                    <div class="clearfix"></div>
                  </div>
                  
                  @include('comercial/pedido_contrato/form_pedido_cab')

                  <div class="form-group">
                      <div class="col-md-3 col-md-offset-9">
                        <span class="pull-right">
                          <a id="cancel" href="{{ url('/pedidoContrato') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
                          <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                        </span>
                      </div>
                  </div>

              </div><!-- /x-content -->

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection