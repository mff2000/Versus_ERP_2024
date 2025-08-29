<div class="form-group">
                    
  <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('limite_credito') ? ' has-error' : '' }}">
      <label for="limite_credito" class="control-label">Limite de Crédito:</label>
      <input type="text" id="limite_credito" value="" name="limite_credito" class="limite" />
  </div>
  
  <div class="col-md-6 col-sm-6 col-xs-12 item">
      <label for="risco_credito" class="control-label">Risco de Crédito:</label><br />
      <p style="margin-top:10px;">
        <label>
          <input type="radio" class="flat" checked name="risco_credito" value="L"> Sempre Liberado
        </label>
      
        <label>
          <input type="radio" class="flat" name="risco_credito" value="A"> Liberado por Análise
        </label>
     
        <label>
          <input type="radio" class="flat" name="risco_credito" value="B"> Sempre Bloqueado
        </label>
      </p>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
    <label for="data_validade" class="control-label">Validade Límite:</label>
    <input id="data_validade" class="form-control has-feedback-left date" name="data_validade" data-inputmask="'mask': '99/99/9999'" value="{{ isset($favorecido) ? $favorecido->data_validade : old('data_validade') }}"  type="text">
    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
  </div>

</div>

<div class="form-group">

  <div class="col-md-3 col-sm-3 col-xs-12 item">
    <label for="limite_desconto" class="control-label">Límite de Desconto:</label>
    <input id="limite_desconto" class="form-control limite" name="limite_desconto" value="{{ isset($favorecido) ? $favorecido->limite_desconto : old('limite_desconto') }}"  type="text">
  </div>

  <div class="col-md-9 col-sm-9 col-xs-12 item">
      <label for="tipo_pessoa" class="control-label">Condição de Pagamento:</label>
      {!! Form::select('condicao_pagamento_id[]', $condicoes, isset($favorecido) ? getIdstoSelectMultiple($favorecido->condicoes_pagamento) : old('condicao_pagamento_id'), array('class'=>'form-control', 'multiple'=>'multiple', 'id' => 'condicao_pagamento_id')) !!}
  </div>

</div>    