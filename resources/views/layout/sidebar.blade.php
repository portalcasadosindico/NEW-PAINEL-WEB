<nav class="sidebar sidebar-dark">
  <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
      Casa do <span>Síndico</span>
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category"></li>
      <li class="nav-item {{ active_class(['admin/inicio','admin/pendencias']) }}">
        <a class="nav-link" data-toggle="collapse" href="#dashboard" role="button" aria-expanded="{{ is_active_route(['admin/inicio','admin/pendencias']) }}" aria-controls="dashboard">
          <i class="link-icon" data-feather="aperture"></i>
          <span class="link-title">Menu principal</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/*','admin/inicio']) }}" id="dashboard">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.index') }}" class="nav-link {{ active_class(['admin/inicio']) }}">Dashboard</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.pendencias') }}" class="nav-link {{ active_class(['admin/pendencias']) }}">Pendências</a>
            </li>
          </ul>
        </div>
      </li>
    </a>
  </li>
      <li class="nav-item nav-category"></li>
      <li class="nav-item {{ active_class(['admin/vistoriadores/*','admin/vistoriadores']) }}">
        <a class="nav-link" data-toggle="collapse" href="#vistoriador" role="button" aria-expanded="{{ is_active_route(['admin/vistoriadores/*','admin/vistoriadores']) }}" aria-controls="vistoriador">
          <i class="link-icon" data-feather="aperture"></i>
          <span class="link-title">Vistoriador</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/vistoriadores/*','admin/vistoriadores']) }}" id="vistoriador">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.vistoriadores.create') }}" class="nav-link {{ active_class(['admin/vistoriadores/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.vistoriadores.index') }}" class="nav-link {{ active_class(['admin/vistoriadores']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/sindicos/*','admin/sindicos']) }}">
        <a class="nav-link" data-toggle="collapse" href="#sindico" role="button" aria-expanded="{{ is_active_route(['admin/sindicos/*','admin/sindicos']) }}" aria-controls="sindico">
          <i class="link-icon" data-feather="star"></i>
          <span class="link-title">Sindico</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/sindicos/*','admin/sindicos']) }}" id="sindico">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.sindicos.create') }}" class="nav-link {{ active_class(['admin/sindicos/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.sindicos.index') }}" class="nav-link {{ active_class(['admin/sindicos']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/afiliados/*','admin/afiliados']) }}">
        <a class="nav-link" data-toggle="collapse" href="#afiliado" role="button" aria-expanded="{{ is_active_route(['admin/afiliados/*','admin/afiliados']) }}" aria-controls="afiliado">
          <i class="link-icon" data-feather="smile"></i>
          <span class="link-title">Afiliado</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/afiliados/*','admin/afiliados']) }}" id="afiliado">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.afiliados.create') }}" class="nav-link {{ active_class(['admin/afiliados/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.afiliados.index') }}" class="nav-link {{ active_class(['admin/afiliados']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/vistorias/*','admin/vistorias']) }}">
        <a class="nav-link" data-toggle="collapse" href="#vistoria" role="button" aria-expanded="{{ is_active_route(['admin/vistorias/*','admin/vistorias']) }}" aria-controls="vistoria">
          <i class="link-icon" data-feather="camera"></i>
          <span class="link-title">Vistorias</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/vistorias/*','admin/vistorias']) }}" id="vistoria">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.vistorias.create') }}" class="nav-link {{ active_class(['admin/vistorias/create']) }}">Nova</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.vistorias.index') }}" class="nav-link {{ active_class(['admin/vistorias']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/orcamentos/*','admin/orcamentos']) }}">
        <a class="nav-link" data-toggle="collapse" href="#orcamento" role="button" aria-expanded="{{ is_active_route(['admin/orcamentos/*','admin/orcamentos']) }}" aria-controls="orcamento">
          <i class="link-icon" data-feather="file-text"></i>
          <span class="link-title">Solicitações</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/orcamentos/*','admin/orcamentos']) }}" id="orcamento">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.orcamentos.index') }}" class="nav-link {{ active_class(['admin/orcamentos']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/franqueados/*','admin/franqueados']) }}">
        <a class="nav-link" data-toggle="collapse" href="#franqueado" role="button" aria-expanded="{{ is_active_route(['admin/franqueados/*','admin/franqueados']) }}" aria-controls="franqueado">
          <i class="link-icon" data-feather="git-merge"></i>
          <span class="link-title">Franqueado</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/franqueados/*','admin/franqueados']) }}" id="franqueado">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('franqueados.create') }}" class="nav-link {{ active_class(['admin/franqueados/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('franqueados.index') }}" class="nav-link {{ active_class(['admin/franqueados']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/planos_disponiveis_franqueado/*','admin/planos_disponiveis_franqueado']) }}">
        <a class="nav-link" data-toggle="collapse" href="#plano" role="button" aria-expanded="{{ is_active_route(['admin/planos_disponiveis_franqueado/*','admin/planos_disponiveis_franqueado']) }}" aria-controls="rua">
          <i class="link-icon" data-feather="slack"></i>
          <span class="link-title">Planos</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/planos_disponiveis_franqueado/*','admin/planos_disponiveis_franqueado']) }}" id="plano">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.planos_disponiveis_franqueado.create') }}" class="nav-link {{ active_class(['admin/planos_disponiveis_franqueado/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.planos_disponiveis_franqueado.index') }}" class="nav-link {{ active_class(['admin/planos_disponiveis_franqueado']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>



      <li class="nav-item nav-category">Notificações</li>
      <li class="nav-item {{ active_class(['admin/notificacoes/*','admin/notificacoes']) }}">
        <a class="nav-link" data-toggle="collapse" href="#notificacoes" role="button" aria-expanded="{{ is_active_route(['admin/notificacoes/*','admin/notificacoes']) }}" aria-controls="notificacoes">
          <i class="link-icon" data-feather="tag"></i>
          <span class="link-title">Notificações</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/notificacoes/*','admin/notificacoes']) }}" id="notificacoes">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.notificacoes.create') }}" class="nav-link {{ active_class(['admin/notificacoes/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.notificacoes.index') }}" class="nav-link {{ active_class(['admin/notificacoes']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>



      <li class="nav-item nav-category">Configurações</li>
      <li class="nav-item {{ active_class(['admin/categorias/*','admin/categorias']) }}">
        <a class="nav-link" data-toggle="collapse" href="#categoria" role="button" aria-expanded="{{ is_active_route(['admin/categorias/*','admin/categorias']) }}" aria-controls="categoria">
          <i class="link-icon" data-feather="tag"></i>
          <span class="link-title">Categorias</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/categorias/*','admin/categorias']) }}" id="categoria">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('categorias.create') }}" class="nav-link {{ active_class(['admin/categorias/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('categorias.index') }}" class="nav-link {{ active_class(['admin/categorias']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/regioes/*','admin/regioes']) }}">
        <a class="nav-link" data-toggle="collapse" href="#regiao" role="button" aria-expanded="{{ is_active_route(['admin/regioes/*','admin/regioes']) }}" aria-controls="regiao">
          <i class="link-icon" data-feather="compass"></i>
          <span class="link-title">Regiões</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/regioes/*','admin/regioes']) }}" id="regiao">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('regioes.create') }}" class="nav-link {{ active_class(['admin/regioes/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('regioes.index') }}" class="nav-link {{ active_class(['admin/regioes']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/estados/*','admin/estados']) }}">
        <a class="nav-link" data-toggle="collapse" href="#estado" role="button" aria-expanded="{{ is_active_route(['admin/estados/*','admin/estados']) }}" aria-controls="estado">
          <i class="link-icon" data-feather="settings"></i>
          <span class="link-title">Estados</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/estados/*','admin/estados']) }}" id="estado">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('estados.create') }}" class="nav-link {{ active_class(['admin/estados/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('estados.index') }}" class="nav-link {{ active_class(['admin/estados']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/cidades/*','admin/cidades']) }}">
        <a class="nav-link" data-toggle="collapse" href="#cidade" role="button" aria-expanded="{{ is_active_route(['admin/cidades/*','admin/cidades']) }}" aria-controls="cidade">
          <i class="link-icon" data-feather="settings"></i>
          <span class="link-title">Cidades</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/cidades/*','admin/cidades']) }}" id="cidade">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('cidades.create') }}" class="nav-link {{ active_class(['admin/cidades/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('cidades.index') }}" class="nav-link {{ active_class(['admin/cidades']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <!--<li class="nav-item {{ active_class(['admin/bairros/*','admin/bairros']) }}">
        <a class="nav-link" data-toggle="collapse" href="#bairro" role="button" aria-expanded="{{ is_active_route(['admin/bairros/*','admin/bairros']) }}" aria-controls="cidade">
          <i class="link-icon" data-feather="settings"></i>
          <span class="link-title">Bairros</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/bairros/*','admin/bairros']) }}" id="bairro">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('bairros.create') }}" class="nav-link {{ active_class(['admin/bairros/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('bairros.index') }}" class="nav-link {{ active_class(['admin/bairros']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>-->
      <!--<li class="nav-item {{ active_class(['admin/ruas/*','admin/ruas']) }}">
        <a class="nav-link" data-toggle="collapse" href="#rua" role="button" aria-expanded="{{ is_active_route(['admin/ruas/*','admin/ruas']) }}" aria-controls="rua">
          <i class="link-icon" data-feather="mail"></i>
          <span class="link-title">Ruas</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/ruas/*','admin/ruas']) }}" id="rua">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('ruas.create') }}" class="nav-link {{ active_class(['admin/ruas/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('ruas.index') }}" class="nav-link {{ active_class(['admin/ruas']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>-->
      
      <li class="nav-item {{ is_active_route(['admin/termo_usos/*','admin/politica_privacidades/*','admin/responsavel_politicas/*','admin/canal_atendimentos/*','admin/termo_usos','admin/politica_privacidades','admin/responsavel_politicas','admin/canal_atendimentos']) }}" >
        <a class="nav-link" data-toggle="collapse" href="#termos" role="button" aria-expanded="{{ is_active_route(['admin/termo_usos/*','admin/politica_privacidades/*','admin/responsavel_politicas/*','admin/canal_atendimentos/*','admin/termo_usos','admin/politica_privacidades','admin/responsavel_politicas','admin/canal_atendimentos']) }}" aria-controls="rua">
          <i class="link-icon" data-feather="pen-tool"></i>
          <span class="link-title">Termos e Política</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/termo_usos/*','admin/politica_privacidades/*','admin/responsavel_politicas/*','admin/canal_atendimentos/*','admin/termo_usos','admin/politica_privacidades','admin/responsavel_politicas','admin/canal_atendimentos']) }}" id="termos">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('termo_usos.index') }}" class="nav-link {{ active_class(['admin/termo_usos']) }}">Termos de uso</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('politica_privacidades.index') }}" class="nav-link {{ active_class(['admin/politica_privacidades']) }}">Política de privacidade</a>
            </li>
            <!--<li class="nav-item">
              <a href="{{ route('responsavel_politicas.index') }}" class="nav-link {{ active_class(['admin/responsavel_politicas']) }}">Responsavel</a>
            </li>-->
            <li class="nav-item">
              <a href="{{ route('canal_atendimentos.index') }}" class="nav-link {{ active_class(['admin/canal_atendimentos']) }}">Canal atendimentos</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Site</li>
      <li class="nav-item {{ active_class(['admin/parceiros/*']) }}">
        <a class="nav-link" data-toggle="collapse" href="#parceiro" role="button" aria-expanded="{{ is_active_route(['admin/parceiros/*','admin/parceiros']) }}" aria-controls="parceiro">
          <i class="link-icon" data-feather="users"></i>
          <span class="link-title">Parceiros</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/parceiros/*','admin/parceiros']) }}" id="parceiro">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('parceiros.create') }}" class="nav-link {{ active_class(['admin/parceiros/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('parceiros.index') }}" class="nav-link {{ active_class(['admin/parceiros']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin/blogs/*','admin/blogs']) }}">
        <a class="nav-link" data-toggle="collapse" href="#blog" role="button" aria-expanded="{{ is_active_route(['admin/blogs/*','admin/blogs']) }}" aria-controls="blog">
          <i class="link-icon" data-feather="hash"></i>
          <span class="link-title">Blog</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin/blogs/*','admin/blogs']) }}" id="blog">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('blogs.create') }}" class="nav-link {{ active_class(['admin/blogs/create']) }}">Criar Post</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('blogs.index') }}" class="nav-link {{ active_class(['admin/blogs']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</nav>