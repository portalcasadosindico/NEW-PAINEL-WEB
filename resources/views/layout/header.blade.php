<?php

use Illuminate\Support\Facades\Auth;

$email = Auth::guard('admins')->user()->email;
$name = Auth::guard('admins')->user()->nome;
?>
<nav class="navbar">
  <a href="#" class="sidebar-toggler">
    <i data-feather="menu"></i>
  </a>
  <div class="navbar-content">
    <a class="btn btn-outline-success mt-2 mb-2 p-3" target="_blank" href="{{ route('gerarExcel') }}">Gerar relatório
      Excel</a>
    <ul class="navbar-nav">
      <li class="nav-item dropdown nav-profile">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <div class="col-sm-6 col-md-4 col-lg-3"> <i data-feather="user"></i></div>
        </a>
        <div class="dropdown-menu" aria-labelledby="profileDropdown">
          <div class="dropdown-header d-flex flex-column align-items-center">
            <div class="figure mb-3">
              <div class="col-sm-6 col-md-4 col-lg-3"> <i data-feather="user"></i></div>
            </div>
            <div class="info text-center">
              <p class="name font-weight-bold mb-0" style="white-space: pre-line;">{{ $name ?? '' }}</p>
              <p class="email text-muted mb-3">{{ $email ?? '' }}</p>
            </div>
          </div>
          <div class="dropdown-body">
            <ul class="profile-nav p-0 pt-3">
              <li class="nav-item">
                <a href="{{ route('admin.profile') }}" class="nav-link">
                  <i data-feather="user"></i>
                  <span>Perfil</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.profile.edit') }}" class="nav-link">
                  <i data-feather="edit-2"></i>
                  <span>Editar Perfil</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.logout') }}" class="nav-link">
                  <i data-feather="log-out"></i>
                  <span>Sair</span>
                </a>
              </li>

            </ul>
          </div>
        </div>
      </li>
    </ul>
  </div>
</nav>