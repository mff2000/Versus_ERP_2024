<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'bancos' => [
        'descricao' => 'Descrição',
        'codigo' => 'Código',
        'agencia' => 'Agência',
        'dv_agencia' => 'DV.',
        'numero_conta' => 'Número da Conta',
        'dv_conta' => 'DV',
        'nome_gerente' => 'Gerente',
        'email_geral' => 'E-mail Geral',
        'limite' => 'R$ Limite',
        'saldo_atual' => 'Saldo'
    ],

    'favorecidos' => [
        'nome_empresarial' => 'Razão Social',
        'nome_fantasia' => 'Nome Fantasia',
        'cnpj' => 'CNPJ/CPF',
        'tel_fixo1' => 'Telefone',
        'tel_movel1' => 'Celular',
        'email_geral' => 'E-mail',
        'limite_credito' => 'Limite',
        'risco_credito' => 'Risco de Crédito',
        'created_at' => 'D.Cadastrado',
    ],

    'planos_contas' => [
        'codigo' => 'Código',
        'descricao' => 'Descrição',
        'classe' => 'Classe',
        'natureza' => 'Natureza',
    ],

    'centros_resultados' => [
        'codigo' => 'Código',
        'descricao' => 'Descrição',
        'classe' => 'Classe'
    ],

    'projetos' => [
        'codigo' => 'Código',
        'descricao' => 'Descrição',
        'classe' => 'Classe',
        'natureza' => 'Natureza',
    ],

    'correcao_financeiras' => [
        'descricao' => 'Descrição'
    ],

    'forma_financeiras' => [
        'descricao' => 'Descrição'
    ],

    'descontos' => [
        'descricao' => 'Descrição'
    ],

    'agendamentos' => [
        'id' => 'ID',
        'numero_titulo' => 'Nº Título',
        'numero_parcela' => 'Parcela', 
        'historico' => 'Histórico', 
        'favorecido_id' => 'Favorecido', 
        'valor_titulo' => 'Valor Título', 
        'valor_saldo' => 'Valor Saldo', 
        'data_competencia' => 'Competência', 
        'data_vencimento' => 'Vencimento',
        'tipo_movimento' => 'Tipo de Movimento'
    ],

    'lancamentos_bancarios' => [
        'id' => 'ID',
        'numero_titulo' => 'Nº Título',
        'numero_parcela' => 'Parcela', 
        'historico' => 'Histórico', 
        'favorecido_id' => 'Favorecido', 
        'valor_lancamento' => 'Valor Título', 
        'data_lancamento' => 'Data Lançamento',
        'data_liquidacao' => 'Data de Liquidação',
        'tipo_movimento' => 'Tipo de Movimento',
        'banco_id' => 'Banco'
    ],

    'transferencias_bancarias' => [
        'numero_titulo' => 'Nº Título',
        'numero_parcela' => 'Parcela', 
        'historico' => 'Histórico', 
        'valor_lancamento' => 'Valor Título', 
        'data_lancamento' => 'Data Lançamento',
        'tipo_movimento' => 'Tipo de Movimento',
        'banco_origem_id' => 'Banco Débitado',
        'banco_destino_id' => 'Banco Créditado'
    ],
    
    'lancamentos_gerenciais' => [
        'numero_titulo' => 'Nº Título',
        'numero_parcela' => 'Parcela', 
        'historico' => 'Histórico', 
        'favorecido_id' => 'Favorecido', 
        'valor_lancamento' => 'Valor Título', 
        'data_lancamento' => 'Data Lançamento'
    ],

    'lancamentos_orcamento' => [
        'historico' => 'Histórico', 
        'tipo_movimento' => 'Tipo de Movimento',
        'valor_lancamento' => 'Valor Lançamento',
        'data_competencia' => 'Data Competência',
        'data_vencimento' => 'Data Vencimento',
    ],

    'borderos' => [
        'id' => 'ID', 
        'descricao' => 'Descrição', 
        'tipo_bordero' => 'Tipo de Borderô',
        'valor' => 'Valor',
        'data_emissao' => 'Data Emissão',
        'data_liquidacao' => 'Data Liquidação',
    ],

];