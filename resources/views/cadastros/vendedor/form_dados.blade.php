<div class="x_title">
  <h2><i class="fa fa-bars"></i> Dados do Vendedor </h2>
  <div class="clearfix"></div>
</div>

<div class="form-group">

  <div class="col-md-2 col-sm-2 col-xs-12 item">
      <label for="tipo_pessoa" class="control-label">Tipo Pessoa:*</label>
      {!! Form::select('tipo_pessoa', getTypePesson(), null, array('class'=>'form-control', 'required'=>"required", 'id' => 'tipo_pessoa', 'value'=>Input::old('tipo_pessoa'))) !!}
      @if ($errors->first('tipo_pessoa'))
      <span class="help-block">{!! $errors->first('tipo_pessoa') !!}</span>
      @endif
  </div>

  <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('nome_empresarial') ? ' has-error' : '' }}">
      <label for="nome_empresarial" class="control-label">Razão Social:*</label>
      <input id="nome_empresarial" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="nome_empresarial" value="{{ isset($vendedor) ? $vendedor->nome_empresarial : old('nome_empresarial') }}" placeholder="Razão Social" required="required" type="text">
      @if ($errors->has('nome_empresarial'))
        <span class="help-block">
            <strong>{{ $errors->first('nome_empresarial') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-4 col-sm-4 col-xs-12 item">
      <label for="nome_fantasia" class="control-label">Nome Fantasia:</label>
      <input id="nome_fantasia" class="form-control col-md-7 col-xs-12" name="nome_fantasia" value="{{ isset($vendedor) ? $vendedor->nome_fantasia : old('nome_fantasia') }}" type="text">
  </div>

</div> 

<div class="form-group">

  <div class="col-md-2 col-sm-2 col-xs-12 item {{ $errors->has('cnpj') ? ' has-error' : '' }}">
    <label for="cnpj" class="control-label">CNPJ/CPF:*</label>
    <input id="cpf" class="form-control col-md-7 col-xs-12" name="cnpj" data-inputmask="'mask' : '999.999.999-99', 'numericInput': true" value="{{ isset($vendedor) ? $vendedor->cnpj : old('cnpj') }}" required="required" type="text">
    <input id="cnpj" class="form-control col-md-7 col-xs-12" name="cnpj" data-inputmask="'mask' : '[9]99.999.999/9999-99', 'numericInput': true" value="{{ isset($vendedor) ? $vendedor->cnpj : old('cnpj') }}" required="required" type="text" disabled="disabled" style="display:none;">
    @if ($errors->has('cnpj'))
      <span class="help-block">
          <strong>{{ $errors->first('cnpj') }}</strong>
      </span>
    @endif
  </div>

  <div class="col-md-2 col-sm-2 col-xs-12 item {{ $errors->has('categoria') ? ' has-error' : '' }}">
      <label for="categoria" class="control-label">Categoria:*</label>
      {!! Form::select('categoria', getCategoriaVendedor(), isset($vendedor) ? $vendedor->categoria : old('categoria'), array('class'=>'form-control', 'required'=>"required", 'id' => 'categoria', 'value'=>Input::old('tipo_pessoa'))) !!}
      @if ($errors->first('categoria'))
      <span class="help-block">{!! $errors->first('categoria') !!}</span>
      @endif
  </div>

  <div class="col-md-2 col-sm-2 col-xs-12 item {{ $errors->has('percentual_comissao') ? ' has-error' : '' }}">
      <label for="percentual_comissao" class="control-label">Percentual de Comissão:*</label>
      <input id="percentual_comissao" class="form-control currency" name="percentual_comissao" value="{{ isset($vendedor) ? $vendedor->percentual_comissao : old('percentual_comissao') }}" required="required"  type="text">
      @if ($errors->has('percentual_comissao'))
        <span class="help-block">
            <strong>{{ $errors->first('percentual_comissao') }}</strong>
        </span>
      @endif
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="contato_geral" class="control-label">Contato Geral:</label>
      <input id="contato_geral" class="form-control has-feedback-left" name="contato_geral" value="{{ isset($vendedor) ? $vendedor->contato_geral : old('contato_geral') }}" type="text">
      <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="email_geral" class="control-label">E-mail Geral:</label>
      <input id="email_geral" class="form-control has-feedback-left" name="email_geral" value="{{ isset($vendedor) ? $vendedor->email_geral : old('email_geral') }}"  type="email">
      <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
  </div>

</div>

<div class="form-group">

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="tel_fixo1" class="control-label">Tel. Fixo 1:</label>
      <input id="tel_fixo1" class="form-control has-feedback-left" name="tel_fixo1" data-inputmask="'mask' : '(99) 99999-9999'" value="{{ isset($vendedor) ? $vendedor->tel_fixo1 : old('tel_fixo1') }}" type="text">
      <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
  </div>


  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="tel_fixo2" class="control-label">Tel. Fixo 2:</label>
      <input id="tel_fixo2" class="form-control has-feedback-left" name="tel_fixo2" data-inputmask="'mask' : '(99) 99999-9999'" value="{{ isset($vendedor) ? $vendedor->tel_fixo2 : old('tel_fixo2') }}"  type="text">
      <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="tel_movel1" class="control-label">Celular 1:</label>
      <input id="tel_movel1" class="form-control has-feedback-left" name="tel_movel1"  data-inputmask="'mask' : '(99) 99999-9999'" value="{{ isset($vendedor) ? $vendedor->tel_movel1 : old('tel_movel1') }}" type="text">
      <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="tel_movel2" class="control-label">Celular 2:</label>
      <input id="tel_movel2" class="form-control has-feedback-left" name="tel_movel2" data-inputmask="'mask' : '(99) 99999-9999'" value="{{ isset($vendedor) ? $vendedor->tel_fixo2 : old('tel_movel2') }}"  type="text">
      <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
  </div>

</div>