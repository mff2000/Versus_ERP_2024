@extends('layouts.login')

@section('content')

<div id="login" class="animate form">
  <div class="separator">
    <div style="text-align: center">
      <img src="{{ URL::asset('images/logo.png') }}" />
      <h5>Autorizado para: {{ env('AUTHTO_COMPANY', 'Versus ERP') }} </h5>
    </div>
  </div>
  <section class="login_content">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
      {!! csrf_field() !!}

      <h1>Login de Acesso</h1>
      
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <input type="email" class="form-control" name="email" value="{{ old('email') }}" />
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <input type="password" class="form-control" name="password">

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <div class="checkbox col-md-4">
            <label>
                <input type="checkbox" name="remember"> Lembrar-me
            </label>
        </div>

        <div class="col-md-8">
          <button type="submit" class="btn btn-primary btn-lg btn-block">
              <i class="fa fa-btn fa-sign-in"></i> Entrar
          </button>
        </div>

      <a class="btn btn-link" href="{{ url('/password/reset') }}">Esqueceu sua senha?</a>
        
      </div>

      <div class="clearfix"></div>

      <div class="separator">

        <div class="clearfix"></div>
        <br />
        <div>
          <p>©2016-{{date('Y')}} Todos Direitos Reservados.<br /> {{ env('APP_NAME', 'Versus ERP') }} - {{env('APP_DESCRIPTION')}}</p>
        </div>
      </div>
    </form>
    <!-- form -->
  </section>
  <!-- content -->
</div>
<!-- content -->

@endsection
