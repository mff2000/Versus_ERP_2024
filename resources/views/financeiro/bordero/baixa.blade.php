
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Baixar Borderô <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="bordero-baixa-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('bordero/baixa') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                    <input id="bordero_id" type="hidden" name="bordero_id" value="{{ isset($bordero) ? $bordero->id : 0 }}">

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Dados do Borderô </h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                      
                      <div class="col-md-6 col-sm-6 col-xs-12 item}}">
                          <label for="descricao" class="control-label">Descrição:</label>
                          <input id="descricao" class="form-control" name="descricao" value="{{ isset($bordero) ? $bordero->descricao : old('descricao') }}" disabled="disabled"  type="text">
                      </div>
                      
                      <div class="col-md-3 col-sm-3 col-xs-12 item">
                          <label for="data_emissao" class="control-label">Data Emissão:</label>
                          <input id="data_emissao" class="form-control" name="data_emissao" value="{{ isset($bordero) ? convertDatePt($bordero->data_emissao) : old('data_emissao') }}" disabled="disabled"   type="text">
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12 item">
                          <label for="tipo_bordero" class="control-label">Tipo:</label>
                          <input id="tipo_bordero" class="form-control" name="tipo_bordero" value="{{ ($bordero->tipo_bordero=='RCT') ? 'Borderô de Recebimento' : 'Borderô de Pagamento' }}" disabled="disabled"   type="text">
                      </div>

                    </div> 

                    <div class="form-group">
                      
                      <div class="col-md-2 col-sm-2 col-xs-6 item">
                          <label for="qt_titulo" class="control-label">Quant. Títulos:</label>
                          <input id="qt_titulo" class="form-control" name="qt_titulo" value="{{ isset($bordero) ? count($bordero->agendamentos) : 0 }}"  disabled="disabled"  type="text">
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                          <label for="valor" class="control-label">Valor R$:</label>
                          <input id="valor" class="form-control currency" name="valor" value="{{ isset($bordero) ? $bordero->valor : old('valor') }}"  disabled="disabled"  type="text">
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                        <label for="date" class="control-label">Saldo R$:</label>
                        <input id="date" class="form-control currency" name="saldo" value="{{ isset($bordero) ? $bordero->saldo : old('saldo') }}" disabled="disabled" type="text">
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-6 item">
                        <label for="observacoes" class="control-label">Observações:</label>
                        <input id="observacoes" class="form-control date" name="observacoes" value="{{ isset($bordero) ? $bordero->observacoes : old('observacoes') }}" disabled="disabled" type="text">
                      </div>

                    </div>

                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Itens do Borderô <small>Agendamentos Relacionados</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                          <li>
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                          </li>
                        </ul>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content" style="display:none">
                          
                          <table class="table table-striped">
                            <thead>
                              <tr class="headings">
                                <th class="column-title">Historico </th>
                                <th class="column-title">Valor </th>
                                <th class="column-title">Saldo </th>
                                <th class="column-title">Favorecido </th>
                                <th class="column-title">Número/Parc </th>
                                <th class="column-title">Competência </th>
                                <th class="column-title">Vencimento </th>
                              </tr>
                            </thead>

                            <tbody>
                              @if(isset($bordero->agendamentos))
                                @foreach($bordero->agendamentos as $agendamento)
                                  <tr class="even pointer">
                                    <td class=" ">{{$agendamento->historico}}</td>
                                    <td class="a-right a-right">{{priceFormat($agendamento->valor_titulo)}}</td>
                                    <td class="a-right a-right">{{priceFormat($agendamento->valor_saldo)}}</td>
                                    <td class=" ">{{$agendamento->favorecido->nome_fantasia}}</td>
                                    <td class=" ">{{$agendamento->numero_titulo}}</td>
                                    <td class="">{{convertDatePt($agendamento->data_competencia)}}</td>
                                    <td class="">{{convertDatePt($agendamento->data_vencimento)}}</td>
                                  </tr>
                                @endforeach
                              @endif
                            </tbody>
                          </table>

                        <p> Total de <code>{!! count($bordero->lancamentos) !!}</code> itens.</p>

                      </div>
                    </div>

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Dados da baixa </h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                    
                      <div class="col-md-6 col-sm-6 col-xs-6 item">
                          <label for="historico" class="control-label">Histórico da Baixa:*</label>
                          <input id="historico" class="form-control" name="historico" value="{{ isset($bordero) ? $bordero->historico : old('historico') }}" required type="text">
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12 has-feedback item">
                          <label for="data_lancamento" class="control-label">Data Baixa:</label>
                          <input id="data_lancamento" class="form-control has-feedback-left date" name="data_lancamento" value="{{ date('d/m/Y') }}" type="text">
                          <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12 tile_stats_count">
                          <label for="numero_cheque" class="control-label">Saldo à Pagar:</label>
                          <div class="count red currency">{{ isset($bordero) ? $bordero->saldo : old('saldo') }}</div>
                      </div>
                    
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                          <label for="forma_financeira_id" class="control-label">Forma Financeira:*</label><br>
                          {!! Form::select('forma_financeira_id', $formasFinanceira, (isset($lancamento)) ? $lancamento->forma_financeira_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'forma_financeira_id')) !!}
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                          <label for="desconto_id" class="control-label">Desconto:</label><br>
                          {!! Form::select('desconto_id', $descontos, (isset($lancamento)) ? $lancamento->desconto_id : null, array('class'=>'form-control', 'id' => 'desconto_id')) !!}
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                          <label for="banco_id" class="control-label">Banco:*</label><br>
                          {!! Form::select('banco_id', $bancos, (isset($lancamento)) ? $lancamento->banco_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'banco_id')) !!}
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12">
                          <label for="valor_multa" class="control-label">Multa:</label>
                          <input id="valor_multa" class="form-control count red input-sm currency" name="valor_multa" value="{{ $valorMulta }}" type="text" onChange="getValorLancamento();">
                      </div>

                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-12 col-sm-offset-9">
                          <label for="valor_juros" class="control-label">Juros:</label>
                          <input id="valor_juros" class="form-control count red input-sm currency" name="valor_juros" value="{{ $valorJuros }}" type="text" onChange="getValorLancamento();">
                      </div>
                      
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-12 col-sm-offset-9">
                          <label for="valor_desconto" class="control-label">Desconto:</label>
                          <input id="valor_desconto" class="form-control count blue input-sm currency" name="valor_desconto" value="{{ $valorDesconto }}" type="text" onChange="getValorLancamento();">
                      </div>
                      
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-12 tile_stats_count col-sm-offset-9">
                          <label for="valor_lancamento" class="control-label">Total à pagar:</label>
                          <input id="valor_lancamento" class="form-control count green input-lg currency" name="valor_lancamento" value="{{ priceFormat($valorLancamento) }}" type="text" onchange="validaBaixaParcial();">
                      </div>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-md-3 col-md-offset-9">
                      <span class="pull-right">
                        <a id="cancel" href="{{ url('/bordero') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                        <button id="send" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Confirmar Baixar</button>
                      </span>
                    </div>
                </div>

              </div><!-- /x-content -->

            </form>

        </div><!-- /x-panel -->

    </div>

</div><!-- #row -->


<script type="text/javascript">
function getValorLancamento()
{

    var input_multa = $("#valor_multa");
    var input_juros = $("#valor_juros");
    var input_desconto = $("#valor_desconto");
    var resultado = 0;

    //--- obtem valores do form
    var valor_saldo = '<?=$bordero->saldo?>';
    var valor_multa = input_multa.val().replace(/,/g, "");
    var valor_juros = input_juros.val().replace(/,/g, "");
    var valor_desconto = input_desconto.val().replace(/,/g, "");

    if(valor_multa=="" || valor_multa ==null)
      valor_multa = 0;

    if(valor_juros=="" || valor_juros ==null)
      valor_juros = 0;

    if(valor_desconto=="" || valor_desconto ==null)
      valor_desconto = 0;

  //--- calcula novo valor do lancamento
  resultado = parseFloat(valor_saldo) + parseFloat(valor_multa) + parseFloat(valor_juros) - parseFloat(valor_desconto);

  $("#valor_lancamento").val( parseFloat(resultado).toFixed(2) );
 
return;
}


function validaBaixaParcial()
{


  //--- obtem valores do form
    var valor_saldo = '<?=$bordero->saldo?>';
    var valor_multa = $("#valor_multa");
    var valor_juros = $("#valor_juros");
    var valor_desconto = $("#valor_desconto");
    var valor_lancamento = $("#valor_lancamento");
  
    //--- variaveis para retorno
    var valor_baixa_parcial = 0;
    var valor_baixa_integral = 0;


    valor_baixa_parcial = parseFloat(valor_lancamento.val().replace(/,/g, ""));
    valor_baixa_integral = parseFloat(valor_saldo) + parseFloat(valor_multa.val().replace(/,/g, ""))   + parseFloat(valor_juros.val().replace(/,/g, "")) - parseFloat(valor_desconto.val().replace(/,/g, ""))  ;

    //--- valida valor de baixa superior ao valor do lançamento
    if( valor_baixa_parcial > valor_baixa_integral){
      alert("O valor informado superior ao valor do lançamento. Verifique!");
      valor_lancamento.val( parseFloat(valor_baixa_integral).toFixed(2) );
      return;
    }
    //--- valida valor de baixa superior ao valor do lançamento
    if( valor_baixa_parcial == 0 || valor_baixa_parcial < ( parseFloat(valor_multa.val().replace(/,/g, ""))+parseFloat(valor_juros.val().replace(/,/g, ""))-parseFloat(valor_desconto.val().replace(/,/g, "")))){
      alert("O valor informado incorreto. Verifique!");
      valor_lancamento.val( parseFloat(valor_baixa_integral).toFixed(2) );
      return;
    }

      
    var lConfirmaBxParcial = confirm("Deseja fazer uma baixa parcial?");

    if (lConfirmaBxParcial == true) {
      valor_lancamento.val( parseFloat(valor_baixa_parcial).toFixed(2) );
    } else {
      valor_lancamento.val( parseFloat(valor_baixa_integral).toFixed(2) );
    }
    
    return;

}

</script>

@endsection