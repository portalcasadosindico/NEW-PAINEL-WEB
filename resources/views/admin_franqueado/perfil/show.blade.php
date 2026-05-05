@extends('admin_franqueado.layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Perfil</h4>
                <dl class="dl-horizontal">
                    <dt>Nome</dt>
                    <dd>{{ $franqueado->nome }}</dd>
                    <dt>Email</dt>
                    <dd>{{ $franqueado->email }}</dd>
                    <dt>Cnpj</dt>
                    <dd>{{ $franqueado->cnpj }}</dd>
                    <dt>Inscricao estadual</dt>
                    <dd>{{ $franqueado->inscricao_estadual }}</dd>
                    <dt>Inscricao municipal</dt>
                    <dd>{{ $franqueado->inscricao_municipal }}</dd>
                    <dt>Cpf responsavel</dt>
                    <dd>{{ $franqueado->cpf_responsavel }}</dd>
                    <dt>Rg responsavel</dt>
                    <dd>{{ $franqueado->rg_responsavel }}</dd>
                    <dt>Profissao responsavel</dt>
                    <dd>{{ $franqueado->profissao_responsavel }}</dd>
                    <dt>Telefone responsavel</dt>
                    <dd>{{ $franqueado->telefone_responsavel }}</dd>
                    <dt>Cep</dt>
                    <dd>{{ $franqueado->cep }}</dd>
                    <dt>Estado</dt>
                    <dd>{{ $franqueado->estado }}</dd>
                    <dt>Cidade</dt>
                    <dd>{{ $franqueado->cidade }}</dd>
                    <dt>Bairro</dt>
                    <dd>{{ $franqueado->bairro }}</dd>
                    <dt>Rua</dt>
                    <dd>{{ $franqueado->rua }}</dd>
                </dl>

                <div class="pull-right">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin_franqueado.profile.edit', $franqueado->id ) }}" class="btn btn-primary" title="Editar franqueado">
                                <i class="fa fa-pencil"></i>
                            </a>

                        </div>
                </div>

        	</div>
        </div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dragula.js') }}"></script>
@endpush
