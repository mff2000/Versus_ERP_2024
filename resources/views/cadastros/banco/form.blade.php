
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Nova Conta Bancária <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="banco-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('banco') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <div class="" role="tabpanel" data-example-id="togglable-tabs">
          
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Cadastrais</a>
                      </li>
                      <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Endereços</a>
                      </li>
                    </ul>

                    <div id="myTabContent" class="tab-content">
              
                      <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                        <input id="id_banco" type="hidden" name="id" value="{{ isset($banco) ? $banco->id : 0 }}">

                        <div class="x_title">
                          <h2><i class="fa fa-bars"></i> Dados Bancários </h2>
                          <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                          

                          <div class="col-md-4 col-sm-4 col-xs-12 item {{ $errors->has('descricao') ? ' has-error' : '' }}">
                              <label for="descricao" class="control-label">{{trans('versus.bancos.descricao')}}:*</label>
                              <input id="descricao" class="form-control col-md-7 col-xs-12" name="descricao" value="{{ isset($banco) ? $banco->descricao : old('descricao') }}" placeholder="Nome da Conta" required="required" type="text">
                              @if ($errors->has('descricao'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('descricao') }}</strong>
                                </span>
                              @endif
                          </div>
                          
                          <div class="col-md-4 col-sm-4 col-xs-12 item">
                              <label for="nome_gerente" class="control-label">{{trans('versus.bancos.nome_gerente')}}:</label>
                              <input id="nome_gerente" class="form-control" name="nome_gerente" value="{{ isset($banco) ? $banco->nome_gerente : old('nome_gerente') }}"  type="text">
                          </div>

                           <div class="col-md-4 col-sm-4 col-xs-12 item has-feedback">
                              <label for="email_geral" class="control-label">{{trans('versus.bancos.email_geral')}}:</label>
                              <input id="email_geral" class="form-control has-feedback-left" name="email_geral" value="{{ isset($banco) ? $banco->email_geral : old('email_geral') }}"  type="email">
                              <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                          </div>

                        </div> 

                        <div class="form-group">
                          
                          <div class="col-md-1 col-sm-1 col-xs-3 item {{ $errors->has('codigo') ? ' has-error' : '' }}">
                              <label for="codigo" class="control-label">{{trans('versus.bancos.codigo')}}:*</label>
                              <input id="codigo" class="form-control numeric" name="codigo" maxlength="3" value="{{ isset($banco) ? $banco->codigo : old('codigo') }}"  required="required" type="text">
                              @if ($errors->has('codigo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('codigo') }}</strong>
                                </span>
                              @endif
                          </div>

                          <div class="col-md-2 col-sm-2 col-xs-7 item {{ $errors->has('agencia') ? ' has-error' : '' }}">
                            <label for="agencia" class="control-label">{{trans('versus.bancos.agencia')}}:*</label>
                            <input id="agencia" class="form-control numeric" name="agencia" maxlength="5" value="{{ isset($banco) ? $banco->agencia : old('agencia') }}" required="required" type="text">
                            @if ($errors->has('agencia'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('agencia') }}</strong>
                              </span>
                            @endif
                          </div>

                          <div class="col-md-1 col-sm-1 col-xs-2 item {{ $errors->has('dv_agencia') ? ' has-error' : '' }}">
                            <label for="dv_agencia" class="control-label">{{trans('versus.bancos.dv_agencia')}}:*</label>
                            <input id="dv_agencia" class="form-control numeric" name="dv_agencia" maxlength="1" value="{{ isset($banco) ? $banco->dv_agencia : old('dv_agencia') }}" type="text">
                             @if ($errors->has('dv_agencia'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('dv_agencia') }}</strong>
                              </span>
                            @endif
                          </div>
                          
                          <div class="col-md-3 col-sm-3 col-xs-9 item {{ $errors->has('numero_conta') ? ' has-error' : '' }}">
                              <label for="numero_conta" class="control-label">{{trans('versus.bancos.numero_conta')}}:*</label>
                              <input id="numero_conta" class="form-control numeric" name="numero_conta"  maxlength="20" value="{{ isset($banco) ? $banco->numero_conta : old('numero_conta') }}" required="required" type="text">
                               @if ($errors->has('numero_conta'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('numero_conta') }}</strong>
                                </span>
                              @endif
                          </div>
                          
                          <div class="col-md-1 col-sm-1 col-xs-3 item {{ $errors->has('dv_conta') ? ' has-error' : '' }}">
                            <label for="dv_conta" class="control-label">{{trans('versus.bancos.dv_conta')}}:*</label>
                            <input id="dv_conta" class="form-control" name="dv_conta"  maxlength="1" value="{{ isset($banco) ? $banco->dv_conta : old('dv_conta') }}" required="required" type="text">
                            @if ($errors->has('dv_conta'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('dv_conta') }}</strong>
                                </span>
                            @endif
                          </div>

                          <div class="col-md-2 col-sm-2 col-xs-3 item {{ $errors->has('limite') ? ' has-error' : '' }}">
                            <label for="limite" class="control-label">{{trans('versus.bancos.limite')}}:*</label>
                            <input id="limite" class="form-control currency" name="limite" value="{{ isset($banco) ? $banco->limite : old('limite') }}" required="required" type="text">
                            @if ($errors->has('limite'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('limite') }}</strong>
                                </span>
                            @endif
                          </div>

                          <div class="col-md-2 col-sm-2 col-xs-3 item {{ $errors->has('saldo_atual') ? ' has-error' : '' }}">
                            <label for="saldo_atual" class="control-label">{{trans('versus.bancos.saldo_atual')}}:*</label>
                            <input id="saldo_atual" class="form-control currency" name="saldo_atual" value="{{ isset($banco) ? $banco->saldo_atual : old('saldo_atual') }}" required="required" type="text">
                            @if ($errors->has('saldo_atual'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('saldo_atual') }}</strong>
                                </span>
                            @endif
                          </div>

                        </div>

                        <div class="form-group">
                        
                          <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
                              <label for="tel_fixo1" class="control-label">Tel. Fixo 1:</label>
                              <input id="tel_fixo1" class="form-control has-feedback-left phone" name="tel_fixo1" value="{{ isset($banco) ? $banco->tel_fixo1 : old('tel_fixo1') }}" type="text">
                              <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                          </div>
                          
                          <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
                              <label for="tel_fixo2" class="control-label">Tel. Fixo 2:</label>
                              <input id="tel_fixo2" class="form-control has-feedback-left phone" name="tel_fixo2" value="{{ isset($banco) ? $banco->tel_fixo2 : old('tel_fixo2') }}"  type="text">
                              <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                          </div>
                          
                          <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
                              <label for="tel_movel1" class="control-label">Celular 1:</label>
                              <input id="tel_movel1" class="form-control has-feedback-left phone" name="tel_movel1" value="{{ isset($banco) ? $banco->tel_movel1 : old('tel_movel1') }}" type="text">
                              <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                          </div>
                          
                          <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
                              <label for="tel_movel2" class="control-label">Celular 2:</label>
                              <input id="tel_movel2" class="form-control has-feedback-left phone" name="tel_movel2" value="{{ isset($banco) ? $banco->tel_fixo2 : old('tel_movel2') }}"  type="text">
                              <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                          </div>

                        </div>

                      </div>

                      <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

                        @include('common/address')

                      </div>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-md-3 col-md-offset-9">
                      <span class="pull-right">
                        <a id="cancel" href="{{ url('/banco') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
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