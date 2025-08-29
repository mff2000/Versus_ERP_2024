<div class="form-group">

  <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('favorecido_id') ? ' has-error' : '' }}">
      <label for="favorecido_id" class="control-label">Contratante:*</label>
      {!! Form::select('favorecido_id', $favorecidos, (isset($contrato)) ? $contrato->favorecido_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'favorecido_id')) !!}
      @if ($errors->has('favorecido_id'))
        <span class="help-block">
            <strong>{{ $errors->first('favorecido_id') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('descricao') ? ' has-error' : '' }}">
      <label for="descricao" class="control-label">Descrição:*</label>
      <input id="descricao" class="form-control" name="descricao" value="{{ isset($contrato) ? $contrato->descricao : old('descricao') }}" required type="text">
      @if ($errors->has('descricao'))
        <span class="help-block">
            <strong>{{ $errors->first('descricao') }}</strong>
        </span>
      @endif
  </div>

</div>

<div class="form-group">

  <div class="col-md-3 col-sm-3 col-xs-9 item has-feedback {{ $errors->has('data_vigencia_inicio') ? ' has-error' : '' }}">
      <label for="data_vigencia_inicio" class="control-label">Início da Vigência:*</label>
      <input id="data_vigencia_inicio" class="form-control has-feedback-left date" name="data_vigencia_inicio" value="{{ isset($contrato) ? convertDatePt($contrato->data_vigencia_inicio) : old('data_vigencia_inicio') }}" required type="text">
      <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
       @if ($errors->has('data_vigencia_inicio'))
        <span class="help-block">
            <strong>{{ $errors->first('data_vigencia_inicio') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-3 col-sm-3 col-xs-9 item has-feedback {{ $errors->has('data_vigencia_fim') ? ' has-error' : '' }}">
      <label for="data_vigencia_fim" class="control-label">Fim da Vigência:*</label>
      <input id="data_vigencia_fim" class="form-control has-feedback-left date" name="data_vigencia_fim" value="{{ isset($contrato) ? convertDatePt($contrato->data_vigencia_fim) : old('data_vigencia_fim') }}" required type="text">
      <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
       @if ($errors->has('data_vigencia_fim'))
        <span class="help-block">
            <strong>{{ $errors->first('data_vigencia_fim') }}</strong>
        </span>
      @endif
  </div>

   <div class="col-md-6 col-sm-6 col-xs-6 item {{ $errors->has('tipo_transacao_id') ? ' has-error' : '' }}">
      <label for="tipo_transacao_id" class="control-label">Tipo Transação:*</label>
      {!! Form::select('tipo_transacao_id', $tiposTransacao, (isset($contrato)) ? $contrato->tipo_transacao_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'tipo_transacao_id')) !!}
      @if ($errors->has('tipo_transacao_id'))
        <span class="help-block">
            <strong>{{ $errors->first('tipo_transacao_id') }}</strong>
        </span>
      @endif
  </div>

</div>

<div class="form-group">

  <div class="col-md-4 col-sm-4 col-xs-6 item {{ $errors->has('vendedor1_id') ? ' has-error' : '' }}">
      <label for="vendedor1_id" class="control-label">Vendedor 1:*</label>
      {!! Form::select('vendedor1_id', $vendedores, (isset($contrato)) ? $contrato->vendedor1_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'vendedor1_id')) !!}
      @if ($errors->has('vendedor1_id'))
        <span class="help-block">
            <strong>{{ $errors->first('vendedor1_id') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-4 col-sm-4 col-xs-6 item">
      <label for="vendedor2_id" class="control-label">Vendedor 2:</label>
      {!! Form::select('vendedor2_id', $vendedores, (isset($contrato)) ? $contrato->vendedor2_id : null, array('class'=>'form-control', 'id' => 'vendedor2_id')) !!}
  </div>

   <div class="col-md-4 col-sm-4 col-xs-6 item">
      <label for="vendedor3_id" class="control-label">Vendedor 3:</label>
      {!! Form::select('vendedor3_id', $vendedores, (isset($contrato)) ? $contrato->vendedor3_id : null, array('class'=>'form-control', 'id' => 'vendedor3_id')) !!}
  </div>

</div>

<div class="form-group">

  <div class="col-md-4 col-sm-4 col-xs-6 item {{ $errors->has('condicao_id') ? ' has-error' : '' }}">
      <label for="condicoes_id" class="control-label">Condições de Pagamento:*</label>
      {!! Form::select('condicao_id', $condicoes, (isset($contrato)) ? $contrato->condicao_id : null, array('class'=>'form-control', 'required'=>'required', 'id' => 'condicao_id')) !!}
      @if ($errors->has('condicao_id'))
        <span class="help-block">
            <strong>{{ $errors->first('condicao_id') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-4 col-sm-4 col-xs-6 item">
      <label for="pec_cliente" class="control-label">PEC Cliente:</label>
      <input id="pec_cliente" class="form-control percentage" name="pec_cliente" value="{{ isset($contrato) ? $contrato->pec_cliente : old('pec_cliente') }}" type="text" />
  </div>
  @if(isset($contrato))
  <div class="col-md-4 col-sm-4 col-xs-6 item">
      <label for="etapa" class="control-label">Etapa:</label>
      <input id="etapa" class="form-control" name="etapa" value="{{ isset($contrato) ? getEtapasComercial($contrato->etapa) : old('etapa') }}" type="text" readonly="readonly" />
  </div>
  @endif

</div>

<div class="form-group">

  <div class="col-md-4 col-sm-3 col-xs-12 item">
      <label for="construtora_id" class="control-label">Construtora:</label>
      {!! Form::select('construtora_id', $favorecidos, (isset($contrato)) ? $contrato->construtora_id : null, array('class'=>'form-control', 'id' => 'favorecido_id')) !!}
  </div>

  <div class="col-md-4 col-sm-3 col-xs-12 item">
      <label for="serralheiro_id" class="control-label">Serralheiro:</label>
      {!! Form::select('serralheiro_id', $favorecidos, (isset($contrato)) ? $contrato->serralheiro_id : null, array('class'=>'form-control', 'id' => 'favorecido_id')) !!}
  </div>

  <div class="col-md-4 col-sm-3 col-xs-12 item">
      <label for="instalador_id" class="control-label">Instalador:</label>
      {!! Form::select('instalador_id', $favorecidos, (isset($contrato)) ? $contrato->instalador_id : null, array('class'=>'form-control', 'id' => 'favorecido_id')) !!}
  </div>

</div>

<div class="form-group">

  <div class="col-md-6 col-sm-6 col-xs-12">
      <label for="obra" class="control-label">Obra:</label>
      <input id="obra" class="form-control" name="obra" value="{{ isset($contrato) ? $contrato->obra : old('obra') }}" type="text" />
  </div>
  
  <div class="col-md-6 col-sm-6 col-xs-12">
      <label for="responsavel" class="control-label">Responsável:</label>
      <input id="responsavel" class="form-control" name="responsavel" value="{{ isset($contrato) ? $contrato->responsavel : old('responsavel') }}" type="text" />
  </div>

</div>

<div class="form-group">

  <div class="col-md-8 col-sm-8 col-xs-12">
      <label for="observacoes" class="control-label">Observação:</label>
      <input id="observacoes" class="form-control" name="observacoes" value="{{ isset($contrato) ? $contrato->observacoes : old('observacoes') }}" type="text" />
  </div>
  
  <div class="col-md-4 col-sm-4 col-xs-12">
      <label for="valor" class="control-label">Valor do Contrato:</label>
      <input id="valor" class="form-control count green input-lg currency" name="valor" value="{{ isset($contrato) ? $contrato->valor : 0 }}" type="text"  readonly="readonly" />
  </div>

</div>