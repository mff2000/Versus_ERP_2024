
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Novo Produto <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="produto-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('produto') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                  <input id="id_correcao" type="hidden" name="id" value="{{ isset($produto) ? $produto->id : 0 }}">

                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Dados Gerais </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="form-group">
                    
                    <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('descricao') ? ' has-error' : '' }}">
                        <label for="descricao" class="control-label">Descrição:*</label>
                        <input id="descricao" class="form-control" name="descricao" value="{{ isset($produto) ? $produto->descricao : old('descricao') }}"  required="required"  type="text">
                        @if ($errors->has('descricao'))
                          <span class="help-block">
                              <strong>{{ $errors->first('descricao') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12 item {{ $errors->has('unidade_id') ? ' has-error' : '' }}">
                        <label for="unidade_id" class="control-label">Unidade:*</label>
                        {!! Form::select('unidade_id', $unidades, isset($produto) ? $produto->unidade_id : old('unidade_id'), array('class'=>'form-control', 'id' => 'unidade_id', 'required'=> 'required')) !!}
                        @if ($errors->has('unidade_id'))
                          <span class="help-block">
                              <strong>{{ $errors->first('unidade_id') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12 item {{ $errors->has('grupo_id') ? ' has-error' : '' }}">
                        <label for="grupo_id" class="control-label">Grupo:*</label>
                        {!! Form::select('grupo_id', $grupos, isset($produto) ? $produto->grupo_id : old('grupo_id'), array('class'=>'form-control', 'id' => 'grupo_id', 'required'=> 'required')) !!}
                        @if ($errors->has('grupo_id'))
                          <span class="help-block">
                              <strong>{{ $errors->first('grupo_id') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12 item">
                        <label for="risco_credito" class="control-label">Ativo?:</label>
                        <p style="margin-top:10px;">
                          <label>
                            <input type="radio" class="flat" {!! (isset($produto) && $produto->ativo == 1) ? 'checked' : (!isset($produto)) ? 'checked' : '' !!} name="ativo" value="1"> Sim
                          </label>
                        
                          <label>
                            <input type="radio" class="flat" {!! (isset($produto) && $produto->ativo == 0) ? 'checked' : "" !!}  name="ativo" value="0"> Não
                          </label>
                       
                        </p>
                    </div>

                  </div> 

                  <div class="form-group">
                    
                    <div class="col-md-2 col-sm-2 col-xs-6 item {{ $errors->has('ncm') ? ' has-error' : '' }}">
                        <label for="ncm" class="control-label">NCM:*</label>
                        <input id="ncm" class="form-control" name="ncm" value="{{ isset($produto) ? $produto->ncm : old('ncm') }}"  required="required"  type="text">
                        @if ($errors->has('produto'))
                          <span class="help-block">
                              <strong>{{ $errors->first('produto') }}</strong>
                          </span>
                        @endif
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-6 item">
                        <label for="altura" class="control-label">Altura:</label>
                        <input id="altura" class="form-control currency" name="altura" value="{{ isset($produto) ? $produto->altura : old('altura') }}"  type="text">
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-6 item">
                        <label for="largura" class="control-label">Largura:</label>
                        <input id="largura" class="form-control currency" name="largura" value="{{ isset($produto) ? $produto->largura : old('largura') }}" type="text">
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-6 item">
                        <label for="espessura" class="control-label">Espessura:</label>
                        <input id="espessura" class="form-control currency" name="espessura" value="{{ isset($produto) ? $produto->espessura : old('espessura') }}" type="text">
                    </div>

                     <div class="col-md-2 col-sm-2 col-xs-12 item ">
                        <label for="grupo_id" class="control-label">Armazém:</label>
                        {!! Form::select('armazem_id', $armazens, isset($produto) ? $produto->armazem_id : old('armazem_id'), array('class'=>'form-control', 'id' => 'armazem_id')) !!}
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12 item">
                        <label for="risco_credito" class="control-label">Lapidado?:</label>
                        <p style="margin-top:10px;">
                          <label>
                            <input type="radio" class="flat" {!! (isset($produto) && $produto->lapidado == 1) ? 'checked' : "" !!} name="lapidado" value="1"> Sim
                          </label>
                        
                          <label>
                            <input type="radio" class="flat" {!! (isset($produto) && $produto->lapidado == 0) ? 'checked' : (!isset($produto)) ? 'checked' : '' !!}  name="lapidado" value="0"> Não
                          </label>
                       
                        </p>
                    </div>

                  </div> 
                  
                  <div class="form-group">

                    <div class="col-md-2 col-sm-2 col-xs-6 item">
                        <label for="fator_multiplo" class="control-label">Fator Multíplo:</label>
                        <input id="fator_multiplo" class="form-control currency" name="fator_multiplo" value="{{ isset($produto) ? $produto->fator_multiplo : old('fator_multiplo') }}"  type="text">
                    </div>

                  </div>
              </div>

              <div class="form-group">
                  <div class="col-md-3 col-md-offset-9">
                    <span class="pull-right">
                      <a id="cancel" href="{{ url('/produto') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                      <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                    </span>
                  </div>
              </div>

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection