<div class="form-group">

  <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('contrato_id') ? ' has-error' : '' }}">
      <label for="contrato_id" class="control-label">Contrato:*</label>
      {!! Form::select('contrato_id', $contratos, (isset($pedido)) ? $pedido->contrato_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'contrato_id')) !!}
      @if ($errors->has('contrato_id'))
        <span class="help-block">
            <strong>{{ $errors->first('contrato_id') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-3 col-sm-3 col-xs-9 item has-feedback {{ $errors->has('data_entrega') ? ' has-error' : '' }}">
      <label for="data_entrega" class="control-label">Data Entrega:*</label>
      <input id="data_entrega" class="form-control has-feedback-left date" name="data_entrega" value="{{ isset($pedido) ? convertDatePt($pedido->data_entrega) : old('data_entrega') }}" required type="text">
      <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
       @if ($errors->has('data_entrega'))
        <span class="help-block">
            <strong>{{ $errors->first('data_entrega') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item">
      <label for="pedido_favorecido" class="control-label">Nº Pedido:</label>
      <input id="pedido_favorecido" class="form-control" name="pedido_favorecido" value="{{ isset($pedido) ? $pedido->pedido_favorecido : old('pedido_favorecido') }}" type="text">
  </div>

</div>


@include('comercial/pedido_contrato/form_pedido_item')


<div class="form-group">

  <div class="col-md-9 col-sm-9 col-xs-12">
      <label for="observacoes" class="control-label">Observação(uso interno):</label>
      <input id="observacoes" class="form-control" name="observacoes" value="{{ isset($pedido) ? $pedido->observacoes : old('observacoes') }}" type="text" />
  </div>
  
  <div class="col-md-3 col-sm-3 col-xs-12">
      <label for="total_itens" class="control-label">Total de Itens:</label>
      <input id="total_itens" class="form-control count blue input-md number" name="total_itens" value="{{ isset($pedido) ? $pedido->total_itens : 0 }}" type="text"  readonly="readonly" />
  </div>

</div>

<div class="form-group">

  <div class="col-md-9 col-sm-9 col-xs-12">
      <label for="mensagens_danfe" class="control-label">Mensagem DANFE:</label>
      <input id="mensagens_danfe" class="form-control" name="mensagens_danfe" value="{{ isset($pedido) ? $pedido->mensagens_danfe : old('mensagens_danfe') }}" type="text" />
  </div>
  
  <div class="col-md-3 col-sm-3 col-xs-12">
      <label for="valor" class="control-label">Valor do Pedido:</label>
      <input id="valor" class="form-control count green input-lg currency" name="valor" value="{{ isset($pedido) ? $pedido->valor : 0 }}" type="text"  readonly="readonly" />
  </div>
</div>