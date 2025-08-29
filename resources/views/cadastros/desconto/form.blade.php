
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Novo Desconto <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="forma-form" class="form-horizontal form-label-left mode2" method="POST" action="{{ url('desconto') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <input id="id_forma" type="hidden" name="id" value="{{ isset($desconto) ? $desconto->id : 0 }}">

                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Dados Gerais </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="form-group">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 item {{ $errors->has('descricao') ? ' has-error' : '' }}">
                        <label for="descricao" class="control-label">Descrição:*</label>
                        <input id="descricao" class="form-control" name="descricao" value="{{ isset($desconto) ? $desconto->descricao : old('descricao') }}"  required="required"  type="text">
                        @if ($errors->has('descricao'))
                          <span class="help-block">
                              <strong>{{ $errors->first('descricao') }}</strong>
                          </span>
                        @endif
                    </div>

                  </div> 

                  <div class="form-group">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 item {{ $errors->has('plano_conta_id') ? ' has-error' : '' }}">
                        <label for="plano_conta_id" class="control-label">Plano de Conta:*</label>
                        {!! Form::select('plano_conta_id', getOnlyChildPlanosContas($planosContas), isset($desconto) ? $desconto->plano_conta->id : old('plano_conta_id'), array('class'=>'form-control', 'id' => 'cobranca_uf')) !!}
                    </div>

                  </div> 
                  
              </div>

              <div class="form-group">
                  <div class="col-md-3 col-md-offset-9">
                    <span class="pull-right">
                      <a id="cancel" href="{{ url('/desconto') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                      <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    </span>
                  </div>
              </div>

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection