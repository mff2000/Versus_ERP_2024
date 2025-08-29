
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Incluir Transferência <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="transferencia-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('transferencia') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                    <input id="id" type="hidden" name="id" value="{{ isset($transferencia) ? $transferencia->id : 0 }}">
                    <input id="tipo_movimento" type="hidden" name="tipo_movimento" value="TRF">

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Dados do Lançamento </h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                      

                      <div class="col-md-8 col-sm-8 col-xs-12 item {{ $errors->has('historico') ? ' has-error' : '' }}">
                          <label for="historico" class="control-label">Histórico:*</label>
                          <input id="historico" class="form-control" name="historico" value="{{ isset($transferencia) ? $transferencia->historico : old('historico') }}" required type="text">
                          @if ($errors->has('historico'))
                            <span class="help-block">
                                <strong>{{ $errors->first('historico') }}</strong>
                            </span>
                          @endif
                      </div>
                      
                      <div class="col-md-3 col-sm-3 col-xs-9 item has-feedback {{ $errors->has('data_lancamento') ? ' has-error' : '' }}">
                          <label for="data_lancamento" class="control-label">Data Lançamento:*</label>
                          <input id="data_lancamento" class="form-control has-feedback-left date" name="data_lancamento" value="{{ isset($transferencia) ? $transferencia->data_lancamento : (old('data_lancamento')) ? old('data_lancamento') : <?=date('d/m/Y')?>  }}"  type="text">
                          <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                           @if ($errors->has('data_lancamento'))
                            <span class="help-block">
                                <strong>{{ $errors->first('data_lancamento') }}</strong>
                            </span>
                          @endif
                      </div>

                    </div>

                    <div class="form-group">


                      <div class="col-md-4 col-sm-3 col-xs-6 item {{ $errors->has('banco_origem_id') ? ' has-error' : '' }}">
                          <label for="banco_origem_id" class="control-label">Banco Origem:*</label>
                          {!! Form::select('banco_origem_id', $bancos, (isset($transferencia)) ? $transferencia->banco_origem_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'banco_origem_id')) !!}
                          @if ($errors->has('banco_origem_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('banco_origem_id') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-6 item {{ $errors->has('banco_destino_id') ? ' has-error' : '' }}">
                          <label for="banco_destino_id" class="control-label">Banco Destino:*</label>
                          {!! Form::select('banco_destino_id', $bancos, (isset($transferencia)) ? $transferencia->banco_destino_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'banco_destino_id')) !!}
                          @if ($errors->has('banco_destino_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('banco_destino_id') }}</strong>
                            </span>
                          @endif
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12 tile_stats_count {{ $errors->has('valor_lancamento') ? ' has-error' : '' }}">
                          <label for="valor_lancamento" class="control-label">Valor:</label>
                          <input id="valor_lancamento" class="form-control count green input-lg currency" name="valor_lancamento" value="{{ isset($transferencia) ? $transferencia->valor_lancamento : old('valor_lancamento') }}" type="text"/>
                          @if ($errors->has('valor_lancamento'))
                            <span class="help-block">
                                <strong>{{ $errors->first('valor_lancamento') }}</strong>
                            </span>
                          @endif
                      </div>

                      
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-3">
                          <div class="row">
                            <div class="col-sm-12">
                              <label for="numero_titulo" class="control-label">Número/Parcela:</label>
                            </div>
                            <div class="col-sm-9">
                            <input id="numero_titulo" class="form-control span9" name="numero_titulo" value="{{ isset($transferencia) ? $transferencia->numero_titulo : old('numero_titulo') }}" type="text" />
                            </div>
                            <div class="col-sm-3">
                              <input id="numero_parcela" class="form-control span2" name="numero_parcela" value="{{ isset($transferencia) ? $transferencia->numero_parcela : old('numero_parcela') }}" type="text" />
                            </div>
                          </div>
                      </div>

                      @if(!isset($transferencia))
                      <div class="col-md-9 col-sm-9 col-xs-9 item">
                          <label for="tags" class="control-label">Marcadores:</label>
                          <input id="tags" class="form-control" name="tags" value="{{ isset($transferencia) ? $transferencia->tags : old('tags') }}"  type="text">
                      </div>
                      @endif

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-md-3 col-md-offset-9">
                      <span class="pull-right">
                        <a id="cancel" href="{{ url('/transferencia') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
                        <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i>Salvar</button>
                      </span>
                    </div>
                </div>

              </div><!-- /x-content -->

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection