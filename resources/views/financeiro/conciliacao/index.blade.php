
@extends('layouts.app')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>Conciliação Bancária</h3>
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

            
              <div class="x_content">
                  
                    <form id="bordero-filter" class="form-horizontal form-label-left mode2" method="GET" action="{{ url('conciliacao') }}" novalidate>
                        {{ csrf_field() }}

                        <div class="x_title">
                          <h2><i class="fa fa-bars"></i> Conciliação Bancária </h2>
                          <div class="clearfix"></div>
                        </div>

                        <div class="form-group">

                          <div class="col-md-3 col-sm-3 col-xs-6 item">
                              <label for="banco_id" class="control-label">Banco:*</label>
                              {!! Form::select('banco_id', $bancos, (isset($banco_id)) ? $banco_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'banco_id')) !!}
                          </div>

                          <div class="col-md-3 col-sm-3 col-xs-6 item has-feedback">
                              <label for="data_lancamento" class="control-label">Período de Lançamento:</label>
                              <input id="data_lancamento" class="form-control has-feedback-left date-range" name="data_lancamento" date="{{ (isset($data_lancamento) ? $data_lancamento : '' ) }}" value="{{ (isset($data_lancamento) ? $data_lancamento : '' ) }}" type="text" />
                              <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                          </div>

                          <div class="col-md-2 col-sm-2 col-xs-12">
                            <label for="favorecido" class="control-label">&nbsp;</label>
                            <button id="send" type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                          </div>

                        </div>

                        <div class="form-group">
                              <p class="lead">Agendamentos</p>
                              <table class="table table-striped responsive-utilities jambo_table bulk_action">
                                <thead>
                                  <tr class="headings">
                                    <th>
                                      <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" id="check-all" class="flat" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
                                    </th>
                                    <th class="column-title">Data Lançamento </th>
                                    <th class="column-title">Histórico </th>
                                    <th class="column-title">Favorecido </th>
                                    <th class="column-title">Número/Parc </th>
                                    <th class="column-title">Valor </th>
                                    <th class="bulk-actions" colspan="7">
                                      <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                    </th>
                                  </tr>
                                </thead>

                                <tbody>
                                  @if(isset($lancamentos))
                                    @foreach($lancamentos as $lancamento)
                                      <tr class="even pointer">
                                        <td class="a-center ">
                                          
                                            <input type="checkbox" class="flat" name="lancamento_id[{{$lancamento->id}}]" style="position: relative; opacity: 1;">
                                            
                                        </td>
                                        <td class=" ">{{convertDatePt($lancamento->data_lancamento)}}</td>
                                        <td class="a-right a-right">{{ $lancamento->historico }}</td>
                                        <td class=" ">{{ $lancamento->favorecido->nome_fantasia }}</td>
                                        <td class=" ">{{$lancamento->numero_titulo}}/{{$lancamento->numero_parcela}}</td>
                                        <td class="a-right a-right">
                                          @if($lancamento->tipo_movimento == 'PGT')
                                            <span style="color:#E74C3C">-{{priceFormat($lancamento->valor_lancamento, false)}}</span>
                                          @else
                                            <span style="color:#3498DB">+{{priceFormat($lancamento->valor_lancamento, false)}}</span>
                                          @endif
                                        </td>
                                      </tr>
                                    @endforeach
                                  @endif
                                </tbody>
                              </table>

                              <p> Total de <code>{!! count($lancamentos) !!}</code> itens.</p>
                            
                        </div>

                        <div class="form-group">
                              <p class="lead">Borderôs</p>
                              <table class="table table-striped responsive-utilities jambo_table bulk_action">
                                <thead>
                                  <tr class="headings">
                                    <th>
                                      <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" id="check-all" class="flat" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
                                    </th>
                                    <th class="column-title">Data Lançamento </th>
                                    <th class="column-title">Histórico </th>
                                    <th class="column-title">Favorecido </th>
                                    <th class="column-title">Número/Parc </th>
                                    <th class="column-title">Valor </th>
                                    <th class="bulk-actions" colspan="7">
                                      <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                    </th>
                                  </tr>
                                </thead>
                                @if(isset($borderos))
                                <tbody>
                                  
                                    @foreach($borderos as $bordero)
                                      <tr class="even pointer">
                                        <td class="a-center ">
                                          
                                            <input type="checkbox" class="flat" name="lancamento_baixa_id[{{$bordero->baixa_id}}]" style="position: relative; opacity: 1;">
                                            
                                        </td>
                                        <td class=" ">{{convertDatePt($bordero->data_lancamento)}}</td>
                                        <td class="a-right a-right">{{ $bordero->historico }}</td>
                                        <td class=" ">{{ $bordero->favorecido->nome_fantasia }}</td>
                                        <td class=" ">{{$bordero->numero_titulo}}/{{$bordero->numero_parcela}}</td>
                                        <td class="a-right a-right">
                                          @if($bordero->tipo_movimento == 'PGT')
                                            <span style="color:#E74C3C">-{{priceFormat($bordero->valor_lancamento)}}</span>
                                          @else
                                            <span style="color:#3498DB">+{{priceFormat($bordero->valor_lancamento)}}</span>
                                          @endif
                                        </td>
                                      </tr>
                                    @endforeach
                                  
                                </tbody>

                                @endif
                              </table>
                              <p> Total de <code>{!! count($borderos) !!}</code> itens.</p>
                        </div>

                        <div class="form-group">
                          <div class="has-feedback col-sm-3 col-md-offset-6">
                              <label for="data_emissao" class="control-label">Data de Liquidação:*</label>
                              <input id="data_liquidado" class="form-control has-feedback-left date" name="data_liquidado" value="" type="text" />
                              <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                          </div>
                          <div class="col-md-3">
                            <span class="pull-right">
                              <label for="data_emissao" class="control-label"></label><br />  
                              <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Conciliar Marcados</button>
                            </span>
                          </div>
                      </div>

                    </form>

              </div><!-- /x-content -->

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->


@endsection