<div class="menu_section">
    
    <ul class="nav side-menu">
        <li class="{{ setActive(['admin']) }}">
            <a href="{{ url('/') }}"><i class="fa fa-home"></i> Início </a>
        </li>
        <li>
            <a><i class="fa fa fa-edit"></i> Cadastros Gerais <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
                @if(env('FINANCEIRO', FALSE))
                <h3>Financeiro</h3>
                <li>
                    <a href="{{ url('/favorecido?session=clear') }}">Favorecidos</a>
                </li>
                <li>
                    <a href="{{ url('/banco?session=clear') }}">Bancos</a>
                </li>
                <li>
                    <a href="{{ url('/centroresultado?session=clear') }}">Centros de Resultados</a>
                </li>
                <li>
                    <a href="{{ url('/contacontabel?session=clear') }}">Planos de Contas</a>
                </li>
                <li>
                    <a href="{{ url('/projeto?session=clear') }}">Projetos</a>
                </li>
                <li>
                    <a href="{{ url('/formafinanceira?session=clear') }}">Formas Financeiras</a>
                </li>
                <li>
                    <a href="{{ url('/desconto?session=clear') }}">Descontos</a>
                </li>
                <li>
                    <a href="{{ url('/correcaofinanceira?session=clear') }}">Correções Financeiras</a>
                </li>
                @endif
                @if(env('COMERCIAL', FALSE))
                <h3>Comercial</h3>
                <li>
                    <a href="{{ url('/produto?session=clear') }}">Produtos</a>
                </li>
                <li>
                    <a href="{{ url('/armazem') }}">Armazéns</a>
                </li>
                <li>
                    <a href="{{ url('/unidade') }}">Unidades de Medida</a>
                </li>
                <li>
                    <a href="{{ url('/grupoProduto') }}">Grupos de Produto</a>
                </li>
                <li>
                    <a href="{{ url('/condicaoPagamento') }}">Condições de Pagamento</a>
                </li>
                <li>
                    <a href="{{ url('/tipoTransacao') }}">Tipos de Transação</a>
                </li>
                <li>
                    <a href="{{ url('/vendedor') }}">Vendedores</a>
                </li>
                <li>
                    <a href="{{ url('/tabelaPreco') }}">Tabelas de Preço</a>
                </li>
                @endif
            </ul>
        </li>
        @if(env('COMERCIAL', FALSE))
        <li>
            <a><i class="fa fa-suitcase"></i> Comercial <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
                <li>
                    <a href="{{ url('/pedido') }}">Pedidos Avulso</a>
                </li>
                <li>
                    <a href="{{ url('/pedidoContrato') }}">Pedidos de Contrato</a>
                </li>
                <li>
                    <a href="form_advanced.html">Exportar p/ Corte</a>
                </li>
                <li>
                    <a href="{{ url('/contratoFornecimento') }}">Contratos de Fornecimento</a>
                </li>
            </ul>
        </li>

        <li class="hidden">
            <a><i class="fa fa-suitcase"></i> Logistica <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
                <li>
                    <a href="form_wizards.html">Romaneios</a>
                </li>
                
            </ul>
        </li>
        <li class="hidden">
            <a><i class="fa fa-suitcase"></i> Produção <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
            
                <li>
                    <a href="form.html">Pedidos por Etapa</a>
                </li>
            </ul>
        </li>
        <li class="hidden">
            <a><i class="fa fa-suitcase"></i> Ocorrências <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
            
                <li>
                    <a href="form.html">Ocorrências</a>
                </li>
            </ul>
        </li>
        @endif
        @if(env('FINANCEIRO', FALSE))
        <li>
            <a><i class="fa fa-line-chart"></i> Financeiro <span class="fa fa-chevron-down"></span></a>
      
            <ul class="nav child_menu" style="display: none">
                <li>
                    <a href="{{ url('/agendamento?session=clear') }}">Agendamentos</a>
                </li>
                <li class="hidden">
                    <a href="page_500.html">Adiantamentos</a>
                </li>
                <li>
                    <a href="{{ url('/lancamento?session=clear') }}">Lançamentos Bancário</a>
                </li>
                <li>
                    <a href="{{ url('/transferencia?session=clear') }}">Transferências Bancária</a>
                </li>
                <li>
                    <a href="{{ url('/lancamento_gerencial?session=clear') }}">Lançamentos Não Financeiros</a>
                </li>
                <li>
                    <a href="{{ url('/orcamento?session=clear') }}">Lançamentos Orçamento</a>
                </li>
                <li>
                    <a href="{{ url('/bordero?session=clear') }}">Borderôs</a>
                </li>
                <li>
                    <a href="{{ url('/conciliacao?session=clear') }}">Conciliação Bancária</a>
                </li>
            </ul>
        </li>
        @endif
        @if(env('GALERIA', FALSE))
            
            @include('layouts/menu_galeria')

        @endif
        <li>
            <a><i class="fa fa-laptop"></i> Configurações <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
            @ability('superadmin', 'user-list')
            <li>
                <a href="{{ url('/users') }}">Usuários</a>
            </li>
            @endability
            @ability('superadmin', 'profile-list')
            <li>
                <a href="{{ url('/perfil') }}">Perfil de Usuários</a>
            </li>
            @endability
            @role('superadmin')
            <li>
                <a href="{{ url('/permissao') }}">Permissões de Acesso</a>
            </li>
            @endrole
            @ability('superadmin', 'company-edit')
            <li>
                <a href="{{ url('/empresa') }}">Empresa</a>
            </li>
            @endability
            @ability('superadmin', 'export-form')
            <li>
                <a href="{{ url('/exportacao') }}">Exportação</a>
            </li>
            @endability
            </ul>
        </li>
    
    </ul>
</div>

<div class="menu_section">

    <h3>Relatórios</h3>

    <ul class="nav side-menu">
        <li>
            <a><i class="fa fa-file-text"></i> Cadastros <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
                <li>
                    <a href="{{ url('cadastro/relatorio/favorecidos') }}">Favorecidos</a>
                </li>
                <li>
                    <a href="{{ url('cadastro/relatorio/planos_contas') }}">Planos de Contas</a>
                </li>
                <li>
                    <a href="{{ url('cadastro/relatorio/centros_resultados') }}">Centros de Resultados</a>
                </li>
                <li>
                    <a href="{{ url('cadastro/relatorio/projetos') }}">Projetos</a>
                </li>
            </ul>
        </li>
        @if(env('COMERCIAL', FALSE))
        <li>
            <a><i class="fa fa-file-text"></i> Comercial <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
                <li>
                    <a href="tables.html">Faturamento</a>
                </li>
                <li>
                    <a href="tables_dynamic.html">Fat. Grupo de Produtos</a>
                </li>
                <li>
                    <a href="tables_dynamic.html">Fat. Cliente x Produto</a>
                </li>
                <li>
                    <a href="tables_dynamic.html">Fat. Produto x Cliente</a>
                </li>
                <li>
                    <a href="tables_dynamic.html">Extrato Contrato</a>
                </li>
                <li>
                    <a href="tables_dynamic.html">Cateria de Pedidos</a>
                </li>
          </ul>
        </li>
        @endif
        @if(env('FINANCEIRO', FALSE))
        <li>
            <a><i class="fa fa-file-text"></i> Financeiro <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
                <li>
                    <a href="{{ url('financeiro/relatorio/agendamentos') }}">Relatório de Agendamentos</a>
                </li>
                <li>
                    <a href="{{ url('financeiro/relatorio/extratos_bancarios') }}">Extrato Bancário</a>
                </li>
                <li>
                    <a href="{{ url('financeiro/relatorio/demonstrativos_resultados') }}">Demonstrativo de Resultado</a>
                </li>
                <li>
                    <a href="{{ url('financeiro/relatorio/fluxo_caixa') }}">Fluxo de Caixa</a>
                </li>
                <li>
                    <a href="{{ url('financeiro/relatorio/razao') }}">Relatório de Razão</a>
                </li>
            </ul>
        </li>
        @endif
    </ul>
    
</div>