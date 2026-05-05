<?php

use Illuminate\Support\Facades\Auth;

$email = Auth::guard('franqueados')->user()->email;
$name = Auth::guard('franqueados')->user()->nome;
?>
<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        {{-- <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i data-feather="search"></i>
        </div>
      </div>
      <input type="text" class="form-control" id="pesquisa_table_input" placeholder="Pesquise">
    </div> --}}
        <ul class="navbar-nav">
            {{-- <li class="nav-item dropdown nav-notifications">
        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="bell"></i>
          <div class="indicator">
            <div class="circle"></div>
          </div>
        </a>
        <div class="dropdown-menu" aria-labelledby="notificationDropdown">
          <div class="dropdown-header d-flex align-items-center justify-content-between">
            <p class="mb-0 font-weight-medium">6 New Notifications</p>
            <a href="javascript:;" class="text-muted">Clear all</a>
          </div>
          <div class="dropdown-body">
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="user-plus"></i>
              </div>
              <div class="content">
                <p>New customer registered</p>
                <p class="sub-text text-muted">2 sec ago</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="gift"></i>
              </div>
              <div class="content">
                <p>New Order Recieved</p>
                <p class="sub-text text-muted">30 min ago</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="alert-circle"></i>
              </div>
              <div class="content">
                <p>Server Limit Reached!</p>
                <p class="sub-text text-muted">1 hrs ago</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="layers"></i>
              </div>
              <div class="content">
                <p>Apps are ready for update</p>
                <p class="sub-text text-muted">5 hrs ago</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="download"></i>
              </div>
              <div class="content">
                <p>Download completed</p>
                <p class="sub-text text-muted">6 hrs ago</p>
              </div>
            </a>
          </div>
          <div class="dropdown-footer d-flex align-items-center justify-content-center">
            <a href="javascript:;">View all</a>
          </div>
        </div>
      </li> --}}
            <li class="nav-item dropdown nav-profile">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                <a href="{{ route('admin_franqueado.profile.edit') }}" class="nav-link">
                                    <i data-feather="edit-2"></i>
                                    <span>Editar</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin_franqueado.logout') }}" class="nav-link">
                                    <i data-feather="log-out"></i>
                                    <span>Sair</span>
                                </a>
                            </li>
                            @php
                            session_start();
                            @endphp
                            @if(isset($_SESSION['login_as_admin']) && $_SESSION['login_as_admin'])
                            <li class="nav-item">
                                <form method="POST" action="{!! route('admin_franqueado.autoLogout') !!}" accept-charset="UTF-8">
                                    <input name="_method" value="POST" type="hidden">
                                    {{ csrf_field() }}
                                    <button class="btn btn-primary">Voltar</button>
                                </form>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
