
@extends('layouts.app')

@section('content')

<div class="page-title">
    <div class="title_left">
        <h3>
            Exportação de Dados <small>Opções de Tabelas.</small>
        </h3>
    </div>

    <div class="title_right">
      
      <div class="pull-right">
          
      </div>

    </div>
</div>


<div class="row">

  <div class="col-md-12 col-sm-12 col-xs-12">
	  
    @include('flash::message')

		<div class="x_panel">

		    <div class="x_title">
		      
				<h2>Opções para Exportação</h2>

				<div class="clearfix"></div>

		    </div>

		    <form id="conta-form" class="form-horizontal form-label-left mode2" method="POST" action="{{ url('exportacao/gerar_excel') }}" novalidate>
          	
            {{ csrf_field() }}

            <div class="x_content">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                          <h2>Cadastros <small>Tabelas</small></h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <div class="">
                              <ul class="to_do">
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="cadastro[banco]"> Bancos</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="cadastro[centro_resultado]"> Centros de Resultado</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="cadastro[correcao_financeira]"> Correções Financeiras</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="cadastro[desconto]"> Descontos</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="cadastro[favorecido]"> Favorecidos</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="cadastro[forma_financeira]"> Formas Financeiras</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="cadastro[plano_conta]"> Planos de Contas</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="cadastro[projeto]"> Projetos</p>
                                </li>
                              </ul>
                            </div>
                          </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                          <h2>Financeiro <small>Tabelas</small></h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <div class="">
                              <ul class="to_do">
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="financeiro[agendamento]"> Agendamentos </p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="financeiro[bordero]"> Borderôs</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="financeiro[lancamento_bancario]"> Lançamentos Bancários</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="financeiro[lancamento_gerencial]"> Lançamentos Gerenciais</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="financeiro[orcamento]"> Orçamentos</p>
                                </li>
                                <li>
                                  <p>
                                    <input type="checkbox" class="flat" name="financeiro[transferencia]"> Transferências Bancárias</p>
                                </li>
                              </ul>
                            </div>
                          </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">

                    <div class="form-group">
                        
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ordenação:</label>
                        
                        <p class="col-md-3 col-sm-3 col-xs-12">
                          <label class="control-label">
                          <input type="radio" class="flat" name="orderByDirecion" id="orientacaoV" value="ASC" checked="" required /> Ascendente</label>
                          <label class="control-label">
                          <input type="radio" class="flat" name="orderByDirecion" id="orientacaoH" value="DESC" /> Descendente</label>
                        </p>

                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Extensão:</label>
                        <p class="col-md-3 col-sm-3 col-xs-12">
                          <label class="control-label">
                          <input type="radio" class="flat" name="printIn" id="orientacaoV" value="xlsx" checked="" required  /> XLSX</label>
                          <label class="control-label">
                          <input type="radio" class="flat" name="printIn" id="orientacaoH" value="xls" /> XLS</label>
                        </p>

                    </div>

                    <div class="form-group">
                        <div class="col-md-3 col-md-offset-9">
                          <span class="pull-right">
                            <button id="send" type="submit" class="btn btn-default"><i class="fa fa-file-text"></i> Exportar</button>
                          </span>
                        </div>
                    </div>

                </div>

            </div>

		   	</form>

    </div>
  </div>

</div><!-- /#row -->


@endsection