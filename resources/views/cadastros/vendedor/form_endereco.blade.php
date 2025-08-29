

    <div class="x_title">
      <h2><i class="fa fa-map-marker"></i> Endereço </h2>
      <div class="clearfix"></div>
    </div>

    <!-- Endereço Fiscal -->
    <div class="form-group">
      
      <div class="col-md-3 col-sm-3 col-xs-6 item">
        <label for="cep" class="control-label">Cep:</label>
        <input id="cep" type="text" name="cep" class="form-control" value="{{ isset($vendedor) ? $vendedor->cep : old('cep') }}">
      </div>

      <div class="col-md-7 col-sm-7 col-xs-12 item">
        <label for="endereco" class="control-label">Logradouro:</label>
        <input id="endereco" type="text" name="endereco" class="form-control" value="{{ isset($vendedor) ? $vendedor->endereco : old('endereco') }}">
      </div>
      
      <div class="col-md-2 col-sm-2 col-xs-6 item">
        <label for="numero" class="control-label">Nº:</label>
        <input id="numero" type="text" name="numero" class="form-control" value="{{ isset($vendedor) ? $vendedor->numero : old('numero') }}">
      </div>

    </div>

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 item">
        <label for="complemento" class="control-label">Complemento:</label>
        <input id="complemento" type="text" name="complemento" class="form-control col-md-7 col-xs-12" value="{{ isset($vendedor) ? $vendedor->complemento : old('complemento') }}">
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 item">
        <label for="bairro" class="control-label">Bairro:</label>
        <input id="bairro" type="text" name="bairro" class="form-control col-md-7 col-xs-12" value="{{ isset($vendedor) ? $vendedor->bairro : old('bairro') }}">
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-4 item">
        <label for="cidade" class="control-label">Cidade:</label>
        <input id="cidade" type="text" name="cidade" class="form-control col-md-7 col-xs-12" value="{{ isset($vendedor) ? $vendedor->cidade : old('cidade') }}">
      </div>
      <div class="col-md-2 col-sm-2 col-xs-4 item">
        <label for="uf" class="control-label">UF:</label>
        {!! Form::select('uf', getUfs(), null, array('class'=>'form-control', 'id' => 'uf', 'value'=>isset($vendedor) ? $vendedor->uf : old('uf'))) !!}
      </div>
    </div>