
@extends('layouts.app')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
            Perfis de Usuário
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
            <form id="demo-form" class="form-horizontal form-label-left" method="POST" action="{{ url('/perfil') }}" novalidate>
                {!! csrf_field() !!}

                <div class="item form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                  <label for="nome" class="control-label col-md-3 col-sm-3 col-xs-12">Nome do Perfil * :</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="nome" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="nome" value="{{ old('nome') }}" placeholder="ex: Administrador" required="required" type="text">
                  </div>
                </div>

                <div class="item form-group{{ $errors->has('descricao') ? ' has-error' : '' }}">
                  <label for="descricao" class="control-label col-md-3 col-sm-3 col-xs-12">Descrição :</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="descricao" class="form-control col-md-7 col-xs-12" name="descricao" value="{{ old('descricao') }}"  required="required" type="text">
                  </div>
                </div>

                <div class="item form-group{{ $errors->has('ativo') ? ' has-error' : '' }}">
                  <label for="ativo" class="control-label col-md-3 col-sm-3 col-xs-12">Ativo * :</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="ativo" type="checkbox" name="ativo" class="checkbox flat" checked="checked" required="required">
                  </div>
                  @if ($errors->has('ativo'))
                    <span class="help-block">
                        <strong>{{ $errors->first('ativo') }}</strong>
                    </span>
                  @endif
                </div>

                <div class="item form-group{{ $errors->has('descricao') ? ' has-error' : '' }}">
                  <label for="descricao" class="control-label col-md-3 col-sm-3 col-xs-12">Permissões :</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                      @foreach($permissions as $permission)

                        <label for="permission_{{$permission->id}}">
                          <input id="permission_{{$permission->id}}" value="{{$permission->id}}" type="checkbox" name="permission[]" class="flat" /> {{$permission->display_name}}
                        </label>

                      @endforeach
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