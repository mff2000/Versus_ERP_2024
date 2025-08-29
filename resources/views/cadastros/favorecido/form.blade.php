
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Novo Favorecido <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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
     
        <div class="x_content">

          <div class="" role="tabpanel" data-example-id="togglable-tabs">
          
            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Cadastrais</a>
              </li>
              <li role="presentation" class="">
                <a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Endereços</a>
              </li>
              <li role="presentation" class="">
                <a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Condições Comerciais</a>
              </li>
              <li role="presentation" class="">
                <a href="#tab_content4" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Tabela de Preço</a>
              </li>
              <li role="presentation" class="">
                <a href="#tab_content5" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Vendedores</a>
              </li>
              <li role="presentation" class="">
                <a href="#tab_content6" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Observações</a>
              </li>
            </ul>

            <form id="favorecido-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('/favorecido') }}" novalidate>
              {{ csrf_field() }}
              <input id="id_favorecido" type="hidden" name="id" value="{{ isset($favorecido) ? $favorecido->id : 0 }}">

                <div id="myTabContent" class="tab-content">
              
                  <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                    <!-- start form for validation -->
                    @include('cadastros/favorecido/form_dados')
                  </div>
                  <!-- end form for validations -->
                  <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                      <!-- Endereço -->
                      @include('cadastros/favorecido/form_endereco')
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                      <!-- Endereço -->
                      @include('cadastros/favorecido/form_comercial')
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
                      <!-- Endereço -->
                      @include('cadastros/favorecido/form_tabela')
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tab">
                      <!-- Endereço -->
                      @include('cadastros/favorecido/form_vendedores')
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content6" aria-labelledby="profile-tab">
                      <!-- Endereço -->
                      @include('cadastros/favorecido/form_obs')
                  </div>
                </div>
          
                <div class="form-group">
                  <div class="col-md-3 col-md-offset-9">
                    <span class="pull-right">
                      <a id="cancel" href="{{ url('/favorecido') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                      <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    </span>
                  </div>
                </div>
            </form>
        
          </div>

        </div>

    </div>

  </div>

</div><!-- #row -->

<script>

    $(function() {

      $("#limite_credito").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 50000,
        from: <?= (isset($favorecido)) ? $favorecido->limite_credito : 0 ?>,
        type: 'single',
        step: 100,
        prefix: "R$",
        grid: true
      });
      
      $("#limite_desconto").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 100,
        from: <?= (isset($favorecido)) ? $favorecido->limite_desconto : 0 ?>,
        type: 'single',
        step: 1,
        prefix: "%",
        grid: true
      });
     
    });

</script>

@endsection