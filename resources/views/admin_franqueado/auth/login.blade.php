@extends('admin_franqueado.layout.masterlogin')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-4 pr-md-0">
          <div class="auth-left-wrapper" style="background-image: url({{ asset('assets/images/screen.png') }}); background-size: contain; background-repeat: no-repeat;">

        </div>
          </div>
          <div class="col-md-8 pl-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <a href="#" class="noble-ui-logo d-block mb-2">Casa do <span>Síndico</span></a>
              <h5 class="text-muted font-weight-normal mb-4">Bem vindo ao painel administrativo da sua franquia</h5>
              @if($errors->any())
              <div class="alert alert-danger mb-3">
                {{ $errors->first() }}
              </div>
              @endif
              <form class="" action="{{ route('admin_franqueado.login.do') }}" method="POST">
              {{ csrf_field() }}
                <div class="form-group">
                  <label for="email">E-mail</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                  <label for="password">Senha</label>
                  <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" placeholder="Senha">
                </div>
                <div class="form-check form-check-flat form-check-primary">
                  <label class="form-check-label">
                    <input type="checkbox" class="form-check-input">
                    Lembrar-me
                  </label>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0">Acessar</button>
                </div>
                <!-- <a href="{{ url('/auth/register') }}" class="d-block mt-3 text-muted">Not a user? Sign up</a> -->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection