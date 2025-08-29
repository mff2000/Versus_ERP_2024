
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Novo Projeto <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

		    <div class="x_title">
		      
				<h2>Projetos <small>Conta Superior: <b>{{ isset($contaSuperior) ? $contaSuperior->descricao : 'Nova' }}</b></small></h2>

				<ul class="nav navbar-right panel_toolbox">
					
				</ul>

				<div class="clearfix"></div>

		    </div>

		    <form id="conta-form" class="form-horizontal form-label-left mode2" method="POST" action="{{ url('projeto') }}" novalidate>
            	{{ csrf_field() }}
            	<input id="id_projeto" type="hidden" name="id" value="{{ isset($projeto) ? $projeto->id : 0 }}">
            	<input id="id_conta_contabel_superior" type="hidden" name="conta_superior" value="{{ isset($contaSuperior) ? $contaSuperior->id : 0 }}">

              	<div class="x_content">

              		<div class="form-group">
                          
                          <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('codigo') ? ' has-error' : '' }}">
                              <label for="codigo" class="control-label">Código:*</label>
                              <input id="codigo" class="form-control col-md-7 col-xs-12" max-lenth="20"  name="codigo" value="{{ isset($projeto) ? $projeto->codigo : old('codigo') }}" placeholder="" required="required" type="text">
                              @if ($errors->has('codigo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('codigo') }}</strong>
                                </span>
                              @endif
                          </div>
                          
                          <div class="col-md-9 col-sm-9 col-xs-12 item {{ $errors->has('descricao') ? ' has-error' : '' }}">
                              <label for="descricao" class="control-label">Descrição:</label>
                              <input id="descricao" class="form-control" name="descricao" value="{{ isset($projeto) ? $projeto->descricao : old('descricao') }}" required="required" type="text">
                          </div>

                    </div> 

                    <div class="form-group">
                    	<div class="col-md-3 col-sm-3 col-xs-12 item">
	                        <label for="risco_credito" class="control-label">Classe:</label><br />
	                        <p style="margin-top:10px;">
	                        	@foreach (getClassesConta() as $key => $classe)
		                          <label>
		                            <input type="radio" class="flat" name="classe" value="{{ $key }}" {{ ( isset($projeto) && $key==$projeto->classe ) ? 'checked' : '' }} {{ ( !isset($projeto) && $key=='A') ? 'checked' : '' }}> {{ $classe }}
		                          </label>
	                        	@endforeach
	                        </p>
	                    </div>
                      
                    </div>

                    <div class="form-group">
	                    <div class="col-md-3 col-md-offset-9">
	                      <span class="pull-right">
	                        <a id="cancel" href="{{ url('/projeto') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
	                        <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
	                      </span>
	                    </div>
	                </div>

		    	</div>

		   	</form>
        </div>
    </div>
</div><!-- /#row -->



@endsection