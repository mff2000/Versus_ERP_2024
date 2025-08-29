<div class="form-group">

  <div class="col-md-9 col-sm-9 col-xs-12 item {{ $errors->has('cliente_id') ? ' has-error' : '' }}">
      <label for="cliente_id" class="control-label">Cliente:*</label>
      {!! Form::select('cliente_id', $clientes, (isset($consignacao)) ? $consignacao->cliente_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'cliente_id')) !!}
      @if ($errors->has('cliente_id'))
        <span class="help-block">
            <strong>{{ $errors->first('cliente_id') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-3 col-sm-3 col-xs-9 has-feedback">
      <label for="data_devolucao" class="control-label">Data de Devolução:*</label>
      <input id="data_devolucao" class="form-control has-feedback-left date" name="data_devolucao" value="{{ isset($consignacao) ? convertDatePt($consignacao->data_devolucao) : old('data_devolucao') }}" required type="text">
      <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
  </div>

</div>

<div class="form-group">

  <div class="col-md-12 col-sm-12 col-xs-12 item">
      <label for="obra_select" class="control-label">Selecione a obra:</label>
      {!! Form::select('obra_select', [], null, array('class'=>'form-control', 'id' => 'obra_select')) !!}
  </div>

</div>

@include('galeria/venda/form_pedido_item')

<div class="form-group">

  
</div>

<div class="form-group">

  <div class="col-md-9 col-sm-9 col-xs-12">
      
      <div class="col-md-12 col-sm-12 col-xs-12">
          <label for="observacao" class="control-label">Observação:</label>
          <textarea id="observacao" class="form-control" name="observacao">{{ isset($consignacao) ? $consignacao->observacao : old('observacao') }}</textarea>
      </div>      

  </div>

  <div class="col-md-3 col-sm-3 col-xs-12">


      <div class="col-md-12 col-sm-12 col-xs-12">
          <label for="total_itens" class="control-label">Total de Itens:</label>
          <input id="total_itens" class="form-control count blue input-md number" name="total_itens" value="{{ isset($consignacao) ? count($consignacao->itens) : 0 }}" type="text"  readonly="readonly" />
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12">
        <label for="valor" class="control-label">Valor do Pedido:</label>
        <input id="valor" class="form-control count green input-lg currency" name="valor" value="{{ isset($consignacao) ? $consignacao->valor : 0 }}" type="text"  readonly="readonly" />
      </div>

  </div>
  
</div>