
@extends('layouts.app')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
            Usuários
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
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Settings 1</a>
                </li>
                <li><a href="#">Settings 2</a>
                </li>
              </ul>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
  
        <div class="x_content">

            <!-- start form for validation -->
            <form id="demo-form" class="form-horizontal form-label-left" method="POST" action="{{ url('/user') }}" novalidate>
                {!! csrf_field() !!}

                <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                  <label for="name" class="control-label col-md-3 col-sm-3 col-xs-12">Nome Completo * :</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="name" value="{{ old('name') }}" placeholder="Nome e sobrenome ex: Jon Doe" required="required" type="text">
                  </div>
                </div>

                <div class="item form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email" class="control-label col-md-3 col-sm-3 col-xs-12">E-mail * :</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="email" class="form-control col-md-7 col-xs-12" name="email" value="{{ old('email') }}"  required="required" type="email">
                  </div>
                </div>

                <div class="item form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                  <label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">Senha * :</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="password" type="password" name="password" data-validate-length="6,8" class="form-control col-md-7 col-xs-12" required="required">
                  </div>
                  @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
                </div>

                <div class="item form-group">
                  <label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12">Confirme a senha</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="password2" type="password" name="password2" data-validate-linked="password" class="form-control col-md-7 col-xs-12" required="required">
                  </div>
                </div>

                <div class="item form-group">
                  <label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12">Confirme a senha</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::select('role_id', $roles, isset($user->role) ? $user->role->id : old('role_id'), array('class'=>'form-control', 'id' => 'role_id')) !!}
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