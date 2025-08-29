
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Retorno de Consignação<small></small>
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

            <form id="orcamento-form" class="form-horizontal form-label-left mode2" method="POST" action="{{ url('galeria/consignacao/retorno') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                    <input id="consignacao_id" type="hidden" name="id" value="{{ isset($consignacao) ? $consignacao->id : '' }}">
                    <input id="retornar_tudo" type="hidden" name="retornar_tudo" value="N">

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Consignação Nº <b>{{ isset($consignacao) ? $consignacao->id : "###" }}</b> </h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">

                        <div class="col-md-9 col-sm-9 col-xs-12 item {{ $errors->has('cliente_id') ? ' has-error' : '' }}">
                            <label for="cliente_id" class="control-label">Cliente:*</label>
                            <input id="cliente_id" class="form-control" name="has-feedback-left date" value="{{ isset($consignacao) ? $consignacao->cliente->nome_empresarial : old('cliente_id') }}" readonly="true" type="text">
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-9 has-feedback">
                            <label for="data_devolucao" class="control-label">Data de Devolução:*</label>
                            <input id="data_devolucao" class="form-control has-feedback-left date" name="data_devolucao" value="{{ isset($consignacao) ? convertDatePt($consignacao->data_devolucao) : old('data_devolucao') }}" type="text" readonly="true">
                            <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                        </div>

                    </div>

                    <table class="table table-striped">
                        <tbody>
                          <tr>
                            <th style="width: 10px">#</th>
                            <th>Título da Obra</th>
                            <th>Image</th>
                            <th>Valor da Obra</th>
                            <th>Valor Vendido</th>
                          </tr>
                          @foreach($consignacao->itens as $item)
                          <tr>
                              <td><input type="checkbox" class="flat" name="item_id[]" value="{{$item->id}}"></td>
                              <td>{{$item->obra->titulo}}</td>
                              <td>
                                @if( isset($item->obra->foto) && !empty($item->obra->foto) )
                                  <img src="{{ url($item->obra->foto) }}" height="90" class="margin image" />
                                @endif
                              </td>
                              <td>{{priceFormat($item->obra->valor_venda)}}</td>
                              <td><span class="badge bg-green">{{priceFormat($item->valor_obra)}}</span></td>
                          </tr>                        
                          @endforeach
                        </tbody>
                    </table>
                    <p class="badge bg-yellow">Selecione as obras negociadas. As demais serão retornadas</p>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-6">
                      <span class="pull-right">
                        <a id="cancel" href="{{ url('/galeria/consignacao') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
                        <button id="retornar_btn" type="submit" onclick="$('#retornar_tudo').val('S')" class="btn btn-primary"><i class="fa fa-sort-alpha-asc"></i> Retornar Tudo</buton>
                        <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Gerar Venda</button>
                      </span>
                    </div>
                </div>

              </div><!-- /x-content -->

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

@endsection