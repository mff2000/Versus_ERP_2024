<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default">

  <div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Endereço Fiscal
      </a>
    </h4>
  </div>

  <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">

    <div class="panel-body">

      <!-- Endereço Fiscal -->
      <div class="form-group">
        
        <div class="col-md-3 col-sm-3 col-xs-6 item">
          <label for="cep" class="control-label">Cep:</label>
          <input id="cep" type="text" name="cep" class="form-control" value="{{ isset($favorecido) ? $favorecido->cep : old('cep') }}">
        </div>

        <div class="col-md-7 col-sm-7 col-xs-12 item">
          <label for="endereco" class="control-label">Logradouro:</label>
          <input id="endereco" type="text" name="endereco" class="form-control" value="{{ isset($favorecido) ? $favorecido->endereco : old('endereco') }}">
        </div>
        
        <div class="col-md-2 col-sm-2 col-xs-6 item">
          <label for="numero" class="control-label">Nº:</label>
          <input id="numero" type="text" name="numero" class="form-control" value="{{ isset($favorecido) ? $favorecido->numero : old('numero') }}">
        </div>

      </div>

      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 item">
          <label for="complemento" class="control-label">Complemento:</label>
          <input id="complemento" type="text" name="complemento" class="form-control col-md-7 col-xs-12" value="{{ isset($favorecido) ? $favorecido->complemento : old('complemento') }}">
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 item">
          <label for="bairro" class="control-label">Bairro:</label>
          <input id="bairro" type="text" name="bairro" class="form-control col-md-7 col-xs-12" value="{{ isset($favorecido) ? $favorecido->bairro : old('bairro') }}">
        </div>
      </div>

      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-4 item">
          <label for="cidade" class="control-label">Cidade:</label>
          <input id="cidade" type="text" name="cidade" class="form-control col-md-7 col-xs-12" value="{{ isset($favorecido) ? $favorecido->cidade : old('cidade') }}">
        </div>
        <div class="col-md-2 col-sm-2 col-xs-4 item">
          <label for="uf" class="control-label">UF:</label>
          {!! Form::select('uf', getUfs(), null, array('class'=>'form-control', 'id' => 'uf', 'value'=>isset($favorecido) ? $favorecido->uf : old('uf'))) !!}
        </div>
      </div>

    </div>

  </div>

</div>

<div class="panel panel-default">
  
  <div class="panel-heading" role="tab" id="headingTwo">
    <h4 class="panel-title">
      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Endereço de Cobrança
      </a>
      
      <a href="#" onclick="copiarEnderecoFiscalParaCobranca();" class="pull-right" style="font-size:12px;"><i class="fa fa-mail-forward"></i> Copiar endereço fiscal</a>
      
    </h4>
  </div>

  <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
    
    <div class="panel-body">

      <!-- Endereço Conbraça -->
      <div class="form-group">
        
        <div class="col-md-3 col-sm-3 col-xs-6 item">
          <label for="cobranca_cep" class="control-label">Cep:</label>
          <input id="cobranca_cep" type="text" name="cobranca_cep" class="form-control" value="{{ isset($favorecido) ? $favorecido->cobranca_cep : old('cobranca_cep') }}">
        </div>

        <div class="col-md-7 col-sm-7 col-xs-12 item">
          <label for="cobranca_endereco" class="control-label">Logradouro:</label>
          <input id="cobranca_endereco" type="text" name="cobranca_endereco" class="form-control" value="{{ isset($favorecido) ? $favorecido->cobranca_endereco : old('cobranca_endereco') }}">
        </div>
        
        <div class="col-md-2 col-sm-2 col-xs-6 item">
          <label for="cobranca_numero" class="control-label">Nº:</label>
          <input id="cobranca_numero" type="text" name="cobranca_numero" class="form-control" value="{{ isset($favorecido) ? $favorecido->cobranca_numero : old('cobranca_numero') }}">
        </div>

      </div>

      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 item">
          <label for="cobranca_complemento" class="control-label">Complemento:</label>
          <input id="cobranca_complemento" type="text" name="cobranca_complemento" class="form-control col-md-7 col-xs-12" value="{{ isset($favorecido) ? $favorecido->cobranca_complemento : old('cobranca_complemento') }}">
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 item">
          <label for="cobranca_bairro" class="control-label">Bairro:</label>
          <input id="cobranca_bairro" type="text" name="cobranca_bairro" class="form-control col-md-7 col-xs-12" value="{{ isset($favorecido) ? $favorecido->cobranca_bairro : old('cobranca_bairro') }}">
        </div>
      </div>

      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-4 item">
          <label for="cobranca_cidade" class="control-label">Cidade:</label>
          <input id="cobranca_cidade" type="text" name="cobranca_cidade" class="form-control col-md-7 col-xs-12" value="{{ isset($favorecido) ? $favorecido->cobranca_cidade : old('cobranca_cidade') }}">
        </div>
        <div class="col-md-2 col-sm-2 col-xs-4 item">
          <label for="cobranca_uf" class="control-label">UF:</label>
          {!! Form::select('cobranca_uf', getUfs(), null, array('class'=>'form-control', 'id' => 'cobranca_uf', 'value'=>isset($favorecido) ? $favorecido->cobranca_uf : old('cobranca_uf'))) !!}
        </div>
      </div>

    </div>

  </div>

</div>

<div class="panel panel-default">
  
  <div class="panel-heading" role="tab" id="headingThree">
    <h4 class="panel-title">
      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Endereço de Entrega
      </a>
      <a href="#" onclick="copiarEnderecoFiscalParaEntrega();" class="pull-right" style="font-size:12px;"><i class="fa fa-mail-forward"></i> Copiar endereço fiscal</a>
    </h4>
  </div>

  <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
    
    <div class="panel-body">

      <!-- Endereço Fiscal -->
      <div class="form-group">
        
        <div class="col-md-3 col-sm-3 col-xs-6 item">
          <label for="entrega_cep" class="control-label">Cep:</label>
          <input id="entrega_cep" type="text" name="entrega_cep" class="form-control" value="{{ isset($favorecido) ? $favorecido->entrega_cep : old('entrega_cep') }}">
        </div>

        <div class="col-md-7 col-sm-7 col-xs-12 item">
          <label for="entrega_endereco" class="control-label">Logradouro:</label>
          <input id="entrega_endereco" type="text" name="entrega_endereco" class="form-control" value="{{ isset($favorecido) ? $favorecido->entrega_endereco : old('entrega_endereco') }}">
        </div>
        
        <div class="col-md-2 col-sm-2 col-xs-6 item">
          <label for="entrega_numero" class="control-label">Nº:</label>
          <input id="entrega_numero" type="text" name="entrega_numero" class="form-control" value="{{ isset($favorecido) ? $favorecido->entrega_numero : old('entrega_numero') }}">
        </div>

      </div>

      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 item">
          <label for="entrega_complemento" class="control-label">Complemento:</label>
          <input id="entrega_complemento" type="text" name="entrega_complemento" class="form-control col-md-7 col-xs-12" value="{{ isset($favorecido) ? $favorecido->entrega_complemento : old('entrega_complemento') }}">
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 item">
          <label for="entrega_bairro" class="control-label">Bairro:</label>
          <input id="entrega_bairro" type="text" name="entrega_bairro" class="form-control col-md-7 col-xs-12" value="{{ isset($favorecido) ? $favorecido->entrega_bairro : old('entrega_bairro') }}">
        </div>
      </div>

      <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-4 item">
          <label for="entrega_cidade" class="control-label">Cidade:</label>
          <input id="entrega_cidade" type="text" name="entrega_cidade" class="form-control col-md-7 col-xs-12" value="{{ isset($favorecido) ? $favorecido->entrega_cidade : old('entrega_cidade') }}">
        </div>
        <div class="col-md-2 col-sm-2 col-xs-4 item">
          <label for="uf" class="control-label">UF:</label>
          {!! Form::select('entrega_uf', getUfs(), null, array('class'=>'form-control', 'id' => 'entrega_uf', 'value'=>isset($favorecido) ? $favorecido->entrega_uf : old('entrega_uf'))) !!}
        </div>
      </div>

    </div>

  </div>

</div>

</div>