# NEW-PAINEL-WEB — Painel Administrativo (Laravel)

Painel web administrativo em Laravel/PHP, usado por dois perfis: `admin` (super admin) e `admin_franqueado` (franqueado). Legado em fase de substituição gradual pela API .NET (`casa-do-sindico-API-Laravel` é a API legada correspondente; `API-dotnet` é a substituta). Compartilha o mesmo banco MySQL (`wwcasa_producao`) que a API .NET.

## Stack

- Laravel (PHP 8.5 em produção — atenção a incompatibilidades de pacotes antigos, ex: Mpdf < 8.3 quebra com `unserialize()` no PHP 8.5)
- MySQL via Eloquent
- Blade + jQuery (sem SPA framework) — AJAX simples com `fetch()`/`$.getJSON`
- Integração Autentique (assinatura eletrônica) e ASAAS (cobrança)

## 🔴 Deploy: sem CI, servidor diverge do git — SEMPRE checar antes de sobrescrever

Não existe pipeline de deploy. Mudanças de código viram produção via `scp` direto pro servidor. **O checkout do servidor não é git limpo** — tem hotfixes aplicados direto em produção ao longo do tempo que nunca foram commitados no GitHub.

**Antes de fazer `scp` de qualquer arquivo pro servidor:**
```bash
ssh <server> "cd /var/www/webroot/painel && git status --short <arquivo>"
```
- Vazio → arquivo igual ao HEAD do servidor, seguro sobrescrever com a versão local.
- Modificado → rodar `diff` entre `git show HEAD:<arquivo>` (do servidor) e o arquivo local antes de decidir. Pode ser preciso mesclar manualmente — nem sempre a divergência é inofensiva.

Depois de deployar, sempre commitar+pushar a mudança equivalente pro GitHub, pra não acumular mais divergência.

## Gotchas conhecidos

- **`fetch()`/AJAX com path absoluto sem prefixo**: o Painel roda sob `/painel/` no Nginx (`APP_URL=http://.../painel`), não na raiz. Qualquer `fetch("/rota/...")` sem o prefixo bate 404 — e esse erro aparece só no `nginx error.log`/`access.log`, **nunca** no `laravel.log` (a requisição nem chega no Laravel). Usar `fetch("<?php echo getenv("APP_URL"); ?>/rota/...")` ou `route()`/`url()`.
- **`session_start()` cru sem guarda quebra login**: se algum código chamar `session_start()` sem checar `session_status() === PHP_SESSION_NONE` primeiro, a segunda chamada na mesma request gera um warning que o Laravel promove a exceção fatal nesse ambiente. Sempre guardar.
- **Ordem de rotas em `web.php`**: rotas com parâmetro genérico (`{status}`) cadastradas antes de uma rota literal mais específica "roubam" o match — a literal nunca é alcançada. Rotas mais específicas sempre antes.
- **Signers do Autentique sem `email`**: ao montar `$attributes["signers"][]` (ver `app/Uteis/autentique/DocumentosAutentique.php`), todo signatário precisa de `email`, não só `name`. Sem email, o Autentique nunca notifica esse signatário — o documento existe, mas ninguém sabe que precisa assinar. Já causou contratos represados sem ninguém perceber.
- **`getenv()`/`env()` pode não ver o `.env`** se `bootstrap/cache/config.php` existir (config cacheada) — nesse caso o Laravel pula o carregamento do `.env` inteiro por performance. Rodar `php artisan config:clear` depois de editar `.env` no servidor.
- **`php artisan tinker` não está disponível** nesse deploy — usar teste de canário (mudar um valor direto no banco, ver refletir na UI) pra validar leitura/escrita quando precisar de confirmação forte.
- **APP_DEBUG=true em produção** — expõe stack trace completo em qualquer erro. Não mudar sem necessidade (Laravel legado tem os dias contados).

## Estrutura de acesso (admin vs admin_franqueado)

`DashboardController::__construct` detecta o contexto pela URL (`admin_franqueado/*` vs `admin/*`) e seta `$this->url`/`$this->guard` — cuidado ao adicionar rotas novas fora desses prefixos (ex: `dashboard_franqueado/*`), o construtor precisa reconhecer o padrão explicitamente ou o contexto cai pro guard errado.

## Testando mudanças

Sem suíte de testes automatizada relevante em uso. Validar visualmente no navegador contra o banco de teste, ou com teste de canário direto no banco de produção quando necessário (com cautela).
