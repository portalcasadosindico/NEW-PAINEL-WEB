<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function () {
    return redirect('/admin/inicio');
});
/** Rotas de login e logout admin **/
Route::get('/admin/login', 'Auth\LoginController@loginPage')->name('admin.login');
Route::post('/admin/login', 'Auth\LoginController@login')->name('admin.login.do');
Route::get('/admin/logout', 'Auth\LoginController@logout')->name('admin.logout');
Route::get('/admin_franqueado/logout', 'Auth\LoginController@logout')->name('admin_franqueado.logout');
/** END Rotas de login e logout admin **/

/** Rotas Dashboard admin **/
Route::get('/admin/inicio', 'DashboardController@index')->name('admin.index');
Route::get('admin/pendencias', 'DashboardController@pendencias')->name('admin.pendencias');
Route::get('dashboard/solicitacoes/{status}', 'DashboardController@getSolicitacoesStatus');
Route::get('dashboard/afiliados/sem-regiao', 'DashboardController@getAfiliadosSemRegiao');
Route::get('dashboard/afiliados/ativos', 'DashboardController@getAfiliadosAtivos');
Route::get('dashboard/categorias', 'DashboardController@getCategorias');
Route::get('dashboard/franqueado', 'DashboardController@getFranqueados');
Route::get('dashboard/solicitacao/andamento', 'DashboardController@getSolicitacoesAndamento');
/** END Rotas Dashboard admin **/

/** Rotas login e logout franqueado **/
Route::get('/admin_franqueado/login', 'Auth\LoginController@loginPage')->name('admin_franqueado.login');
Route::post('/admin_franqueado/login', 'Auth\LoginController@login')->name('admin_franqueado.login.do');
Route::post('/admin_franqueado/autoLogin', 'Auth\LoginController@autoLogin')->name('admin_franqueado.autoLogin');
Route::post('/admin_franqueado/autoLogout', 'Auth\LoginController@autoLogout')->name('admin_franqueado.autoLogout');
Route::get('/admin_franqueado', function () {
    return redirect('/admin_franqueado/inicio');
});
Route::get('/admin_franqueado/login', 'Auth\LoginController@loginPage')->name('admin_franqueado.login');
/** END Rotas login e logout franqueado **/

/** Rotas Dashboard franqueado **/
Route::get('/admin_franqueado/inicio', 'DashboardController@index')->name('admin_franqueado.index');
Route::get('admin_franqueado/pendencias', 'DashboardController@pendencias')->name('admin_franqueado.pendencias');
Route::get('dashboard_franqueado/solicitacoes/vistoria', 'DashboardController@getSolicitacoesVistorias');
Route::get('dashboard_franqueado/solicitacoes/{status}', 'DashboardController@getSolicitacoesFranqueadosStatus');
Route::get('dashboard_franqueado/solicitacao/andamento', 'DashboardController@getSolicitacoesFranqueadoAndamento');
/** END Rotas Dashboard frnaqueado **/

Route::post('/admin/upload_img_conteudo_blog', "BlogController@uploadImagemConteudoBlog");
Route::post("admin/gerarContrato/{afiliado_regiao_id}", 'AfiliadoRegiaoController@gerar_contrato');
Route::post("admin_franqueado/gerarContrato/{afiliado_regiao_id}", 'AfiliadoRegiaoController@gerar_contrato');


Route::middleware('authMiddleware')->group(function () {

    Route::get('admin/logapp', 'ConfiguracaoController@logapp');

    Route::post('admin/alterar_modus_operandi', 'ConfiguracaoController@alterar_modus_operandi');
    Route::get('admin/orcamentos/{id}/edit/{categoria_id}', 'OrcamentoController@edit');
    Route::get('admin_franqueado/orcamentos/{id}/edit/{categoria_id}', 'OrcamentoController@edit');

    Route::post("/admin/alterar_status_categoria_afiliado/{categoria_afiliado_id}", 'AfiliadoCategoriaController@alterar_status');
    Route::post("/admin_franqueado/alterar_status_categoria_afiliado/{categoria_afiliado_id}", 'AfiliadoCategoriaController@alterar_status');

    Route::post("/admin/alterar_status_documento_pendente/{documento_id}", 'AfiliadoController@alterar_status_documento');
    Route::post("/admin_franqueado/alterar_status_documento_pendente/{documento_id}", 'AfiliadoController@alterar_status_documento');


    Route::group(['prefix' => 'admin'], function () {


        Route::get('restore/{id}', 'AfiliadoController@restore')->name('restore');

        Route::get('gerar-excel', 'DashboardController@gerarExcel')->name('gerarExcel');

        Route::get('afiliados_regiao/remover/{afiliado_regiao_id}', "AfiliadoRegiaoController@destroy");

        Route::get('ajax/reenviarEmailConfirmarcao/{usuario_id}', "UsuarioAppController@reenviarEmailConfirmarcao");

        Route::get('afiliados/franqueado_id/{id}', "AfiliadoController@index");

        Route::get('blogs/alterarStatus/{newStatus}/{id}', "BlogController@alterarStatus");

        Route::get('afiliados/{id}/vincularassinaturaexistente/{plano_assinatura_asaas_id}/{plano_assinatura_afiliado_id}', "AfiliadoController@vincularassinaturaexistente");
        Route::post('afiliados/store_regiao', "AfiliadoController@store_regiao")->name("admin.afiliados.store_regiao");
        Route::post('afiliados/store_contrato', "AfiliadoController@store_contrato")->name("admin.afiliados.store_contrato");

        Route::get('afiliados/{id}/removerasaas', "AfiliadoController@removerasaas");

        Route::get('afiliados/{id}/vincularasaas', "AfiliadoController@vincularasaas");
        Route::get('afiliados/{id}/vincularasaasexistente/{customer_id}', "AfiliadoController@vincularasaasexistente");

        Route::get('afiliados/{afiliado_id}/cancelarassinatura/{customer_id}', "AfiliadoController@cancelarassinatura");

        Route::get('afiliados/{afiliado_id}/inserirassinatura/{plano_assinatura_id}', "AfiliadoController@inserirassinatura");

        Route::get('orcamentos/create/categoria_id/{categoria_param_id}', 'OrcamentoController@createComCategoria');
        Route::group(['prefix' => 'perfil'], function () {
            Route::get('/', 'UsuarioSistemaAdminController@profilePage')->name('admin.profile');
            Route::get('/edit', 'UsuarioSistemaAdminController@updateProfilePage')->name('admin.profile.edit');
            Route::put('/edit.do', 'UsuarioSistemaAdminController@updateProfile')->name('admin.profile.edit.do');
        });
        Route::resource('afiliados', AfiliadoController::class)->parameters([
            'afiliados' => 'id',
        ])->names([
            'index' => 'admin.afiliados.index',
            'create' => 'admin.afiliados.create',
            'store' => 'admin.afiliados.store',
            'show' => 'admin.afiliados.show',
            'edit' => 'admin.afiliados.edit',
            'update' => 'admin.afiliados.update',
            'destroy' => 'admin.afiliados.destroy',
        ]);


        Route::resource('notificacoes', NotificacoesController::class)->parameters([
            'notificacoes' => 'id',
        ])->names([
            'index' => 'admin.notificacoes.index',
            'create' => 'admin.notificacoes.create',
            'store' => 'admin.notificacoes.store',
            'show' => 'admin.notificacoes.show'
        ]);


        // Route::get('afiliados/{afiliado_id}/orcamentos', 'AfiliadoController@orcamentos')->name('afiliados.regiao.orcamentos');

        Route::get('afiliados/regiao/{regiao_id}/orcamentos/afiliado_id/{afiliado_id}', "OrcamentoController@fetchOrcamentosByRegiao")->name("admin.afiliados.fetchOrcamentos");

        Route::resource('afiliado_categorias', AfiliadoCategoriaController::class)->parameters([
            'afiliado_categorias' => 'id',
        ]);
        Route::resource('afiliado_orcamentos_interesse', AfiliadoOrcamentoInteresseController::class)->parameters([
            'afiliado_orcamentos_interesse' => 'id',
        ]);
        Route::resource('afiliado_regioes', AfiliadoRegiaoController::class)->parameters([
            'afiliado_regioes' => 'id',
        ]);
        Route::resource('bairros', BairroController::class)->parameters([
            'bairros' => 'id',
        ]);
        Route::resource('blogs', BlogController::class)->parameters([
            'blogs' => 'id',
        ]);

        Route::resource('cartoes_cnpj', CartaoCnpjController::class)->parameters([
            'cartoes_cnpj' => 'id',
        ]);
        Route::resource('categorias', CategoriaController::class)->parameters([
            'categorias' => 'id',
        ]);
        Route::resource('cidades', CidadeController::class)->parameters([
            'cidades' => 'id',
        ]);

        Route::get('cidades/estado_id/{id}', "CidadeController@index");
        Route::get('bairros/cidade_id/{id}', "BairroController@index");

        Route::resource('condominios', CondominioController::class)->parameters([
            'condominios' => 'id',
        ]);
        Route::resource('contratos', ContratoController::class)->parameters([
            'contratos' => 'id',
        ]);
        Route::resource('contratos_sociais', ContratoSocialController::class)->parameters([
            'contratos_sociais' => 'id',
        ]);
        Route::resource('devices', DeviceController::class)->parameters([
            'devices' => 'id',
        ]);
        Route::resource('estados', EstadoController::class)->parameters([
            'estados' => 'id',
        ]);
        Route::resource('franqueados', FranqueadoController::class)->parameters([
            'franqueados' => 'id',
        ]);
        Route::resource('franqueado_regioes', FranqueadoRegiaoController::class)->parameters([
            'franqueado_regioes' => 'id',
        ])->names([
            'index' => 'admin.franqueado_regioes.index',
            'create' => 'admin.franqueado_regioes.create',
            'store' => 'admin.franqueado_regioes.store',
            'show' => 'admin.franqueado_regioes.show',
            'edit' => 'admin.franqueado_regioes.edit',
            'update' => 'admin.franqueado_regioes.update',
            'destroy' => 'admin.franqueado_regioes.destroy',
        ]);
        Route::resource('franqueado_regiao_planos_disponibilizados', FranqueadoRegiaoPlanoDisponibilizadoController::class)->parameters([
            'franqueado_regiao_planos_disponibilizados' => 'id',
        ])->names([
            'index' => 'admin.franqueado_regiao_planos_disponibilizados.index',
            'create' => 'admin.franqueado_regiao_planos_disponibilizados.create',
            'store' => 'admin.franqueado_regiao_planos_disponibilizados.store',
            'show' => 'admin.franqueado_regiao_planos_disponibilizados.show',
            'edit' => 'admin.franqueado_regiao_planos_disponibilizados.edit',
            'update' => 'admin.franqueado_regiao_planos_disponibilizados.update',
            'destroy' => 'admin.franqueado_regiao_planos_disponibilizados.destroy',
        ]);
        Route::resource('imagens_orcamento', ImagemOrcamentoController::class)->parameters([
            'imagens_orcamento' => 'id',
        ]);
        Route::resource('logs_erros_email', LogErroEmailController::class)->parameters([
            'logs_erros_email' => 'id',
        ]);
        Route::resource('logs_sendin_blue', LogSendinBlueController::class)->parameters([
            'logs_sendin_blue' => 'id',
        ]);
        Route::resource('orcamentos', OrcamentoController::class)->parameters([
            'orcamentos' => 'id',
        ])->names([
            'index' => 'admin.orcamentos.index',
            'create' => 'admin.orcamentos.create',
            'store' => 'admin.orcamentos.store',
            'show' => 'admin.orcamentos.show',
            'edit' => 'admin.orcamentos.edit',
            'update' => 'admin.orcamentos.update',
            'destroy' => 'admin.orcamentos.destroy',
        ]);

        Route::get('orcamentos/create/sindico_id/{sindico_param_id}', 'OrcamentoController@create');

        Route::resource('parceiros', ParceiroController::class)->parameters([
            'parceiros' => 'id',
        ]);
        Route::resource('planos_assinatura_afiliado_regiao', PlanoAssinaturaAfiliadoRegiaoController::class)->parameters([
            'planos_assinatura_afiliado_regiao' => 'id',
        ]);
        Route::resource('planos_disponiveis_franqueado', PlanoDisponivelFranqueadoController::class)->parameters([
            'planos_disponiveis_franqueado' => 'id',
        ])->names([
            'index' => 'admin.planos_disponiveis_franqueado.index',
            'create' => 'admin.planos_disponiveis_franqueado.create',
            'store' => 'admin.planos_disponiveis_franqueado.store',
            'show' => 'admin.planos_disponiveis_franqueado.show',
            'edit' => 'admin.planos_disponiveis_franqueado.edit',
            'update' => 'admin.planos_disponiveis_franqueado.update',
            'destroy' => 'admin.planos_disponiveis_franqueado.destroy',
        ]);

        Route::match(['post', 'get'], 'regioes/fetchEstado', 'RegiaoController@fetchEstado')->name('regioes.fetchEstado');
        Route::match(['post', 'get'], 'regioes/fetchCidade', 'RegiaoController@fetchCidade')->name('regioes.fetchCidade');

        Route::view('/regioes/{id}/afiliados/', 'regioes.afiliados.show')->name('regioes.afiliado');
        Route::view('/regioes/{id}/sindicos/', 'regioes.sindicos.show')->name('regioes.sindico');
        Route::view('/regioes/{id}/locais/', 'regioes.locais.show')->name('regioes.local');

        Route::resource('regioes', RegiaoController::class)->parameters([
            'regioes' => 'id',
        ]);

        Route::resource('responsaveis_afiliado', ResponsavelAfiliadoController::class)->parameters([
            'responsaveis_afiliado' => 'id',
        ]);
        Route::resource('ruas', RuaController::class)->parameters([
            'ruas' => 'id',
        ]);
        Route::resource('sessoes_usuario', SessaoUsuarioController::class)->parameters([
            'sessoes_usuario' => 'id',
        ]);
        Route::resource('sindicos', SindicoController::class)->parameters([
            'sindicos' => 'id',
        ])->names([
            'index' => 'admin.sindicos.index',
            'create' => 'admin.sindicos.create',
            'store' => 'admin.sindicos.store',
            'show' => 'admin.sindicos.show',
            'edit' => 'admin.sindicos.edit',
            'update' => 'admin.sindicos.update',
            'destroy' => 'admin.sindicos.destroy',
        ]);

        Route::get('sindicos/{id}/condominios', "CondominioController@fetchCondominiosBySindico")->name("admin.sindicos.fetchCondominios");

        Route::get('condominios/{condominio_id}/orcamentos', "OrcamentoController@fetchOrcamentosByCondominio")->name("admin.sindicos.fetchOrcamentos");

        Route::post('sindicos/cadastrar_modal', "SindicoController@store_modal");

        Route::post('condominios/cadastrar_modal', "CondominioController@storeModal")->name("condominios.cadastrar_modal");

        Route::get('sindicos/franqueado_id/{id}', "SindicoController@index");
        Route::resource('usuarios_app', UsuarioAppController::class)->parameters([
            'usuarios_app' => 'id',
        ]);
        Route::resource('usuarios_sistema_admin', UsuarioSistemaAdminController::class)->parameters([
            'usuarios_sistema_admin' => 'id',
        ]);
        Route::resource('vistorias', VistoriaController::class)->parameters([
            'vistorias' => 'id',
        ])->names([
            'index' => 'admin.vistorias.index',
            'create' => 'admin.vistorias.create',
            'store' => 'admin.vistorias.store',
            'show' => 'admin.vistorias.show',
            'edit' => 'admin.vistorias.edit',
            'update' => 'admin.vistorias.update',
            'destroy' => 'admin.vistorias.destroy',
        ]);;

        Route::resource('vistoriadores', VistoriadorController::class)->parameters([
            'vistoriadores' => 'id',
        ])->names([
            'index' => 'admin.vistoriadores.index',
            'create' => 'admin.vistoriadores.create',
            'store' => 'admin.vistoriadores.store',
            'show' => 'admin.vistoriadores.show',
            'edit' => 'admin.vistoriadores.edit',
            'update' => 'admin.vistoriadores.update',
            'destroy' => 'admin.vistoriadores.destroy',
        ]);
        Route::get('vistoriadores/franqueado_id/{id}', "VistoriadorController@index");
        Route::resource('vistoria_imagens', VistoriaImagemController::class)->parameters([
            'vistoria_imagens' => 'id',
        ])->names([
            'index' => 'admin.vistoria_imagens.index',
            'create' => 'admin.vistoria_imagens.create',
            'store' => 'admin.vistoria_imagens.store',
            'show' => 'admin.vistoria_imagens.show',
            'edit' => 'admin.vistoria_imagens.edit',
            'update' => 'admin.vistoria_imagens.update',
            'destroy' => 'admin.vistoria_imagens.destroy',
        ]);;

        Route::resource('politica_privacidades', PoliticaPrivacidadesController::class)->parameters([
            'politica_privacidades' => 'id',
        ])->except('edit', 'update', 'destroy');

        Route::resource('responsavel_politicas', ResponsavelPoliticasController::class)->parameters([
            'responsavel_politicas' => 'id',
        ])->except('destroy');

        Route::resource('canal_atendimentos', CanalAtendimentosController::class)->parameters([
            'canal_atendimentos' => 'id',
        ]);

        Route::resource('termo_usos', TermoUsosController::class)->parameters([
            'termo_usos' => 'id',
        ])->except('edit', 'update', 'destroy');

        Route::get('orcamentos/franqueado/{franqueado_id}', 'OrcamentoController@index');
        Route::get('orcamentos/franqueado/{franqueado_id}/regiao/{regiao_id}', 'OrcamentoController@index');
        Route::post('orcamentos/filtro', 'OrcamentoController@indexByDate')->name('admin.orcamentos.indexByDate');

        Route::post('alterar_status/plano_regiao/{id}', "PlanoAssinaturaAfiliadoRegiaoController@alterarStatus");
        Route::post('plano_regiao/{id}/confirmar-contrato', "PlanoAssinaturaAfiliadoRegiaoController@confirmarContrato");
    });

    Route::group(['prefix' => 'admin_franqueado'], function () {

        Route::get('ajax/reenviarEmailConfirmarcao/{usuario_id}', "UsuarioAppController@reenviarEmailConfirmarcao");

        Route::resource('vistorias', VistoriaController::class)->parameters([
            'vistorias' => 'id',
        ])->names([
            'index' => 'admin_franqueado.vistorias.index',
            'create' => 'admin_franqueado.vistorias.create',
            'store' => 'admin_franqueado.vistorias.store',
            'show' => 'admin_franqueado.vistorias.show',
            'edit' => 'admin_franqueado.vistorias.edit',
            'update' => 'admin_franqueado.vistorias.update',
            'destroy' => 'admin_franqueado.vistorias.destroy',
        ]);

        Route::post('alterar_status/plano_regiao/{id}', "PlanoAssinaturaAfiliadoRegiaoController@alterarStatus");
        Route::post('plano_regiao/{id}/confirmar-contrato', "PlanoAssinaturaAfiliadoRegiaoController@confirmarContrato");
        Route::get('afiliados/regiao/{regiao_id}/orcamentos/afiliado_id/{afiliado_id}', "OrcamentoController@fetchOrcamentosByRegiao")->name("admin_franqueado.afiliados.fetchOrcamentos");


        Route::get('afiliados/{afiliado_id}/orcamentos', 'AfiliadoController@orcamentos')->name('admin_franqueado.afiliados.regiao.orcamentos');

        Route::group(['prefix' => 'perfil'], function () {
            Route::get('/', 'FranqueadoController@profilePage')->name('admin_franqueado.profile');
            Route::get('/edit', 'FranqueadoController@updateProfilePage')->name('admin_franqueado.profile.edit');
            Route::put('/edit.do', 'FranqueadoController@updateProfile')->name('admin_franqueado.profile.edit.do');
        });
        Route::resource('sindicos', SindicoController::class)->parameters([
            'sindicos' => 'id',
        ])->names([
            'index' => 'admin_franqueado.sindicos.index',
            'create' => 'admin_franqueado.sindicos.create',
            'store' => 'admin_franqueado.sindicos.store',
            'show' => 'admin_franqueado.sindicos.show',
            'edit' => 'admin_franqueado.sindicos.edit',
            'update' => 'admin_franqueado.sindicos.update',
            'destroy' => 'admin_franqueado.sindicos.destroy',
        ]);
        Route::post('sindicos/cadastrar_modal', "SindicoController@store_modal");
        Route::post('condominios/cadastrar_modal', "CondominioController@storeModal")->name("admin_franqueado.condominios.cadastrar_modal");
        Route::resource('vistoria_imagens', VistoriaImagemController::class)->parameters([
            'vistoria_imagens' => 'id',
        ])->names([
            'index' => 'admin_franqueado.vistoria_imagens.index',
            'create' => 'admin_franqueado.vistoria_imagens.create',
            'store' => 'admin_franqueado.vistoria_imagens.store',
            'show' => 'admin_franqueado.vistoria_imagens.show',
            'edit' => 'admin_franqueado.vistoria_imagens.edit',
            'update' => 'admin_franqueado.vistoria_imagens.update',
            'destroy' => 'admin_franqueado.vistoria_imagens.destroy',
        ]);

        Route::get('sindicos/{id}/condominios', "CondominioController@fetchCondominiosBySindico")->name("admin_franqueado.sindicos.fetchCondominios");
        Route::get('condominios/{condominio_id}/orcamentos', "OrcamentoController@fetchOrcamentosByCondominio")->name("admin_franqueado.sindicos.fetchOrcamentos");


        //Route::get('sindicos/{id}/condominios', 'SindicoController@condominios')->name('admin_franqueado.sindicos.condominios');
        //Route::get('sindicos/{id}/condominios/orcamentos', 'SindicoController@condominioOrcamentos')->name('admin_franqueado.sindicos.condominios.orcamentos');
        Route::get('sindicos/{id}/orcamentos', 'SindicoController@orcamentos')->name('admin_franqueado.sindicos.orcamentos');

        Route::delete('sindicos/{id}/condominios', 'SindicoController@condominiosDestroy')->name('admin_franqueado.sindicos.condominios.destroy');

        Route::resource('vistoriadores', VistoriadorController::class)->parameters([
            'vistoriadores' => 'id',
        ])->names([
            'index' => 'admin_franqueado.vistoriadores.index',
            'create' => 'admin_franqueado.vistoriadores.create',
            'store' => 'admin_franqueado.vistoriadores.store',
            'show' => 'admin_franqueado.vistoriadores.show',
            'edit' => 'admin_franqueado.vistoriadores.edit',
            'update' => 'admin_franqueado.vistoriadores.update',
            'destroy' => 'admin_franqueado.vistoriadores.destroy',
        ]);

        Route::resource('planos_disponiveis_franqueado', PlanoDisponivelFranqueadoController::class)->parameters([
            'planos_disponiveis_franqueado' => 'id',
        ])->names([
            'index' => 'admin_franqueado.planos_disponiveis_franqueado.index',
            'create' => 'admin_franqueado.planos_disponiveis_franqueado.create',
            'store' => 'admin_franqueado.planos_disponiveis_franqueado.store',
            'show' => 'admin_franqueado.planos_disponiveis_franqueado.show',
            'edit' => 'admin_franqueado.planos_disponiveis_franqueado.edit',
            'update' => 'admin_franqueado.planos_disponiveis_franqueado.update',
            'destroy' => 'admin_franqueado.planos_disponiveis_franqueado.destroy',
        ]);

        Route::put('planos_disponiveis_franqueado/{id}/status', 'PlanoDisponivelFranqueadoController@status')->name('admin_franqueado.planos_disponiveis_franqueado.status');
        Route::resource('franqueado_regioes', FranqueadoRegiaoController::class)->parameters([
            'franqueado_regioes' => 'id',
        ])->names([
            'index' => 'admin_franqueado.franqueado_regioes.index',
            'create' => 'admin_franqueado.franqueado_regioes.create',
            'store' => 'admin_franqueado.franqueado_regioes.store',
            'show' => 'admin_franqueado.franqueado_regioes.show',
            'edit' => 'admin_franqueado.franqueado_regioes.edit',
            'update' => 'admin_franqueado.franqueado_regioes.update',
            'destroy' => 'admin_franqueado.franqueado_regioes.destroy',
        ]);
        Route::resource('franqueado_regiao_planos_disponibilizados', FranqueadoRegiaoPlanoDisponibilizadoController::class)->parameters([
            'franqueado_regiao_planos_disponibilizados' => 'id',
        ])->names([
            'index' => 'admin_franqueado.franqueado_regiao_planos_disponibilizados.index',
            'create' => 'admin_franqueado.franqueado_regiao_planos_disponibilizados.create',
            'store' => 'admin_franqueado.franqueado_regiao_planos_disponibilizados.store',
            'show' => 'admin_franqueado.franqueado_regiao_planos_disponibilizados.show',
            'edit' => 'admin_franqueado.franqueado_regiao_planos_disponibilizados.edit',
            'update' => 'admin_franqueado.franqueado_regiao_planos_disponibilizados.update',
            'destroy' => 'admin_franqueado.franqueado_regiao_planos_disponibilizados.destroy',
        ]);
        Route::put('franqueado_regiao_planos_disponibilizados/{id}/status', 'FranqueadoRegiaoPlanoDisponibilizadoController@status')->name('franqueado_regiao_planos_disponibilizados.status');

        Route::resource('afiliados', AfiliadoController::class)->parameters([
            'afiliados' => 'id',
        ])->names([
            'index' => 'admin_franqueado.afiliados.index',
            'create' => 'admin_franqueado.afiliados.create',
            'store' => 'admin_franqueado.afiliados.store',
            'show' => 'admin_franqueado.afiliados.show',
            'edit' => 'admin_franqueado.afiliados.edit',
            'update' => 'admin_franqueado.afiliados.update',
            'destroy' => 'admin_franqueado.afiliados.destroy',
        ]);
        Route::post('afiliados/store_regiao', "AfiliadoController@store_regiao")->name("admin_franqueado.afiliados.store_regiao");

        Route::post('afiliados/store_contrato', "AfiliadoController@store_contrato")->name("admin_franqueado.afiliados.store_contrato");


        Route::get('afiliados/{id}/removerasaas', "AfiliadoController@removerasaas");

        Route::get('afiliados/{id}/vincularasaas', "AfiliadoController@vincularasaas");
        Route::get('afiliados/{id}/vincularasaasexistente/{customer_id}', "AfiliadoController@vincularasaasexistente");

        Route::get('afiliados/{id}/cancelarassinatura/{customer_id}', "AfiliadoController@cancelarassinatura");

        Route::get('afiliados/{id}/inserirassinatura/{plano_assinatura_id}', "AfiliadoController@inserirassinatura");

        Route::get('afiliados/{id}/vincularassinaturaexistente/{plano_assinatura_asaas_id}/{plano_assinatura_afiliado_id}/{data_expiracao}/{customer_id}', "AfiliadoController@vincularassinaturaexistente");

        //Route::get('afiliados/{id}/vincularassinaturaexistente/{plano_assinatura_asaas_id}/{plano_assinatura_afiliado_id}', "AfiliadoController@vincularassinaturaexistente");


        Route::get('afiliados/franqueado_id/{id}', "AfiliadoController@index");
        Route::get('/afiliado_regioes/{id}/regiao', 'AfiliadoRegiaoController@regiao')->name('afiliado_regioes.regiao');

        Route::post('orcamentos/filtro', 'OrcamentoController@indexByDate')->name('admin_franqueado.orcamentos.indexByDate');
        Route::resource('orcamentos', OrcamentoController::class)->parameters([
            'orcamentos' => 'id',
        ])->names([
            'index' => 'admin_franqueado.orcamentos.index',
            'create' => 'admin_franqueado.orcamentos.create',
            'store' => 'admin_franqueado.orcamentos.store',
            'show' => 'admin_franqueado.orcamentos.show',
            'edit' => 'admin_franqueado.orcamentos.edit',
            'update' => 'admin_franqueado.orcamentos.update',
            'destroy' => 'admin_franqueado.orcamentos.destroy',
        ]);
        Route::get('orcamentos/{id}/vistoria', 'OrcamentoController@vistoria')->name('admin_franqueado.orcamentos.vistoria');
        //Route::get('orcamentos/create/sindico_id/{sindico_id}', 'OrcamentoController@show');
        Route::get('orcamentos/create/sindico_id/{sindico_param_id}', 'OrcamentoController@create');
        Route::get('orcamentos/create/categoria_id/{categoria_param_id}', 'OrcamentoController@createComCategoria');
    });
});

/* Padrão template **/
Route::group(['prefix' => 'auth'], function () {
    Route::get('login', function () {
        return view('pages.auth.login');
    });
    Route::get('register', function () {
        return view('pages.auth.register');
    });
});

Route::group(['prefix' => 'error'], function () {
    Route::get('404', function () {
        return view('pages.error.404');
    });
    Route::get('500', function () {
        return view('pages.error.500');
    });
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get("/importEstados", "EstadoController@importEstados");
Route::get("/importCidades", "EstadoController@importCidades");
Route::get("/importBairros", "EstadoController@importBairros");
Route::get("/importCeps2016", "EstadoController@importCeps2016");
Route::get("/importCeps2018", "EstadoController@importCeps2018");
Route::get("/transporSolicitacoes", "EstadoController@transporSolicitacoes");

// 404 for undefined routes
Route::any('/{page?}', function () {
    return View::make('pages.error.404');
})->where('page', '.*');
/* END Padrão template **/