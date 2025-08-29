<div class="x_title">
  <h2><i class="fa fa-bars"></i> Dados da favorecido </h2>
  <div class="clearfix"></div>
</div>

<div class="form-group">

  <div class="col-md-2 col-sm-2 col-xs-12 item">
      <label for="tipo_pessoa" class="control-label">Tipo Pessoa:</label>
      {!! Form::select('tipo_pessoa', $tiposPessoa, null, array('class'=>'form-control', 'required'=>"required", 'id' => 'tipo_pessoa', 'value'=>Input::old('tipo_pessoa'))) !!}
      @if ($errors->first('tipo_pessoa'))
      <span class="help-block">{!! $errors->first('tipo_pessoa') !!}</span>
      @endif
  </div>

  <div class="col-md-6 col-sm-6 col-xs-12 item {{ $errors->has('nome_empresarial') ? ' has-error' : '' }}">
      <label for="nome_empresarial" class="control-label">Razão Social:*</label>
      <input id="nome_empresarial" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="nome_empresarial" value="{{ isset($favorecido) ? $favorecido->nome_empresarial : old('nome_empresarial') }}" placeholder="Razão Social" required="required" type="text">
      @if ($errors->has('nome_empresarial'))
        <span class="help-block">
            <strong>{{ $errors->first('nome_empresarial') }}</strong>
        </span>
      @endif
  </div>

  
    <div class="col-md-4 col-sm-4 col-xs-12 item">
        <label for="nome_fantasia" class="control-label">Nome Fantasia:*</label>
        <input id="nome_fantasia" class="form-control col-md-7 col-xs-12" name="nome_fantasia" value="{{ isset($favorecido) ? $favorecido->nome_fantasia : old('nome_fantasia') }}"  required="required" type="text">
        @if ($errors->has('nome_fantasia'))
          <span class="help-block">
              <strong>{{ $errors->first('nome_empresarial') }}</strong>
          </span>
        @endif
    </div>

</div> 

<div class="form-group">

  <div class="col-md-3 col-sm-3 col-xs-12 item {{ $errors->has('cnpj') ? ' has-error' : '' }}">
    <label for="cnpj" class="control-label">CNPJ/CPF * :</label>
    <input id="cpf" class="form-control col-md-7 col-xs-12 cpf" name="cnpj" value="{{ isset($favorecido) ? $favorecido->cnpj : old('cnpj') }}" required="required" type="text">
    <input id="cnpj" class="form-control col-md-7 col-xs-12 cnpj" name="cnpj" value="{{ isset($favorecido) ? $favorecido->cnpj : old('cnpj') }}" required="required" type="text" disabled="disabled" style="display:none;">
    @if ($errors->has('cnpj'))
      <span class="help-block">
          <strong>{{ $errors->first('cnpj') }}</strong>
      </span>
    @endif
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item">
    <label for="inscricao_estadual" class="control-label">Inscrição Estadual:</label>
    <input id="inscricao_estadual" class="form-control" name="inscricao_estadual" value="{{ isset($favorecido) ? $favorecido->inscricao_estadual : old('inscricao_estadual') }}"  type="text">
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="contato_financ" class="control-label">Contato Financeiro:</label>
      <input id="contato_financ" class="form-control has-feedback-left" name="contato_financ" value="{{ isset($favorecido) ? $favorecido->contato_financ : old('contato_financ') }}" type="text">
      <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
    <label for="email_financ" class="control-label">E-mail Financeiro:</label>
    <input id="email_financ" class="form-control has-feedback-left" name="email_nfe" value="{{ isset($favorecido) ? $favorecido->email_financ : old('email_financ') }}"  type="email">
    <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
  </div>

</div>

<div class="form-group{{ $errors->has('contato_geral') ? ' has-error' : '' }}">

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="contato_geral" class="control-label">Contato Geral:</label>
      <input id="contato_geral" class="form-control has-feedback-left" name="contato_geral" value="{{ isset($favorecido) ? $favorecido->contato_geral : old('contato_geral') }}" type="text">
      <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="email_geral" class="control-label">E-mail Geral:</label>
      <input id="email_geral" class="form-control has-feedback-left" name="email_geral" value="{{ isset($favorecido) ? $favorecido->email_geral : old('email_geral') }}"  type="email">
      <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="contato_fiscal" class="control-label">Contato Fiscal:</label>
      <input id="contato_fiscal" class="form-control has-feedback-left" name="contato_fiscal" value="{{ isset($favorecido) ? $favorecido->contato_fiscal : old('contato_geral') }}" type="text">
      <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
    <label for="email_nfe" class="control-label">E-mail Fiscal:</label>
    <input id="email_nfe" class="form-control has-feedback-left" name="email_nfe" value="{{ isset($favorecido) ? $favorecido->email_nfe : old('email_nfe') }}"  type="email">
    <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
  </div>

</div>


<div class="form-group">

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="tel_fixo1" class="control-label">Tel. Fixo 1:</label>
      <input id="tel_fixo1" class="form-control has-feedback-left" name="tel_fixo1" data-inputmask="'mask' : '(99) 99999-9999'" value="{{ isset($favorecido) ? $favorecido->tel_fixo1 : old('tel_fixo1') }}" type="text">
      <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
  </div>


  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="tel_fixo2" class="control-label">Tel. Fixo 2:</label>
      <input id="tel_fixo2" class="form-control has-feedback-left" name="tel_fixo2" data-inputmask="'mask' : '(99) 99999-9999'" value="{{ isset($favorecido) ? $favorecido->tel_fixo2 : old('tel_fixo2') }}"  type="text">
      <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="tel_movel1" class="control-label">Celular 1:</label>
      <input id="tel_movel1" class="form-control has-feedback-left" name="tel_movel1"  data-inputmask="'mask' : '(99) 99999-9999'" value="{{ isset($favorecido) ? $favorecido->tel_movel1 : old('tel_movel1') }}" type="text">
      <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
  </div>

  <div class="col-md-3 col-sm-3 col-xs-12 item has-feedback">
      <label for="tel_movel2" class="control-label">Celular 2:</label>
      <input id="tel_movel2" class="form-control has-feedback-left" name="tel_movel2" data-inputmask="'mask' : '(99) 99999-9999'" value="{{ isset($favorecido) ? $favorecido->tel_fixo2 : old('tel_movel2') }}"  type="text">
      <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
  </div>

</div>

<div class="form-group">

  @if(env('GALERIA', FALSE))
    <div class="col-md-4 col-sm-4 col-xs-12 item">
        <label for="tipo_galeria" class="control-label">Tipo (Galeria):</label>
        {!! Form::select('tipo_galeria[]', getTipoPessoaGaleria(), (isset($favorecido)) ? explode(',', $favorecido->tipo_galeria) : null, array('class'=>'form-control', 'required'=>"required", 'id' => 'tipo_galeria','multiple'=>'true')) !!}
        @if ($errors->first('tipo_galeria'))
        <span class="help-block">{!! $errors->first('tipo_galeria') !!}</span>
        @endif
    </div>
  @endif

</div>