<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Task;
use Illuminate\Http\Request;


/*
 *   Login Routes
 */

#Route::auth();

Route::get('login', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['as' => 'auth.login.post', 'uses' => 'Auth\LoginController@login']);
Route::post('logout', ['as' => 'auth.logout', 'uses' => 'Auth\LoginController@logout']);

Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token}', ['as' => 'password.reset.token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']);

/**
 * Show Task Dashboard
 */

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

Route::get('/migracao', 'MigracaoController@index');
Route::get('/exportacao', 'ExportacaoController@index');
Route::post('/exportacao/gerar_excel', 'ExportacaoController@gerarExcel');

/**
*   Users Routes
*/
Route::get('/users', 'UserController@index');
Route::get('/user/create', 'UserController@create');
Route::post('/user', 'UserController@store');
Route::get('/user/{user}', 'UserController@show');
Route::get('/user/{user}/edit', 'UserController@edit');
Route::put('/user/{user}', 'UserController@update');
Route::delete('/user/{user}', 'UserController@destroy');

/**
*   Users Profile Routes
*/
Route::resource('/perfil', 'RoleController');
Route::resource('/permissao', 'PermissionController');


/**
*   Financeiro Cadastros
*/
Route::resource('/empresa', 'EmpresaController');
Route::resource('/favorecido', 'Cadastro\FavorecidoController');
Route::get('/favorecido/deleteobs/{id}', array('uses' => 'Cadastro\FavorecidoController@deleteobs', ))->where('id', '[0-9]+');
Route::get('/favorecido/exportar/excel', array('uses' => 'Cadastro\FavorecidoController@toExcel', ));
Route::resource('/banco', 'Cadastro\BancoController');
Route::resource('/centroresultado', 'Cadastro\CentroResultadoController');
Route::get('centroresultado/{id}/create', array('uses' => 'Cadastro\CentroResultadoController@create', ))->where('id', '[0-9]+');
Route::resource('/contacontabel', 'Cadastro\ContaContabelController');
Route::get('contacontabel/{id}/create', array('uses' => 'Cadastro\ContaContabelController@create', ))->where('id', '[0-9]+');
Route::resource('/projeto', 'Cadastro\ProjetoController');
Route::get('projeto/{id}/create', array('uses' => 'Cadastro\ProjetoController@create', ))->where('id', '[0-9]+');
Route::resource('/formafinanceira', 'Cadastro\FormaFinanceiraController');
Route::resource('/desconto', 'Cadastro\DescontoController');
Route::resource('/correcaofinanceira', 'Cadastro\CorrecaoFinanceiraController');


/**
*   Comercial Cadastros
*/
Route::resource('/armazem', 'Cadastro\ArmazemController');
Route::resource('/condicaoPagamento', 'Cadastro\CondicaoPagamentoController');
Route::resource('/unidade', 'Cadastro\UnidadeController');
Route::resource('/grupoProduto', 'Cadastro\GrupoProdutoController');
Route::resource('/tipoTransacao', 'Cadastro\TipoTransacaoController');
Route::resource('/produto', 'Cadastro\ProdutoController');
Route::resource('/vendedor', 'Cadastro\VendedorController');
Route::resource('/tabelaPreco', 'Cadastro\TabelaPrecoController');
Route::resource('/tabelaPreco/salvaProduto', 'Cadastro\TabelaPrecoController@salvaProduto');
Route::get('/tabelaPreco/excluiProduto/{tabelaId}/{produtoId}', 'Cadastro\TabelaPrecoController@excluiProduto')->where('tabelaId', '[0-9]+');

/**
*   Comercial Movimentos
*/

Route::resource('/contratoFornecimento', 'Comercial\ContratoFornecimentoController');
Route::resource('/pedidoContrato', 'Comercial\PedidoContratoController');
Route::resource('/pedido', 'Comercial\PedidoAvulsoController');


/**
*   Financeiro Movimentos
*/
Route::resource('/agendamento', 'Financeiro\AgendamentoController');
Route::get('agendamento/create/{tipo}', array('uses' => 'Financeiro\AgendamentoController@create', ));
Route::get('agendamento/baixa/{id}', array('uses' => 'Financeiro\AgendamentoController@baixa', ))->where('id', '[0-9]+');
Route::post('agendamento/baixa', array('uses' => 'Financeiro\AgendamentoController@storeBaixa', ))->where('id', '[0-9]+');
Route::get('agendamento/excluiBaixa/{id}', array('uses' => 'Financeiro\AgendamentoController@excluiBaixa', ))->where('id', '[0-9]+');

Route::resource('/lancamento', 'Financeiro\LancamentoBancarioController');
Route::resource('/transferencia', 'Financeiro\TransferenciaBancariaController');
Route::resource('/lancamento_gerencial', 'Financeiro\LancamentoGerencialController');

Route::resource('/bordero', 'Financeiro\BorderoController');
Route::post('bordero/busca_agendamento', array('uses' => 'Financeiro\BorderoController@busca_agendamento', ));
Route::get('bordero/{id}/delete_agendamento', array('uses' => 'Financeiro\BorderoController@delete_agendamento', ))->where('id', '[0-9]+');
Route::get('bordero/baixa/{id}', array('uses' => 'Financeiro\BorderoController@baixa', ))->where('id', '[0-9]+');
Route::post('bordero/baixa', array('uses' => 'Financeiro\BorderoController@storeBaixa', ))->where('id', '[0-9]+');
Route::get('bordero/excluiBaixa/{id}', array('uses' => 'Financeiro\BorderoController@excluiBaixa', ));

Route::resource('/conciliacao', 'Financeiro\ConciliacaoBancariaController');
Route::resource('/orcamento', 'Financeiro\OrcamentoController');
//Route::get('agendamento/create/{tipo}', array('uses' => 'Financeiro\LancamentosBancariosController@create', ));
//Route::get('agendamento/baixa/{id}', array('uses' => 'Financeiro\LancamentosBancariosController@baixa', ))->where('id', '[0-9]+');
//Route::post('agendamento/baixa', array('uses' => 'Financeiro\LancamentosBancariosController@storeBaixa', ))->where('id', '[0-9]+');

Route::get('cadastro/relatorio/{relatorio}', 'Cadastro\RelatorioController@index');
Route::post('cadastro/relatorio/favorecido', array('uses' => 'Cadastro\RelatorioController@favorecidos', ));
Route::post('cadastro/relatorio/plano_conta', array('uses' => 'Cadastros\RelatorioController@planos_contas', ));
Route::post('cadastro/relatorio/projeto', array('uses' => 'Cadastros\RelatorioController@projetos', ));
Route::post('cadastro/relatorio/centro_resultado', array('uses' => 'Cadastros\RelatorioController@centros_resultados', ));

Route::get('financeiro/relatorio/{relatorio}', 'Financeiro\RelatorioController@index');
Route::post('financeiro/relatorio/agendamento', array('uses' => 'Financeiro\RelatorioController@agendamentos', ));
Route::post('financeiro/relatorio/extrato_bancario', array('uses' => 'Financeiro\RelatorioController@extratos_bancarios', ));
Route::post('financeiro/relatorio/demonstrativo_resultado', array('uses' => 'Financeiro\RelatorioController@demonstrativos_resultados', ));
Route::get('financeiro/relatorio/demonstrativo_resultado/{id}', array('uses' => 'Financeiro\RelatorioController@demonstrativo_resultado_details', ));
Route::post('financeiro/relatorio/fluxo_caixa', array('uses' => 'Financeiro\RelatorioController@fluxo_caixa', ));
Route::post('financeiro/relatorio/razao', array('uses' => 'Financeiro\RelatorioController@razao', ));

/**
*   Modulos para PenaCalGaleria
*/

Route::resource('galeria/tipo_obra', 'Galeria\TipoObraController');
Route::get('galeria/tecnica/ajaxToSelect', 'Galeria\TecnicaController@ajaxToSelect');
Route::resource('galeria/tecnica', 'Galeria\TecnicaController');
Route::get('galeria/obra/ajax', 'Galeria\ObraController@getByAjax');
Route::resource('galeria/obra', 'Galeria\ObraController');
Route::get('galeria/venda/delete_item/{id}', 'Galeria\VendaController@deleteItem')->where('id', '[0-9]+');
Route::resource('galeria/venda', 'Galeria\VendaController');
Route::get('galeria/consignacao/retorno/{id}', 'Galeria\ConsignacaoController@formRetorno')->where('id', '[0-9]+');
Route::post('galeria/consignacao/retorno', 'Galeria\ConsignacaoController@saveRetorno');
Route::resource('galeria/consignacao', 'Galeria\ConsignacaoController');