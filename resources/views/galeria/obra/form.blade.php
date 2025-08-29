
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Cadastro de Obra <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="obra-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('galeria/obra') }}" enctype="multipart/form-data" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <div class="" role="tabpanel" data-example-id="togglable-tabs">
          
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Dados da Obra</a>
                      </li>
                      <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Proprietário</a>
                      </li>
                    </ul>

                    <div id="myTabContent" class="tab-content">
              
                      <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                        <input id="id_banco" type="hidden" name="id" value="{{ isset($obra) ? $obra->id : 0 }}">

                        <div class="x_title">
                          <h2><i class="fa fa-bars"></i> Dados da Obra </h2>
                          <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                          

                          <div class="col-md-4 col-sm-4 col-xs-12 item {{ $errors->has('titulo') ? ' has-error' : '' }}">
                              <label for="titulo" class="control-label">Título:*</label>
                              <input id="titulo" class="form-control col-md-7 col-xs-12" name="titulo" value="{{ isset($obra) ? $obra->titulo : old('titulo') }}" placeholder="Nome da Obra" required="required" type="text">
                              @if ($errors->has('titulo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('titulo') }}</strong>
                                </span>
                              @endif
                          </div>
                          
                          <div class="col-md-4 col-sm-4 col-xs-12 item">
                              <label for="tipo_obra_id" class="control-label">Tipo de Obra:*</label>
                              {!! Form::select('tipo_obra_id', $tiposObras, isset($obra) ? $obra->tipo_obra_id : old('tipo_obra_id'), array('class'=>'form-control', 'id' => 'tipo_obra_id', 'required'=> 'required')) !!}
                          </div>

                          <div class="col-md-4 col-sm-4 col-xs-12 item has-feedback">
                              <label for="tecnica_id" class="control-label">Técnica:*</label>
                              {!! Form::select('tecnica_id', $tecnicas, isset($obra) ? $obra->tecnica_id : old('tecnica_id'), array('class'=>'form-control', 'id' => 'tecnica_id', 'required'=> 'required', 'placeholder'=>'Selecione o tipo...')) !!}
                          </div>

                        </div> 

                        <div class="form-group">
                          
                          <div class="col-md-4 col-sm-4 col-xs-12 item {{ $errors->has('artista_id') ? ' has-error' : '' }}">
                              <label for="artista_id" class="control-label">Artista:*</label>
                              {!! Form::select('artista_id', $artistas, isset($obra) ? $obra->artista_id : old('artista_id'), array('class'=>'form-control', 'id' => 'artista_id', 'required'=> 'required')) !!}
                          </div>
                          
                          <div class="col-md-4 col-sm-4 col-xs-9 item {{ $errors->has('dimensao') ? ' has-error' : '' }}">
                              <label for="dimensao" class="control-label">Dimensões:*</label>
                              <input id="dimensao" class="form-control" name="dimensao"  maxlength="50" value="{{ isset($obra) ? $obra->dimensao : old('dimensao') }}" required="required" type="text">
                               @if ($errors->has('dimensao'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('dimensao') }}</strong>
                                </span>
                              @endif
                          </div>
                          
                          <div class="col-md-1 col-sm-1 col-xs-3 item {{ $errors->has('anoexecucao') ? ' has-error' : '' }}">
                            <label for="anoexecucao" class="control-label">Ano</label>
                            <input id="anoexecucao" class="form-control numeric" name="anoexecucao"  maxlength="4" value="{{ isset($obra) ? $obra->anoexecucao : old('anoexecucao') }}" required="required" type="text">
                            @if ($errors->has('anoexecucao'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('anoexecucao') }}</strong>
                                </span>
                            @endif
                          </div>

                          <div class="col-md-3 col-sm-3 col-xs-6 item has-feedback {{ $errors->has('data_aquisicao') ? ' has-error' : '' }}">
                              <label for="data_aquisicao" class="control-label">Aquisição:*</label>
                              <input id="data_aquisicao" class="form-control has-feedback-left date" name="data_aquisicao" value="{{ isset($obra) ? convertDatePt($obra->data_aquisicao) : old('data_aquisicao') }}" required="required"  type="text">
                              <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                              @if ($errors->has('data_aquisicao'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('data_aquisicao') }}</strong>
                                </span>
                              @endif
                          </div>

                        </div>

                        <div class="form-group">
                        
                          <div class="col-md-4 col-sm-4 col-xs-3 item {{ $errors->has('valor_custo') ? ' has-error' : '' }}">
                            <label for="valor_custo" class="control-label">Custo (R$): </label>
                            <input id="valor_custo" class="form-control currency" name="valor_custo" value="{{ isset($obra) ? $obra->valor_custo : old('valor_custo') }}" type="text">
                            @if ($errors->has('valor_custo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('valor_custo') }}</strong>
                                </span>
                            @endif
                          </div>

                          <div class="col-md-4 col-sm-4 col-xs-3 item {{ $errors->has('valor_venda') ? ' has-error' : '' }}">
                            <label for="valor_venda" class="control-label">Venda (R$):*</label>
                            <input id="valor_venda" class="form-control currency" name="valor_venda" value="{{ isset($obra) ? $obra->valor_venda : old('valor_venda') }}" type="text">
                            @if ($errors->has('valor_venda'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('valor_venda') }}</strong>
                                </span>
                            @endif
                          </div>

                          <div class="col-md-4 col-sm-4 col-xs-3 item {{ $errors->has('valor_minimo_venda') ? ' has-error' : '' }}">
                            <label for="valor_minimo_venda" class="control-label">Venda Mínimo (R$): </label>
                            <input id="valor_minimo_venda" class="form-control currency" name="valor_minimo_venda" value="{{ isset($obra) ? $obra->valor_minimo_venda : old('valor_minimo_venda') }}" type="text">
                            @if ($errors->has('valor_minimo_venda'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('valor_minimo_venda') }}</strong>
                                </span>
                            @endif
                          </div>

                        </div>

                        <div class="form-group">

                            <div class="col-sm-6 col-md-6 col-xs-12">
                                <label for="foto" class="control-label">Imagem</label>
                                <input class="form-control" name="foto" type="file" id="foto" />
                            </div>
                            <div class="col-sm-4 col-md-4 col-xs-2">
                                
                                  @if( isset($obra->foto) && !empty($obra->foto) )
                                  <img src="{{ url($obra->foto) }}" height="90" class="margin image" />
                                  @endif
                                
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-3 ">
                            <label for="valor_venda" class="control-label">Estoque:*</label>
                            <input id="estoque" class="form-control currency" name="estoque" value="{{ isset($obra) ? $obra->estoque : old('estoque') }}" type="number">
                            @if ($errors->has('estoque'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('estoque') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>

                      </div>

                      <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

                          <div class="form-group">
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                  <label for="proprietario_id" class="control-label">Proprietário:</label>
                                  {!! Form::select('proprietario_id', $favorecidos, isset($obra) ? $obra->proprietario_id : old('proprietario_id'), array('class'=>'form-control', 'id' => 'proprietario_id')) !!}
                              </div>
                          </div>
                        @include('common/address')

                      </div>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-md-3 col-md-offset-9">
                      <span class="pull-right">
                        <a id="cancel" href="{{ url('galeria/obra') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
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