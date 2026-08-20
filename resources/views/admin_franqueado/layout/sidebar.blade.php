<nav class="sidebar">
  <div class="sidebar-header">
    @php session_start(); @endphp
    @if(isset($_SESSION['login_as_admin']) && $_SESSION['login_as_admin']) {{-- veio de "entrar como" um franqueado --}}
    <form method="POST" action="{{ route('admin_franqueado.autoLogout') }}" accept-charset="UTF-8" style="display:inline;">
      @csrf
      <button type="submit" class="sidebar-brand" style="background:none;border:none;cursor:pointer;">
        Casa do <span>Síndico</span>
      </button>
    </form>
    @else
    <a href="{{ route('admin_franqueado.index') }}" class="sidebar-brand">
      Casa do <span>Síndico</span>
    </a>
    @endif
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category"></li>
      <li class="nav-item {{ active_class(['admin_franqueado/inicio','admin_franqueado/pendencias']) }}">
        <a class="nav-link" data-toggle="collapse" href="#dashboard" role="button" aria-expanded="{{ is_active_route(['admin_franqueado/inicio','admin_franqueado/pendencias']) }}" aria-controls="dashboard">
          <i class="link-icon" data-feather="aperture"></i>
          <span class="link-title">Menu principal</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin_franqueado/*','admin_franqueado/inicio']) }}" id="dashboard">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.index') }}" class="nav-link {{ active_class(['admin_franqueado/inicio']) }}">Dashboard</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.pendencias') }}" class="nav-link {{ active_class(['admin_franqueado/pendencias']) }}">Pendências</a>
            </li>
          </ul>
        </div>
      </li>
    </a>
  </li>
      <li class="nav-item nav-category"></li>
      <li class="nav-item {{ active_class(['admin_franqueado/vistoriadores/*','admin_franqueado/vistoriadores']) }}">
        <a class="nav-link" data-toggle="collapse" href="#vistoriador" role="button" aria-expanded="{{ is_active_route(['admin_franqueado/vistoriadores/*','admin_franqueado/vistoriadores']) }}" aria-controls="vistoriador">
          <i class="link-icon" data-feather="aperture"></i>
          <span class="link-title">Vistoriador</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin_franqueado/vistoriadores/*','admin_franqueado/vistoriadores']) }}" id="vistoriador">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.vistoriadores.create') }}" class="nav-link {{ active_class(['admin_franqueado/vistoriadores/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.vistoriadores.index') }}" class="nav-link {{ active_class(['admin_franqueado/vistoriadores']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin_franqueado/sindicos/*','admin_franqueado/sindicos']) }}">
        <a class="nav-link" data-toggle="collapse" href="#sindico" role="button" aria-expanded="{{ is_active_route(['admin_franqueado/sindicos/*','admin_franqueado/sindicos']) }}" aria-controls="sindico">
          <i class="link-icon" data-feather="star"></i>
          <span class="link-title">Sindico</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin_franqueado/sindicos/*','admin_franqueado/sindicos']) }}" id="sindico">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.sindicos.create') }}" class="nav-link {{ active_class(['admin_franqueado/sindicos/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.sindicos.index') }}" class="nav-link {{ active_class(['admin_franqueado/sindicos']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin_franqueado/afiliados/*','admin_franqueado/afiliados']) }}">
        <a class="nav-link" data-toggle="collapse" href="#afiliado" role="button" aria-expanded="{{ is_active_route(['admin_franqueado/afiliados/*','admin_franqueado/afiliados']) }}" aria-controls="afiliado">
          <i class="link-icon" data-feather="smile"></i>
          <span class="link-title">Afiliado</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin_franqueado/afiliados/*','admin_franqueado/afiliados']) }}" id="afiliado">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.afiliados.create') }}" class="nav-link {{ active_class(['admin_franqueado/afiliados/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.afiliados.index') }}" class="nav-link {{ active_class(['admin_franqueado/afiliados']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item {{ active_class(['admin_franqueado/vistorias/*','admin_franqueado/vistorias']) }}">
        <a class="nav-link" data-toggle="collapse" href="#vistoria" role="button" aria-expanded="{{ is_active_route(['admin_franqueado/vistorias/*','admin_franqueado/vistorias']) }}" aria-controls="vistoria">
          <i class="link-icon" data-feather="camera"></i>
          <span class="link-title">Vistorias</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin_franqueado/vistorias/*','admin_franqueado/vistorias']) }}" id="vistoria">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.vistorias.create') }}" class="nav-link {{ active_class(['admin_franqueado/vistorias/create']) }}">Nova</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.vistorias.index') }}" class="nav-link {{ active_class(['admin_franqueado/vistorias']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item {{ active_class(['admin_franqueado/orcamentos/*','admin_franqueado/orcamentos']) }}">
        <a class="nav-link" data-toggle="collapse" href="#orcamento" role="button" aria-expanded="{{ is_active_route(['admin_franqueado/orcamentos/*','admin_franqueado/orcamentos']) }}" aria-controls="orcamento">
          <i class="link-icon" data-feather="file-text"></i>
          <span class="link-title">Solicitações</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin_franqueado/orcamentos/*','admin_franqueado/orcamentos']) }}" id="orcamento">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.orcamentos.create') }}" class="nav-link {{ active_class(['admin_franqueado/orcamentos/create']) }}">Novo</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin_franqueado.orcamentos.index') }}" class="nav-link {{ active_class(['admin_franqueado/orcamentos']) }}">Listar</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Configurações</li>

      <li class="nav-item {{ active_class(['/']) }}">
        <a class="nav-link" href="{{ route('admin_franqueado.franqueado_regioes.index') }}" role="button" aria-expanded="{{ is_active_route(['admin_franqueado/franqueado_regioes/*','admin_franqueado/franqueado_regioes']) }}" aria-controls="rua">
          <i class="link-icon" data-feather="compass"></i>
          <span class="link-title">Regiões</span>
        </a>
      </li>

      <li class="nav-item {{ active_class(['/']) }}">
        <a class="nav-link" href="{{ route('admin_franqueado.planos_disponiveis_franqueado.index') }}" role="button" aria-expanded="{{ is_active_route(['admin_franqueado/planos_disponiveis_franqueado/*','admin_franqueado/planos_disponiveis_franqueado']) }}" aria-controls="rua">
          <i class="link-icon" data-feather="slack"></i>
          <span class="link-title">Planos</span>
        </a>
      </li>

    </ul>
  </div>
</nav>