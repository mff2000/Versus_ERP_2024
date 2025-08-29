<!-- start form for validation -->
<form id="empresa-form" class="form-horizontal form-label-left" method="POST" action="{{ url('/empresa') }}" novalidate>
<!-- {!! Form::open(array('action' => '\App\Http\Controllers\EmpresaController@store', 'novalidate', 'class'=>'form-horizontal')) !!} -->
    
    {{ csrf_field() }}
    
    <input id="id_empresa" type="hidden" name="id" value="{{ $empresa->id }}">

    <div class="x_title">
      <h2><i class="fa fa-bars"></i> Dados da Empresa </h2>
      <div class="clearfix"></div>
    </div>

    <div class="item form-group{{ $errors->has('nome_empresarial') ? ' has-error' : '' }}">
      <div class="col-md-12 col-sm-12 col-xs-12">
          <label for="nome_empresarial" class="control-label">Razão Social * :</label>
          <input id="nome_empresarial" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="nome_empresarial" value="{{ $empresa->nome_empresarial }}" placeholder="Razão Social" required="required" type="text">
      </div>
    </div>

    <div class="item form-group{{ $errors->has('nome_fantasia') ? ' has-error' : '' }}">
      <div class="col-md-12 col-sm-12 col-xs-12">
          <label for="nome_fantasia" class="control-label">Nome Fantasia * :</label>
          <input id="nome_fantasia" class="form-control col-md-7 col-xs-12" name="nome_fantasia" value="{{ $empresa->nome_fantasia }}"  required="required" type="text">
      </div>
    </div>

    <div class="item form-group{{ $errors->has('cnpj') ? ' has-error' : '' }}">
      <div class="col-md-4 col-sm-4 col-xs-12">
          <label for="cnpj" class="control-label">CNPJ * :</label>
          <input id="cnpj" type="text" name="cnpj" data-validate-length="14,18" value="{{ $empresa->cnpj }}" class="form-control col-md-7 col-xs-12" required="required">
      </div>
      @if ($errors->has('cnpj'))
        <span class="help-block">
            <strong>{{ $errors->first('cnpj') }}</strong>
        </span>
      @endif

      <div class="col-md-4 col-sm-4 col-xs-12">
          <label for="codigo_autorizacao" class="control-label">Código de Autorização * :</label>
          <input id="codigo_autorizacao" type="text" name="codigo_autorizacao" value="{{ $empresa->codigo_autorizacao }}" class="form-control col-md-7 col-xs-12" required="required">
      </div>

      <div class="col-md-4 col-sm-4 col-xs-12">
          <label for="data_validade" class="control-label">Validade cód. Autorização * :</label>
          <input id="data_validade" type="text" name="data_validade" value="{{ $empresa->data_validade }}" class="form-control col-md-7 col-xs-12" required="required">
      </div>

    </div>


    <div class="x_title">
      <h2><i class="fa fa-bars"></i> Endereço </h2>
      <div class="clearfix"></div>
    </div>

    <div class="item form-group">
      <div class="col-md-10 col-sm-10 col-xs-10">
        <label for="endereco" class="control-label">Logradouro:</label>
        <input id="endereco" type="text" name="endereco" class="form-control col-md-7 col-xs-12" value="{{ $empresa->endereco }}">
      </div>
      
      <div class="col-md-2 col-sm-2 col-xs-2">
        <label for="numero" class="control-label">Nº:</label>
        <input id="numero" type="text" name="numero" class="form-control col-md-2 col-xs-12" value="{{ $empresa->numero }}">
      </div>
    </div>

    <div class="item form-group">
      <div class="col-md-6 col-sm-6 col-xs-12">
        <label for="complemento" class="control-label">Complemento:</label>
        <input id="complemento" type="text" name="complemento" class="form-control col-md-7 col-xs-12" value="{{ $empresa->complemento }}">
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <label for="bairro" class="control-label">Bairro:</label>
        <input id="bairro" type="text" name="bairro" class="form-control col-md-7 col-xs-12" value="{{ $empresa->bairro }}">
      </div>
    </div>

    <div class="item form-group">
      <div class="col-md-3 col-sm-3 col-xs-4">
        <label for="cep" class="control-label">Cep:</label>
        <input id="cep" type="text" name="cep" class="form-control col-md-7 col-xs-12" value="{{ $empresa->cep }}">
      </div>
      <div class="col-md-7 col-sm-7 col-xs-4">
        <label for="cidade" class="control-label">Cidade:</label>
        <input id="cidade" type="text" name="cidade" class="form-control col-md-7 col-xs-12" value="{{ $empresa->cidade }}">
      </div>
      <div class="col-md-2 col-sm-2 col-xs-4">
        <label for="uf" class="control-label">UF:</label>
        <input id="uf" type="text" name="uf" data-validate-length="2,2" class="form-control col-md-7 col-xs-12" value="{{ $empresa->uf }}">
      </div>
    </div>

    <div class="x_title">
      <h2><i class="fa fa-bars"></i> Contatos </h2>
      <div class="clearfix"></div>
    </div>

    <div class="item form-group">
      <div class="col-md-3 col-sm-3 col-xs-6">
        <label for="tel_fixo1" class="control-label">Telefone 1:</label>
        <input id="tel_fixo1" type="text" name="tel_fixo1" class="form-control col-md-7 col-xs-12" value="{{ $empresa->tel_fixo1 }}">
      </div>
      
      <div class="col-md-3 col-sm-3 col-xs-6">
        <label for="tel_fixo2" class="control-label">Telefone 2:</label>
        <input id="tel_fixo2" type="text" name="tel_fixo2" class="form-control col-md-2 col-xs-12" value="{{ $empresa->tel_fixo2 }}">
      </div>
      <div class="col-md-3 col-sm-3 col-xs-6">
        <label for="tel_movel1" class="control-label">Celular 1:</label>
        <input id="tel_movel1" type="text" name="tel_movel1" class="form-control col-md-7 col-xs-12" value="{{ $empresa->tel_movel1 }}">
      </div>
      
      <div class="col-md-3 col-sm-3 col-xs-6">
        <label for="tel_movel2" class="control-label">Celular 2:</label>
        <input id="tel_movel2" type="text" name="tel_movel2" class="form-control col-md-2 col-xs-12" value="{{ $empresa->tel_movel2 }}">
      </div>
    </div>

    <div class="item form-group">
      <div class="col-md-4 col-sm-4 col-xs-12">
        <label for="email_geral" class="control-label">E-mail Geral:</label>
        <input id="email_geral" type="email" name="email_geral" class="form-control col-md-7 col-xs-12" value="{{ $empresa->email_geral }}">
      </div>
      
      <div class="col-md-4 col-sm-4 col-xs-12">
        <label for="email_nfe" class="control-label">E-mail NF-e:</label>
        <input id="email_nfe" type="email" name="email_nfe" class="form-control col-md-2 col-xs-12" value="{{ $empresa->email_nfe }}">
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <label for="email_financ" class="control-label">E-mail Financeiro:</label>
        <input id="email_financ" type="text" name="email_financ" class="form-control col-md-7 col-xs-12" value="{{ $empresa->email_financ }}">
      </div>
      
    </div>

    <div class="item form-group">
      <div class="col-md-4 col-sm-4 col-xs-12">
        <label for="endereco" class="control-label">Contato Geral:</label>
        <input id="endereco" type="text" name="endereco" class="form-control col-md-7 col-xs-12" value="{{ $empresa->endereco }}">
      </div>
      
      <div class="col-md-4 col-sm-4 col-xs-12">
        <label for="endereco" class="control-label">Contato Fiscal:</label>
        <input id="endereco" type="text" name="endereco" class="form-control col-md-2 col-xs-12" value="{{ $empresa->endereco }}">
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <label for="endereco" class="control-label">Contato Financeiro:</label>
        <input id="endereco" type="text" name="endereco" class="form-control col-md-7 col-xs-12" value="{{ $empresa->endereco }}">
      </div>
      
    </div>

    <br/>
    <div class="form-group">
      <div class="col-md-3 col-md-offset-9">
        
        <button id="send" type="submit" class="btn btn-success  pull-right"><i class="fa fa-save"></i> Salvar</button>
        
      </div>
    </div>

{!! Form::close() !!}
<!-- end form for validations -->