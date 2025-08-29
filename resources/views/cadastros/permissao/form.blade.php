
@extends('layouts.app')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
            Permissões de Acesso
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
      
        <div class="x_title">
          <h2><small>Dados com * são obrigatórios.</small></h2>
          
          <div class="clearfix"></div>
        </div>
  
        <div class="x_content">

            <!-- start form for validation -->
            <form id="demo-form" class="form-horizontal form-label-left" method="POST" action="{{ url('/permissao') }}" novalidate>
                {!! csrf_field() !!}

                <div class="item form-group{{ $errors->has('display_name') ? ' has-error' : '' }}">
                  <label for="nome" class="control-label col-md-3 col-sm-3 col-xs-12">Nome * :</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="display_name" class="form-control col-md-7 col-xs-12" name="display_name" value="{{ old('display_name') }}" placeholder="ex: Listar Usuários" required="required" type="text">
                  </div>
                </div>

                <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                  <label for="name" class="control-label col-md-3 col-sm-3 col-xs-12">Permissão * :</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="name" class="form-control col-md-7 col-xs-12" data-validate-words="2" name="name" value="{{ old('name') }}" placeholder="ex: user-create" required="required" type="text">
                  </div>
                </div>

                <div class="item form-group{{ $errors->has('descricao') ? ' has-error' : '' }}">
                  <label for="descricao" class="control-label col-md-3 col-sm-3 col-xs-12">Descrição :</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="descricao" class="form-control col-md-7 col-xs-12" name="descricao" value="{{ old('descricao') }}" required="required" type="text">
                  </div>
                </div>

                <br/>
                <div class="form-group">
                  <div class="col-md-6 col-md-offset-9">
                    <button type="submit" class="btn btn-primary">Cancelar</button>
                    <button id="send" type="submit" class="btn btn-success">Salvar</button>
                  </div>
                </div>

            </form>
            <!-- end form for validations -->

        </div>

    </div>

</div>
</div>

@endsection