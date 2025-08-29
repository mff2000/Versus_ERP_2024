
@extends('layouts.app')

@section('content')


<div class="page-title">
    <div class="title_left">
        <h3>
            Baixar Agendamento <small>Preencha os campos abaixo, atentando para os que são obrigatórios.</small>
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

            <form id="agendamento-baixa-form" class="form-horizontal form-label-left mode2 validate" method="POST" action="{{ url('agendamento/baixa') }}" novalidate>
            {{ csrf_field() }}

              <div class="x_content">

                    <input id="agendamento_id" type="hidden" name="agendamento_id" value="{{ isset($agendamento) ? $agendamento->id : 0 }}">

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Dados do Agendamento </h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                      
                      <div class="col-md-6 col-sm-6 col-xs-12 item}}">
                          <label for="historico" class="control-label">Histórico:</label>
                          <input id="historico" class="form-control" name="historico" value="{{ isset($agendamento) ? $agendamento->historico : old('historico') }}" disabled="disabled"  type="text">
                      </div>
                      
                      <div class="col-md-6 col-sm-6 col-xs-12 item">
                          <label for="favorecido" class="control-label">Pagamento à:</label>
                          <input id="favorecido" class="form-control" name="favorecido" value="{{ isset($agendamento) ? $agendamento->favorecido->nome_fantasia : old('nome_fantasia') }}" disabled="disabled"   type="text">
                      </div>

                    </div> 

                    <div class="form-group">
                      
                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                          <label for="numero_titulo" class="control-label">Número/Parc:</label>
                          <input id="numero_titulo" class="form-control" name="numero_titulo" value="{{ isset($agendamento) ? $agendamento->numero_titulo.'/'.$agendamento->numero_parcela  : old('numero_titulo') }}"  disabled="disabled"  type="text">
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                          <label for="valor_titulo" class="control-label">Valor R$:</label>
                          <input id="valor_titulo" class="form-control currency" name="valor_titulo" value="{{ isset($agendamento) ? $agendamento->valor_titulo : old('valor_titulo') }}"  disabled="disabled"  type="text">
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                        <label for="data_competencia" class="control-label">Competência:</label>
                        <input id="data_competencia" class="form-control date" name="data_competencia" value="{{ isset($agendamento) ? convertDatePt($agendamento->data_competencia) : old('data_competencia') }}" disabled="disabled" type="text">
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                        <label for="data_vencimento" class="control-label">Vencimento:</label>
                        <input id="data_vencimento" class="form-control date" name="data_vencimento" value="{{ isset($agendamento) ? convertDatePt($agendamento->data_vencimento) : old('data_vencimento') }}" disabled="disabled" type="text">
                      </div>

                    </div>

                    <div class="x_title">
                      <h2><i class="fa fa-bars"></i> Dados da baixa </h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                    
                      <div class="col-md-8 col-sm-8 col-xs-6 item">
                          <label for="historico" class="control-label">Histórico da Baixa:*</label>
                          <input id="historico" class="form-control" name="historico" value="{{ isset($lancamento) ? $lancamento->historico : old('historico') }}" required type="text">
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12 tile_stats_count col-sm-offset-1">
                          <label for="numero_cheque" class="control-label">Saldo à Pagar:</label>
                          <div class="count red currency">{{ isset($agendamento) ? $agendamento->valor_saldo : old('valor_saldo') }}</div>
                      </div>
                    
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-6 item has-feedback">
                          <label for="data_lancamento" class="control-label">Data Lançamento:</label>
                          <input id="data_lancamento" class="form-control has-feedback-left date" name="data_lancamento" value="{{ isset($lancamento) ? $lancamento->data_lancamento : (old('data_lancamento')) ? old('data_lancamento') : <?=date('d/m/Y')?>  }}"  type="text">
                          <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-2 col-sm-2 col-xs-6 item">
                          <label for="numero_cheque" class="control-label">Nº Cheque:</label>
                          <input id="numero_cheque" class="form-control numeric col-sm-2" name="numero_cheque" value="{{ isset($lancamento) ? $lancamento->numero_cheque : old('numero_cheque') }}"  type="text">
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                          <label for="banco_id" class="control-label">Banco:*</label>
                          {!! Form::select('banco_id', $bancos, (isset($lancamento)) ? $lancamento->banco_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'banco_id')) !!}
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12 col-sm-offset-1">
                          <label for="dias_atraso" class="control-label">Dias {{ ($estaVencido) ? 'em Atraso': 'à vencer' }}:</label>
                          <input id="dias_atraso" class="form-control count {{ ($estaVencido) ? 'red': 'blue' }} input-sm numeric" value="{{ $diasDiferenca }}" disabled type="text">
                      </div>
                      
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                          <label for="forma_financeira_id" class="control-label">Forma Financeira:*</label>
                          {!! Form::select('forma_financeira_id', $formasFinanceira, (isset($lancamento)) ? $lancamento->forma_financeira_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'forma_financeira_id')) !!}
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 item">
                          <label for="desconto_id" class="control-label">Desconto:</label>
                          {!! Form::select('desconto_id', $descontos, (isset($lancamento)) ? $lancamento->desconto_id : null, array('class'=>'form-control', 'id' => 'desconto_id')) !!}
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12 col-sm-offset-3">
                          <label for="valor_multa" class="control-label">Multa:</label>
                          <input id="valor_multa" class="form-control count red input-sm currency" name="valor_multa" value="{{ priceFormat($valorMulta, false) }}" type="text" onChange="getValorLancamento();">
                      </div>
                      
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-12 col-sm-offset-9">
                          <label for="valor_juros" class="control-label">Juros:</label>
                          <input id="valor_juros" class="form-control count red input-sm currency" name="valor_juros" value="{{ priceFormat($valorJuros, false) }}" type="text" onChange="getValorLancamento();">
                      </div>
                      
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-12 col-sm-offset-9">
                          <label for="valor_desconto" class="control-label">Desconto:</label>
                          <input id="valor_desconto" class="form-control count blue input-sm currency" name="valor_desconto" value="{{ priceFormat($valorDesconto, false) }}" type="text" onChange="getValorLancamento();">
                      </div>
                      
                    </div>

                    <div class="form-group">

                      <div class="col-md-3 col-sm-3 col-xs-12 col-sm-offset-9 tile_stats_count">
                          <label for="valor_lancamento" class="control-label">Total à pagar:</label>
                          <input id="valor_lancamento" class="form-control count green input-lg currency" name="valor_lancamento" value="{{ priceFormat($valorLancamento, false) }}" type="text" onchange="validaBaixaParcial();">
                      </div>
                      
                    </div>

                </div>

                <div class="form-group">
                    <div class="col-md-3 col-md-offset-9">
                      <span class="pull-right">
                        <a id="cancel" href="{{ url('/agendamento') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
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
    var valor_saldo = formatFloat( '<?= priceFormat($agendamento->valor_saldo) ?>' );
    var valor_multa = formatFloat( input_multa.val());
    var valor_juros = formatFloat( input_juros.val());
    var valor_desconto = formatFloat( input_desconto.val());
    
    if(valor_multa=="" || valor_multa == null)
      valor_multa = 0;

    if(valor_juros=="" || valor_juros == null)
      valor_juros = 0;

    if(valor_desconto=="" || valor_desconto == null)
      valor_desconto = 0;

  //--- calcula novo valor do lancamento
  resultado = valor_saldo + valor_multa + valor_juros - valor_desconto;
  
  $("#valor_lancamento").val( formatMoney(resultado) );
 
return;
}


function validaBaixaParcial()
{


  //--- obtem valores do form
    var valor_saldo = '<?=$agendamento->valor_saldo?>';
    var valor_multa = $("#valor_multa");
    var valor_juros = $("#valor_juros");
    var valor_desconto = $("#valor_desconto");
    var valor_lancamento = $("#valor_lancamento");
  
    //--- variaveis para retorno
    var valor_baixa_parcial = 0;
    var valor_baixa_integral = 0;


    valor_baixa_parcial = formatFloat(valor_lancamento.val());
    valor_baixa_integral = formatFloat(valor_saldo) + formatFloat(valor_multa.val()) + formatFloat(valor_juros.val()) - formatFloat(valor_desconto.val());

    //--- valida valor de baixa superior ao valor do lançamento
    if( valor_baixa_parcial > valor_baixa_integral){
      alert("O valor informado superior ao valor do lançamento. Verifique!");
      valor_lancamento.val( formatMoney(valor_baixa_integral) );
      return;
    }
    //--- valida valor de baixa superior ao valor do lançamento
    if( valor_baixa_parcial == 0 || valor_baixa_parcial < ( formatFloat(valor_multa.val()) + formatFloat(valor_juros.val()) - formatFloat(valor_desconto.val()) )) {
      alert("O valor informado incorreto. Verifique!");
      valor_lancamento.val( formatMoney(valor_baixa_integral) );
      return;
    }

      
    var lConfirmaBxParcial = confirm("Deseja fazer uma baixa parcial?");

    if (lConfirmaBxParcial == true) {
      valor_lancamento.val( formatMoney(valor_baixa_parcial) );
    } else {
      valor_lancamento.val( formatMoney(valor_baixa_integral) );
    }
    
    return;

}

</script>

@endsection