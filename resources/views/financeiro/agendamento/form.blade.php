
@extends('layouts.app')

@section('content')

<?php
  /* Situacao agendamento
   *   0 = Novo
   *   1 = Aberto
   *   2 = Baixa Parcial
   *   3 = Baixado 
   */
  $situacao = 0;
  if(isset($agendamento)) {
    if($agendamento->valor_saldo == 0) {
      $situacao = 3;
    } elseif($agendamento->valor_titulo > $agendamento->valor_saldo) {
      $situacao = 2;
    } else {
      $situacao = 1;
    }
  }

?>

<div class="page-title">
    <div class="title_left">
        <h3>
          {!! (isset($agendamento)) ? 'Editar' : 'Agendar' !!} {!! ($tipoMovimento == 'PGT') ? 'Pagamento' : 'Recebimento' !!}
          <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right">
          @if($situacao<3 && $situacao>0 && !(isset($agendamento->bordero_id)))
          <a class="btn btn-sm btn-primary" href="{{ url('agendamento/baixa/'.$agendamento->id) }}"><i class="fa fa-plus-square"></i> Baixar {!! ($tipoMovimento == 'PGT') ? 'Pagamento' : 'Recebimento' !!}</a>
          @endif
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

            <form id="agendamento-form" class="form-horizontal form-label-left mode2" method="POST" action="{{ url('agendamento') }}" novalidate onsubmit="return false">
            {{ csrf_field() }}

                <div class="x_content">

                    <input id="id_agendamento" type="hidden" name="id" value="{{ isset($agendamento) ? $agendamento->id : 0 }}">
                    <input id="tipo_agendamento" type="hidden" name="tipo_movimento" value="{{ isset($agendamento) ? $agendamento->tipo_movimento : $tipoMovimento }}">

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
          
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Dados do Agendamento</a>
                          </li>
                          @if(!isset($agendamento))
                          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Repetição</a>
                          </li>
                          @endif
                        </ul>

                        <div id="myTabContent" class="tab-content">

                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                                <div class="form-group">
                              
                                  <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('historico') ? ' has-error' : '' }}">
                                      <label for="historico" class="control-label">Histórico:*</label>
                                      <input id="historico" class="form-control" name="historico" value="{{ isset($agendamento) ? $agendamento->historico : old('historico') }}"  required="required"  {{ ($situacao>1) ? 'readonly=>true' : '' }}  type="text">
                                      @if ($errors->has('historico'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('historico') }}</strong>
                                        </span>
                                      @endif
                                  </div>

                                  @if($situacao==0)
                                  <div class="col-md-4 col-sm-4 col-xs-6 item {{ $errors->has('favorecido_id') ? ' has-error' : '' }}">
                                      <label for="favorecido_id" class="control-label">Favorecido:*</label>
                                      {!! Form::select('favorecido_id', $favorecidos, isset($agendamento) ? $agendamento->favorecido_id : old('favorecido_id'), array('class'=>'form-control', 'id' => 'favorecido_id', 'required'=> 'required')) !!}
                                      @if ($errors->has('favorecido_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('favorecido_id') }}</strong>
                                        </span>
                                      @endif
                                  </div>
                                  @else 
                                  <div class="col-md-4 col-sm-4 col-xs-6 item {{ $errors->has('historico') ? ' has-error' : '' }}">
                                      <input id="favorecido_id" name="favorecido_id" value="{{ isset($agendamento) ? $agendamento->favorecido_id : old('favorecido_id') }}"  required="required"  type="hidden">
                                      <label for="historico" class="control-label">Favorecido:*</label>
                                      <input class="form-control" name="favorecido_nome" value="{{ isset($agendamento) ? $agendamento->favorecido_id. ' - '. $agendamento->favorecido->nome_fantasia : '' }}" disabled="disabled"  type="text">
                                  </div>
                                  @endif

                                  <div class="col-md-2 col-sm-2 col-xs-62 item {{ $errors->has('numero_titulo') ? ' has-error' : '' }}">
                                      <label for="numero_titulo" class="control-label">Número Documento:*</label>
                                      <input id="numero_titulo" class="form-control numeric" name="numero_titulo" value="{{ isset($agendamento) ? $agendamento->numero_titulo : old('numero_titulo') }}" {{ ($situacao>1) ? 'readonly=>true' : '' }}  required="required"  type="text">
                                      @if ($errors->has('numero_titulo'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('numero_titulo') }}</strong>
                                        </span>
                                      @endif
                                  </div>

                                </div> 

                                <div class="form-group">
                                  
                                  <div class="col-md-3 col-sm-3 col-xs-6 item has-feedback {{ $errors->has('valor_titulo') ? ' has-error' : '' }}">
                                      <label for="valor_titulo" class="control-label">Valor:*</label>
                                      <input id="valor_titulo" class="form-control has-feedback-left currency" name="valor_titulo" value="{{ isset($agendamento) ? $agendamento->valor_titulo : old('valor_titulo') }}"  required="required" {{ ($situacao>1) ? 'readonly=>true' : '' }} type="text" onblur="$('#tblAppendGrid_rateio_valor_1').val(this.value)">
                                      <span class="fa fa-money form-control-feedback left" aria-hidden="true"></span>
                                      @if ($errors->has('valor_titulo'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('valor_titulo') }}</strong>
                                        </span>
                                      @endif
                                  </div>

                                  <div class="col-md-3 col-sm-3 col-xs-6 item has-feedback {{ $errors->has('data_competencia') ? ' has-error' : '' }}">
                                      <label for="data_competencia" class="control-label">Competência:*</label>
                                      <input id="data_competencia" class="form-control has-feedback-left date" name="data_competencia" value="{{ isset($agendamento) ? convertDatePt($agendamento->data_competencia) : old('data_competencia') }}"  {{ ($situacao>1) ? 'readonly=>true' : '' }}  required="required"  type="text">
                                      <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                                      @if ($errors->has('data_competencia'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('data_competencia') }}</strong>
                                        </span>
                                      @endif
                                  </div>

                                  <div class="col-md-3 col-sm-3 col-xs-6 item has-feedback {{ $errors->has('data_vencimento') ? ' has-error' : '' }}">
                                      <label for="data_vencimento" class="control-label">Vencimento:*</label>
                                      <input id="data_vencimento" class="form-control has-feedback-left date" name="data_vencimento" value="{{ isset($agendamento) ? convertDatePt($agendamento->data_vencimento) : old('data_vencimento') }}"  {{ ($situacao>1) ? 'readonly=>true' : '' }}  required="required"  type="text">
                                      <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                                      @if ($errors->has('data_vencimento'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('data_vencimento') }}</strong>
                                        </span>
                                      @endif
                                  </div>

                                  <div class="col-md-3 col-sm-3 col-xs-6 item">
                                      <label for="correcao_financeira_id" class="control-label">Correção Financeira:*</label>
                                      {!! Form::select('correcao_financeira_id', getOnlyChildPlanosContas($correcoesFinanceira), (isset($agendamento)) ? $agendamento->correcao_financeira_id : null, array('class'=>'form-control', ($situacao>1) ? 'disabled' : '', 'id' => 'correcao_financeira_id')) !!}
                                  </div>

                                </div>

                                <div class="form-group">

                                  @if(!isset($agendamento))
                                  <div class="col-md-12 col-sm-12 col-xs-12 item">
                                      <label for="tags" class="control-label">Marcadores:</label>
                                      <input id="tags" class="form-control" name="tags" value="{{ isset($agendamento) ? $agendamento->tags : old('tags') }}"  type="text">
                                  </div>
                                  @else
                                  <div class="col-md-3 col-sm-3 col-xs-6 item">
                                      <label for="valor_saldo" class="control-label">Saldo Atual:</label>
                                      <input id="valor_saldo" class="form-control currency" name="valor_saldo" value="{{ isset($agendamento) ? $agendamento->valor_saldo : old('valor_saldo') }}" readonly="true"  type="text">
                                  </div>
                                  @endif
                                </div>

                                <div class="form-group">

                                    <div class="alert alert-danger alert-dismissible" id="notificationRateio" style="display:none">
                                        <i class="icon-exclamation-sign"></i> {!! 'Erro ao validar Rateio, por favor verifique novamente seu dados.' !!}
                                        <ul id="ulError">
                                        </ul>
                                    </div>
                                    <!--Prepare a table element as the grid-->
                                    <table id="tblAppendGrid" style="width:100%"></table>

                                </div>

                            </div>

                            <!-- Repetir -->
                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">


                                <div class="form-group" id="parcelamento">
                                    
                                    <div class="col-md-4 col-sm-4 col-xs-6 item has-feedback" id="avancado">
                                        <label for="periodo_repeticao" class="control-label">Esse agendamento se repete?</label>
                                        {!! Form::select('periodo_repeticao', getPeriodoRepeticao(), (isset($agendamento)) ? $agendamento->periodo_repeticao : null, array('class'=>'form-control', 'id' => 'periodo_repeticao', 'required'=> 'required')) !!}
                                    </div>

                                    <div class="col-md-4 col-sm-4 col-xs-6 item has-feedback" id="avancado">
                                        <label for="tipo_competencia" class="control-label">Quanto à data de competencia?</label>
                                        {!! Form::select('tipo_competencia', getDataCompetenciaRepeticao(), (isset($agendamento)) ? $agendamento->tipo_competencia : null, array('class'=>'form-control', 'id' => 'tipo_competencia', 'required'=> 'required')) !!}
                                    </div>

                                    <div class="col-md-2 col-sm-2 col-xs-6 item">
                                        <label for="parcela_inicial" class="control-label">Parcela Inicial:</label>
                                        <input id="parcela_inicial" class="form-control numeric" name="parcela_inicial" value="{{ isset($agendamento) ? $agendamento->parcela_inicial : (old('parcela_inicial')) ? old('parcela_inicial')  : 1}}" type="text"  />
                                    </div>
                                    
                                    <div class="col-md-2 col-sm-2 col-xs-6 item">
                                      <label for="quantidade_parcelas" class="control-label">Quantas vezes?:</label>
                                      <input id="quantidade_parcelas" class="form-control numeric" name="quantidade_parcelas" value="{{ isset($agendamento) ? $agendamento->quantidade_parcelas :  (old('quantidade_parcelas')) ? old('quantidade_parcelas')  : 1 }}"  type="text">
                                    </div>

                                </div>

                            </div>

                        </div><!-- tab content -->

                    </div>

                </div>

                <!-- BUTTONS -->
                <div class="form-group">
                    <div class="col-md-12">
                      <span class="pull-right">
                        <a id="cancel" href="{{ url('/agendamento') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Voltar</a>
                        
                        @if( $situacao < 3 || (isset($agendamento) && $agendamento->bordero_id == NULL) )
                          <button id="send_baixa" type="submit" class="btn btn-primary" onclick="$('#bt_action').val('send_baixa')"><i class="fa fa-save"></i> Salvar e Baixar</button>
                        @endif
                        
                        <button id="send_new" type="submit" class="btn btn-success" onclick="$('#bt_action').val('send_new')"><i class="fa fa-save"></i> Salvar e Novo</button>
                        <button id="send_back" type="submit" class="btn btn-primary"  onclick="$('#bt_action').val('send_back')"><i class="fa fa-save"></i> Salvar e Voltar</button>
                        
                        <input type="hidden" name="action" value="send_back" id="bt_action" />
                      </span>
                    </div>
                </div>

            </form>

            @if(isset($agendamento))
              <div class="form-group">
                  
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Baixar Realizadas <small></small></h2>
                      <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </li>
                      </ul>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content collaped">

                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Sequência</th>
                            <th>ID</th>
                            <th>Data Lançamento</th>
                            <th>Tipo</th>
                            <th>Conta</th>
                            <th>Histórico</th>
                            <th style="text-align:right">Valor</th>
                            <th style="text-align:right">Multa</th>
                            <th style="text-align:right">Juros</th>
                            <th style="text-align:right">Desconto</th>
                            <th class="column-title no-link last">
                              <span class="nobr">Ação</span>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $count = 1 ?>
                          @foreach($agendamento->lancamentos as $lancamento)
                         
                          <tr>
                            <th scope="row">{{$count}}</th>
                            <td>{{$lancamento->id}}</td>
                            <td>{{convertDatePt($lancamento->data_lancamento)}}</td>
                            <td>{{$lancamento->tipo_baixa}}</td>
                            <td>{{$lancamento->conta->descricao}}</td>
                            <td>{{$lancamento->historico}}</td>
                            <td class="currency" style="text-align:right">{{$lancamento->valor_lancamento}}</td>
                            <td class="currency" style="text-align:right">
                              @if($lancamento->valorMulta)
                                {{ priceFormat($lancamento->valorMulta->valor_lancamento) }}
                              @else
                                {{ priceFormat(0) }}
                              @endif
                            </td>
                            <td class="currency" style="text-align:right">
                              @if($lancamento->valorJuros)
                                {{ priceFormat($lancamento->valorJuros->valor_lancamento) }}
                              @else
                                {{ priceFormat(0) }}
                              @endif
                            </td>
                            <td class="currency" style="text-align:right">
                              @if($lancamento->valorDesconto)
                                {{ priceFormat($lancamento->valorDesconto->valor_lancamento) }}
                              @else
                                {{ priceFormat(0) }}
                              @endif
                            </td>
                            <td class=" last">
                              <ul>
                                  <li style="float:left;">
                                    <button onclick="window.location='{{url('agendamento/excluiBaixa/'.$lancamento->id)}}'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Apagar" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-trash"></i> </button>
                                  </li>
                              </ul>
                            </td>
                          </tr>
                          
                          <?php $count++; ?>
                          
                          @endforeach
                        </tbody>
                      </table>

                    </div>
                  </div>

              </div>
              @endif
        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->

<!--Reference the CSS and JS files #appendGrid-->

<link href="{{ URL::asset('css/jquery.appendGrid-1.6.2.css') }}" rel="stylesheet"/>
<script src="{{ URL::asset('js/jquery.appendGrid-1.6.2.js') }}"></script>
<script type="text/javascript">

// Initial the grid as you needed
$(function() {
    $('#tblAppendGrid').appendGrid({
       // Define a function to generate a customized caption
       caption: 'Rateio',
        initRows: 1,
        columns: [
            { name: 'plano_conta_id', display: 'Plano de Conta', type: 'select', displayCss: { 'width': '20%' }, ctrlOptions:  { <?php foreach (getOnlyChildPlanosContas($planosContas) as $key => $value ) echo $key.':"'.$value.'",'  ?> }, ctrlAttr: { 'style': 'width:95%', <?=($situacao>1) ? "'disabled': 'disabled'":""?> }  },
            { name: 'centro_resultado_id', display: 'Centro de Resultado', type: 'select', displayCss: { 'width': '20%' }, ctrlOptions:  { <?php foreach (getOnlyChildPlanosContas($centrosResultados) as $key => $value ) echo $key.":'".$value."',"  ?> }, ctrlAttr: { 'style': 'width:95%', <?=($situacao>1) ? "'disabled': 'disabled'":""?> } },
            { name: 'projeto_id', display: 'Projeto', type: 'select', displayCss: { 'width': '20%' }, ctrlOptions: { <?php foreach (getOnlyChildPlanosContas($projetos) as $key => $value ) echo $key.":'".$value."',"  ?> }, ctrlAttr: { 'style': 'width:95%', <?=($situacao>1) ? "'disabled': 'disabled'":""?> } },
            { name: 'rateio_porcentagem', display: 'Porcentagem (%)', type: 'text', ctrlAttr: { maxlength: 10, <?=($situacao>1) ? "'disabled': 'disabled'":""?> }, ctrlCss: { width: '95%', 'text-align': 'right' }, value: 100, ctrlClass:"form-control percentage",
            onChange: function (evt, rowIndex) {
                    sumPercento(rowIndex, getValueAgendamento());
                }
            },
            { name: 'rateio_valor', display: 'Valor', type: 'text', ctrlAttr: { maxlength: 10, <?=($situacao>1) ? "'disabled': 'disabled'":""?> }, ctrlCss: { width: '95px', 'text-align': 'right' }, value: 0, ctrlClass:"form-control currency",
            onChange: function (evt, rowIndex) {
                    calcPercento(rowIndex, getValueAgendamento());
                }
            },
            { name: 'RecordId', type: 'hidden', value: 0 }
        ],
        <?php if(isset($agendamento)) { ?>
        initData: [
            <?php foreach ($agendamento->rateios as $rateio) { ?>
              { 'RecordId': '<?=$rateio->id?>', 'plano_conta_id': '<?=$rateio->plano_conta_id?>', 'centro_resultado_id': '<?=$rateio->centro_resultado_id?>', 'projeto_id': '<?=$rateio->projeto_id?>', 'rateio_porcentagem': <?=$rateio->porcentagem?>, 'rateio_valor': <?=$rateio->valor?>, 'RecordId': <?=$rateio->id?> },
            <?php
              }
            ?>
        ],
        <?php
            }
        ?>
        afterRowAppended: function(caller, parentRowIndex, addedRowIndex) {
          allMask();
          var rowCount = $('#tblAppendGrid').appendGrid('getRowCount');
          
          if(rowCount == 2) {
            $('#tblAppendGrid').appendGrid('setCtrlValue', 'rateio_porcentagem', 0, 60);
            $('#tblAppendGrid').appendGrid('setCtrlValue', 'rateio_porcentagem', 1, 40);
            calcValues(getValueAgendamento());
          }
        },
        <?php if($situacao>1) { ?>
        hideButtons: {
            append: true,
            removeLast: true,
            insert: true,
            remove: true
        }
        <?php
            }
        ?>
    });

    

});

$(document).ready(function() {
  $('#tblAppendGrid_plano_conta_id_1').select2();
  $('#tblAppendGrid_centro_resultado_id_1').select2();
  $('#tblAppendGrid_projeto_id_1').select2();
});

function sumPercento(rowIndex, valueTotal) {

  // Get the row count
  var rowCount = $('#tblAppendGrid').appendGrid('getRowCount');
  var perTotal = 100;
  var perLine = 0;
  var valueOtherLinesSum = 0;
  //console.log("ini"+perLine)
  for(var i=0; i<rowCount; i++) {
      
      porcentagem = parseFloat( $('#tblAppendGrid').appendGrid('getCtrlValue', 'rateio_porcentagem', i) );
      
      // soma os valores das outros linhas 
      // para completar o da linha atual(mudado), caso seja maior que 100%
      if(i!=rowIndex)
        valueOtherLinesSum += porcentagem;

      perLine += porcentagem;
  }

  if(perLine < 100) {
      $('#tblAppendGrid').appendGrid('insertRow', 
        [
          { rateio_porcentagem: perTotal-perLine, rateio_valor: (valueTotal* (perTotal-perLine)) / perTotal }
        ]
        , 0);
  } else {
    $('#tblAppendGrid').appendGrid('setCtrlValue', 'rateio_porcentagem', rowIndex, 100-valueOtherLinesSum );
  }
  allMask();
  calcValues(valueTotal);
}

function calcValues(valueTotal) {
    // define o valor de acordo a porcentagem
  var rowCount = $('#tblAppendGrid').appendGrid('getRowCount');
  for(var i=0; i<rowCount; i++) {
      percentage = parseFloat( $('#tblAppendGrid').appendGrid('getCtrlValue', 'rateio_porcentagem', i) );
      $('#tblAppendGrid').appendGrid('setCtrlValue', 'rateio_valor', i, formatMoney( (valueTotal*percentage) / 100 ) );
  }
}

function calcPercento(rowIndex, valueTotal) {
  
  var value = $('#tblAppendGrid').appendGrid('getCtrlValue', 'rateio_valor', rowIndex);
  var value = parseFloat( value.replace(/,/g, "") );
  $('#tblAppendGrid').appendGrid('setCtrlValue', 'rateio_porcentagem', rowIndex, formatMoney( (100*value) / valueTotal ) );

  sumPercento(rowIndex, valueTotal);
}

$('#agendamento-form').submit(function(e){
    e.preventDefault();
    var submit = true;
    // you can put your own custom validations below
    submit = validateRateio()

    // check all the required fields
    if( validator.checkAll( $(this) ) && submit )
        this.submit();

    return false;
});

function validateRateio() {
    
    var submit = true;

    // if exists empty rows
    var rowCount = $('#tblAppendGrid').appendGrid('getRowCount'), index = '';
    for (var z = 0; z < rowCount; z++) {
        if ($('#tblAppendGrid').appendGrid('isRowEmpty', z)) {
            index = index + ',' + (z + 1);
        }
    }
    if (index != '') {
        alert('As seguintes linhas do rateio estão vazias:\n' + index.substring(1));
        submit = false;
    }

    var totalPorcentagem = 0;
    var errorUl = $('#ulError');
    errorUl.html("");
    for(var i=0; i<rowCount; i++) {
        
        var data = $('#tblAppendGrid').appendGrid('getRowValue', i);
        
        totalPorcentagem += parseFloat(data['rateio_porcentagem']);

        if(data['plano_conta_id'] == 0 || data['plano_conta_id'] == "") {
          submit = false;
          errorUl.append('<li>Selecione o Plano de Conta da linha '+ (i+1) + '</li>');
        }

        if(data['centro_resultado_id'] == 0 || data['centro_resultado_id'] == "") {
          submit = false;
          errorUl.append('<li>Selecione o Centro de Resultado da linha '+ (i+1) + '</li>');
        }

        if(data['projeto_id'] == 0 || data['projeto_id'] == "") {
          submit = false;
          errorUl.append('<li>Selecione o Projeto da linha '+ (i+1) + '</li>');
        }

        if(data['rateio_valor'] == 0 || data['rateio_valor'] == "") {
          submit = false;
          errorUl.append('<li>Defina o Valor da linha '+ (i+1) + '</li>');
        }

        if(totalPorcentagem > 100) {
          submit = false;
          errorUl.append('<li>Cálculo da porcentagem maior que 100%, favor corrigir.</li>');
        }

    }
   
    if( errorUl.find("li").length > 0) {
      $("#notificationRateio").fadeIn();
    } else {
       $("#notificationRateio").hide();
    }

    return submit;
}

function getValueAgendamento() {
  return formatFloat( $('#valor_titulo').val() );
}


</script>
@endsection