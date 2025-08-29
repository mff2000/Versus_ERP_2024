
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
          {{ (isset($bordero->id)) ? 'Editar' : 'Incluir' }} Borderô <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            

              <div class="x_content">
                  <form id="bordera-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('bordero') }}" novalidate>
                  {{ csrf_field() }}

                    <input id="id" type="hidden" name="id" value="{{ isset($bordero) ? $bordero->id : "" }}">

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Dados do Borderô </h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('tipo_bordero') ? ' has-error' : '' }}">
                          <label for="tipo_bordero" class="control-label">Tipo Borderô:*</label>
                          @if(isset($bordero))
                            <input id="tipo_bordero" type="hidden" name="tipo_bordero" value="{{ $bordero->tipo_bordero }}">
                            <input id="tipo_bordero_title" class="form-control" name="tipo_bordero_title" value="{{ ($bordero->tipo_bordero=='RCT') ? 'Borderô de Recebimento' : 'Borderô de Pagamento'  }}" type="text" readonly />
                          @else
                            {!! Form::select('tipo_bordero', [''=>'', 'RCT'=>'Recebimento', 'PGT'=>'Pagamento'], (isset($bordero)) ? $bordero->tipo_bordero : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'tipo_bordero', isset($bordero) ? 'disabled=>"disabled' : '')) !!}
                            @if ($errors->has('tipo_bordero'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('tipo_bordero') }}</strong>
                              </span>
                            @endif
                          @endif
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('descricao') ? ' has-error' : '' }}">
                          <label for="descricao" class="control-label">Descrição:</label>
                          <input id="descricao" class="form-control col-sm-2" name="descricao" value="{{ isset($bordero) ? $bordero->descricao : old('descricao') }}" type="text" required max-length="45" />
                          @if ($errors->has('descricao'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('descricao') }}</strong>
                              </span>
                            @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-9 item has-feedback {{ $errors->has('data_emissao') ? ' has-error' : '' }}">
                          <label for="data_emissao" class="control-label">Data Emissão:*</label>
                          @if(isset($bordero))
                            <input id="data_emissao" type="hidden" name="data_emissao" value="{{ convertDatePt($bordero->data_emissao) }}">
                            <input id="data_emissao_title" class="form-control has-feedback-left date" name="data_emissao_title" value="{{ convertDatePt($bordero->data_emissao)  }}" type="text" readonly />
                            <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                          @else
                            <input id="data_emissao" class="form-control has-feedback-left date" name="data_emissao" value="{{ isset($bordero) ? $bordero->data_emissao : (old('data_emissao')) ? old('data_emissao') : <?=date('d/m/Y')?>  }}" required type="text" {!! (isset($bordero)) ? 'disabled':'' !!}/>
                            <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                             @if ($errors->has('data_emissao'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('data_emissao') }}</strong>
                              </span>
                            @endif
                          @endif
                      </div>

                    </div>

                    <div class="form-group">

                      <div class="col-md-2 col-sm-2 col-xs-12">
                          <label for="quantidade" class="control-label">Qtd Agendamentos:</label>
                          <input id="quantidade" class="form-control numeric col-sm-2" name="quantidade" value="{{ isset($bordero) ? count($bordero->agendamentos) : 0 }}" type="text" readonly />
                      </div>

                      <div class="col-md-2 col-sm-2 col-xs-12">
                          <label for="valor" class="control-label">Valor:</label>
                          <input id="valor" class="form-control count green currency" name="valor" value="{{ isset($bordero) ? priceFormat($bordero->valor, false) : old('valor') }}" type="text" readonly/>
                      </div>

                      <div class="col-md-2 col-sm-2 col-xs-12">
                          <label for="saldo" class="control-label">Saldo:</label>
                          <input id="saldo" class="form-control count currency" name="saldo" value="{{ isset($bordero) ? priceFormat($bordero->saldo, false) : old('saldo') }}" type="text" readonly/>
                      </div>

                      <div class="col-md-6 col-sm-12 col-xs-12">
                          <label for="observacoes" class="control-label">Observações:</label>
                          <textarea id="observacoes" class="form-control" name="observacoes">{{ isset($bordero) ? $bordero->observacoes : old('observacoes') }}</textarea>
                      </div>

                    </div>

                    @if(isset($bordero))

                    <div class="form-group">
                        
                        <div class="x_panel">
                          
                            <div class="x_title">
                                <h2>Itens do Borderô <small></small></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                  </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>

                            <div class="x_content collaped" style="display:none">

                                <table class="table table-striped">
                                  <thead>
                                    <tr class="headings">
                                      <th>
                                        <input type="checkbox" id="check-all" class="flat">
                                      </th>
                                      <th class="column-title">Historico </th>
                                      <th class="column-title">Valor </th>
                                      <th class="column-title">Saldo </th>
                                      <th class="column-title">Favorecido </th>
                                      <th class="column-title">Número/Parc </th>
                                      <th class="column-title">Competência </th>
                                      <th class="column-title">Vencimento </th>
                                      <th class="column-title no-link last">
                                        <span class="nobr">Ação</span>
                                      </th>
                                      <th class="bulk-actions" colspan="7">
                                        <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                      </th>
                                    </tr>
                                  </thead>

                                  <tbody>
                                    @if(isset($bordero->agendamentos))
                                      @foreach($bordero->agendamentos as $agendamento)
                                        <tr class="even pointer">
                                          <th>
                                            <input type="checkbox" id="check-all" class="flat">
                                          </th>
                                          <td class=" ">{{$agendamento->historico}}</td>
                                          <td class="a-right a-right">{{priceFormat($agendamento->valor_titulo)}}</td>
                                          <td class="a-right a-right">{{priceFormat($agendamento->valor_saldo)}}</td>
                                          <td class=" ">{{$agendamento->favorecido->nome_fantasia}}</td>
                                          <td class=" ">{{$agendamento->numero_titulo}}</td>
                                          <td class="">{{convertDatePt($agendamento->data_competencia)}}</td>
                                          <td class="">{{convertDatePt($agendamento->data_vencimento)}}</td>
                                          <td class=" last" style="width:80px">
                                            <ul>
                                              <li style="float:left;">
                                                @if($bordero->valor == $bordero->saldo)
                                                <button onclick="window.location='{{ url('bordero/'.$agendamento->id.'/delete_agendamento') }}'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Retirar do Borderô" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-trash"></i> </button>
                                                @endif
                                              </li>
                                            </ul>
                                          </td>
                                        </tr>
                                      @endforeach
                                    @endif
                                  </tbody>
                                </table>

                                <p> Total de <code>{!! count($bordero->agendamentos) !!}</code> itens.</p>
                            
                            </div>
                        </div>

                    </div>
                    @endif

                    @if(isset($bordero) && count($bordero->lancamentos)>0)

                    <div class="form-group">
                        
                        <div class="x_panel">
                          
                          <div class="x_title">
                            <h2>Baixas do Borderô <small></small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>

                          <div class="x_content collaped" style="display:none">

                            <table class="table table-striped">
                            <thead>
                              <tr class="headings">
                                <th>
                                  <input type="checkbox" id="check-all" class="flat">
                                </th>
                                <th class="column-title">ID </th>
                                <th class="column-title">Tipo </th>
                                <th class="column-title">Histórico </th>
                                <th class="column-title">Data Lanç.</th>
                                <th class="column-title">Data Liq.</th>
                                <th class="column-title">Banco </th>
                                <th class="column-title">Valor </th>
                                <th class="column-title">Multa</th>
                                <th class="column-title">Juros</th>
                                <th class="column-title">Desconto</th>
                                <th class="column-title no-link last">
                                  <span class="nobr">Ação</span>
                                </th>
                              </tr>
                            </thead>

                            <tbody>
                                
                                @foreach ($bordero->lancamentos as $lancamento)

                                  <tr class="even pointer">
                                    <td class="a-center ">
                                      @if($lancamento->data_liquidacao)
                                          <span class="label label-success">L</span>
                                        @else
                                          <span class="label label-danger">A</span>
                                        @endif
                                    </td>
                                    <td class=" ">{{ $lancamento->id }} </td>
                                    <td class=" "><span class="label {!! ($lancamento->tipo_movimento=='RCT') ? 'label-primary' : 'label-danger' !!}">{{ $lancamento->tipo_movimento }}</span></td>
                                    <td class=" ">{{ $lancamento->historico }} </i></td>
                                    <td class=" ">{{ convertDatePt($lancamento->data_lancamento) }} </i></td>
                                    <td class=" ">{{ convertDatePt($lancamento->data_liquidacao) }} </i></td>
                                    <td class=" ">{{ $lancamento->conta->descricao }}</i></td>
                                    <td class="">{{ priceFormat($lancamento->valor_lancamento) }} </i></td>
                                    <td class="">{{ priceFormat($lancamento->valorMulta->valor_lancamento) }} </i></td>
                                    <td class="">{{ priceFormat($lancamento->valorJuros->valor_lancamento) }} </i></td>
                                    <td class="">{{ priceFormat($lancamento->valorDesconto->valor_lancamento) }} </i></td>
                                    <td class=" last">
                                      <ul>
                                        <li style="float:left;">
                                          <button onclick="window.location='{{url('bordero/excluiBaixa/'.$lancamento->baixa_id)}}'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Apagar" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-trash"></i> </button>
                                        </li>
                                      </ul>
                                    </td>
                                  </tr>
                                 @endforeach
                              

                            </tbody>
                            </table>

                            <p> Total de <code>{!! count($bordero->agendamentos) !!}</code> itens.</p>
                          </div>
                        
                        </div>

                    </div>
                    @endif

                    <div class="form-group">
                        <div class="col-md-3 col-md-offset-9">
                          <span class="pull-right">
                            <a id="cancel" href="{{ url('/bordero') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
                            @if(isset($bordero) && count($bordero->agendamentos)>0 && $bordero->saldo>0)
                              <a id="cancel" href="{{ url('/bordero/baixa/'.$bordero->id) }}" class="btn btn-primary"><i class="fa fa-money"></i> Baixar</a>
                            @endif
                            <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                          </span>
                        </div>
                    </div>

                    </form>

                    @if(isset($bordero) && $bordero->valor == $bordero->saldo)
                    <form id="bordero-filter" class="form-horizontal form-label-left mode2" method="POST" action="{{ url('bordero/busca_agendamento') }}" novalidate>
                        {{ csrf_field() }}

                        <input id="id" type="hidden" name="id" value="{{ isset($bordero) ? $bordero->id : "" }}">

                        <div class="x_title">
                          <h2><i class="fa fa-bars"></i> Incluir Agendamento </h2>
                          <div class="clearfix"></div>
                        </div>

                        <div class="form-group">

                          <div class="col-md-3 col-sm-3 col-xs-6 item has-feedback">
                              <label for="data_competencia" class="control-label">Data Competência:</label>
                              <input id="data_competencia" class="form-control has-feedback-left date-range" name="data_competencia" date="{{ isset($dataCompetencia) ? $dataCompetencia : '' }}" value="{{ (isset($dataCompetencia) ? $dataCompetencia : '' ) }}" type="text" />
                              <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                          </div>

                          <!-- <div class="col-md-2 col-sm-2 col-xs-6 item has-feedback">
                              <label for="data_competencia_fim" class="control-label">Data Competência Final:</label>
                              <input id="data_competencia_fim" class="form-control has-feedback-left date" name="data_competencia_fim" value="old('data_competencia_fim') : <?=date('d/m/Y')?>  }}" type="text" />
                              <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                          </div> -->

                          <div class="col-md-3 col-sm-3 col-xs-6 item has-feedback">
                              <label for="data_vencimento" class="control-label">Data Vencimento:</label>
                              <input id="data_vencimento" class="form-control has-feedback-left date-range" name="data_vencimento" date="{{ isset($dataVencimento) ? $dataVencimento : '' }}" value="{{ isset($dataVencimento) ? $dataVencimento : '' }}" type="text" />
                              <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                          </div>

                          <!-- <div class="col-md-2 col-sm-2 col-xs-6 item has-feedback">
                              <label for="data_vencimento_fim" class="control-label">Data Vencimento Inicial:</label>
                              <input id="data_vencimento_fim" class="form-control has-feedback-left date" name="data_vencimento_fim" value="old('data_vencimento_fim') : <?=date('d/m/Y')?>  }}" type="text" />
                              <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                          </div> -->

                          <div class="col-md-2 col-sm-2 col-xs-12">
                              <label for="historico" class="control-label">Histórico Contém:</label>
                              <input id="historico" class="form-control" name="historico" value="{{ isset($historico) ? $historico : '' }}" type="text" />
                          </div>

                          <div class="col-md-2 col-sm-2 col-xs-12">
                              <label for="favorecido" class="control-label">Favorecido Contém:</label>
                              <input id="favorecido" class="form-control" name="favorecido" value="{{ isset($favorecido) ? $favorecido : '' }}" type="text" />
                          </div>

                          <div class="col-md-2 col-sm-2 col-xs-12">
                            <label for="favorecido" class="control-label">&nbsp;</label>
                            <button id="send" type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                          </div>

                        </div>

                        <div class="form-group">
                            
                              <table class="table table-striped responsive-utilities jambo_table bulk_action">
                                <thead>
                                  <tr class="headings">
                                    <th>
                                      <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" id="check-all" class="flat" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
                                    </th>
                                    <th class="column-title">Historico </th>
                                    <th class="column-title">Valor </th>
                                    <th class="column-title">Saldo </th>
                                    <th class="column-title">Favorecido </th>
                                    <th class="column-title">Número/Parc </th>
                                    <th class="column-title">Competência </th>
                                    <th class="column-title">Vencimento </th>
                                    </th>
                                    <th class="bulk-actions" colspan="7">
                                      <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                                    </th>
                                  </tr>
                                </thead>

                                <tbody>
                                  @if(isset($agendamentos))
                                    @foreach($agendamentos as $agendamento)
                                      <tr class="even pointer">
                                        <td class="a-center ">
                                          
                                            <input type="checkbox" class="flat" name="agendamento_id[{{$agendamento->id}}]" style="position: relative; opacity: 1;">
                                            
                                        </td>
                                        <td class=" ">{{$agendamento->historico}}</td>
                                        <td class="a-right a-right">{{ priceFormat($agendamento->valor_titulo) }}</td>
                                        <td class="a-right a-right ">{{ priceFormat($agendamento->valor_saldo) }}</td>
                                        <td class=" ">{{$agendamento->favorecido->nome_fantasia}}</td>
                                        <td class=" ">{{$agendamento->numero_titulo}}/{{$agendamento->numero_parcela}}</td>
                                        <td class="">{{convertDatePt($agendamento->data_competencia)}}</td>
                                        <td class="">{{convertDatePt($agendamento->data_vencimento)}}</td>
                                        </td>
                                      </tr>
                                    @endforeach
                                  @endif
                                </tbody>
                              </table>
                            @if(isset($agendamentos))
                            <p> Total de <code>{!! count($agendamentos) !!}</code> itens.</p>
                            @endif
                        </div>

                        <div class="form-group">
                          <div class="col-md-3 col-md-offset-9">
                            <span class="pull-right">
                              <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Incluir Agendamentos</button>
                            </span>
                          </div>
                      </div>

                    </form>
                    @endif

              </div><!-- /x-content -->

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->


<!-- /datepicker -->

@endsection