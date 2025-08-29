<div class="form-group">

  <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('favorecido_id') ? ' has-error' : '' }}">
      <label for="favorecido_id" class="control-label">Favorecido:*</label>
      {!! Form::select('favorecido_id', $favorecidos, (isset($pedido)) ? $pedido->favorecido_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'favorecido_id')) !!}
      @if ($errors->has('favorecido_id'))
        <span class="help-block">
            <strong>{{ $errors->first('favorecido_id') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('tipo_transacao_id') ? ' has-error' : '' }}">
      <label for="tipo_transacao_id" class="control-label">Tipo Transação:*</label>
      {!! Form::select('tipo_transacao_id', $tiposTransacao, (isset($pedido)) ? $pedido->tipo_transacao_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'tipo_transacao_id')) !!}
       @if ($errors->has('tipo_transacao_id'))
        <span class="help-block">
            <strong>{{ $errors->first('tipo_transacao_id') }}</strong>
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

</div>

<div class="form-group">

  <div class="col-md-3 col-sm-3 col-xs-6 item {{ $errors->has('vendedor1_id') ? ' has-error' : '' }}">
      <label for="vendedor1_id" class="control-label">Vendedor 1:*</label>
      {!! Form::select('vendedor1_id', $vendedores, (isset($pedido)) ? $pedido->vendedor1_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'vendedor1_id')) !!}
      @if ($errors->has('vendedor1_id'))
        <span class="help-block">
            <strong>{{ $errors->first('vendedor1_id') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-3 col-sm-3 col-xs-6 item">
      <label for="vendedor2_id" class="control-label">Vendedor 2:</label>
      {!! Form::select('vendedor2_id', $vendedores, (isset($pedido)) ? $pedido->vendedor2_id : null, array('class'=>'form-control', 'id' => 'vendedor2_id')) !!}
  </div>

  <div class="col-md-3 col-sm-3 col-xs-6 item">
      <label for="vendedor3_id" class="control-label">Vendedor 3:</label>
      {!! Form::select('vendedor3_id', $vendedores, (isset($pedido)) ? $pedido->vendedor3_id : null, array('class'=>'form-control', 'id' => 'vendedor3_id')) !!}
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item">
      <label for="pedido_favorecido" class="control-label">Nº Pedido:</label>
      <input id="pedido_favorecido" class="form-control" name="pedido_favorecido" value="{{ isset($pedido) ? $pedido->pedido_favorecido : old('pedido_favorecido') }}" type="text">
  </div>

</div>

@include('comercial/pedido/form_pedido_item')

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