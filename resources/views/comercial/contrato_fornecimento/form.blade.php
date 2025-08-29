
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            {{isset($contrato) ? 'Editar':'Novo'}} Contrato de Fornecimento <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="orcamento-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('contratoFornecimento') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <input id="id" type="hidden" name="id" value="{{ isset($contrato) ? $contrato->id : "" }}">

                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Dados do Contrato </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="" role="tabpanel" data-example-id="togglable-tabs">
        
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                      <li role="presentation" class="active">
                        <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Dados do Contrato</a>
                      </li>
                      <li role="presentation" class="">
                        <a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Itens do Contrato</a>
                      </li>
                    </ul>

                    <div id="myTabContent" class="tab-content">
            
                      <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                          @include('comercial/contrato_fornecimento/form_cab')
                      </div>
                      
                      <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                          <!-- Itens -->
                          @include('comercial/contrato_fornecimento/form_itens')
                      </div>

                    </div>

                  </div>

              </div><!-- /x-content -->

              <div class="form-group">
                  <div class="col-md-3 col-md-offset-9">
                    <span class="pull-right">
                      <a id="cancel" href="{{ url('/contratoFornecimento') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
                      <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    </span>
                  </div>
              </div>

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection