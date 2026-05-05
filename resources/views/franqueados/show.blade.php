@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
                <h4 class="card-title">Visualizando dados de franqueados</h4>
                



                <div class="row">
                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Dados da empresa</h5>
                                <div class="row">
                                    <div class="col-md-6" style="padding: 8px;">
                                        <h5>Contrato Social</h5>
                                        <div style="overflow: hidden;">
                                            <img style="width: 100%; opacity: 0.30;" src="{{ asset('assets/images/contrato-social.png') }}">
                                            @if(isset($franqueado->contrato_social))
                                                <a style="position: absolute; left: 63px; top: 69px;" href="{{Storage::url($franqueado->contrato_social)}}" class="btn btn-primary" target="_blank">Ver arquivo</a>
                                            @else
                                                <a style="position: absolute; left: 63px; top: 69px;" href="{{ route('franqueados.edit', $franqueado->id) }}" class="btn btn-danger">Upload</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Cartão CNPJ</h5>
                                        <div style="overflow: hidden;">
                                            <img style="width: 100%; opacity: 0.30; position: relative; top: -34px;" src="{{ asset('assets/images/cartao-cnpj.png') }}">
                                            @if(isset($franqueado->cartao_cnpj))
                                                <a style="position: absolute; left: 63px; top: 69px;" href="{{Storage::url($franqueado->cartao_cnpj)}}" class="btn btn-primary" target="_blank">Ver arquivo</a>
                                            @else
                                                <a style="position: absolute; left: 63px; top: 69px;" href="{{ route('franqueados.edit', $franqueado->id) }}" class="btn btn-danger">Upload</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <dl class="dl-horizontal">
                                    <dt>Nome</dt>
                                    <dd>{{ $franqueado->nome }}</dd>
                                    <dt>E-mail login</dt>
                                    <dd>{{ $franqueado->email }}</dd>
                                    <dt>E-mail Autentique</dt>
                                    <dd>{{ $franqueado->email_autentique }}</dd>
                                    <dt>Cnpj</dt>
                                    <dd>{{ $franqueado->cnpj }}</dd>
                                    <dt>Inscricao estadual</dt>
                                    <dd>{{ $franqueado->inscricao_estadual }}</dd>
                                    <dt>Inscricao municipal</dt>
                                    <dd>{{ $franqueado->inscricao_municipal }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Dados do responsável</h5>
                                <dl class="dl-horizontal">
                                    <dt>Cpf responsavel</dt>
                                    <dd>{{ $franqueado->cpf_responsavel }}</dd>
                                    <dt>Rg responsavel</dt>
                                    <dd>{{ $franqueado->rg_responsavel }}</dd>
                                    <dt>Profissao responsavel</dt>
                                    <dd>{{ $franqueado->profissao_responsavel }}</dd>
                                    <dt>Telefone responsavel</dt>
                                    <dd>{{ $franqueado->telefone_responsavel }}</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Endereço</h5>
                                <dl class="dl-horizontal">
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
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="pull-right">
                    <form method="POST" action="{!! route('franqueados.destroy', $franqueado->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('franqueados.index') }}" class="btn btn-primary" title="Ver todos franqueados">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('franqueados.create') }}" class="btn btn-success" title="Novo franqueado">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('franqueados.edit', $franqueado->id ) }}" class="btn btn-primary" title="Editar franqueado">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover franqueado" onclick="return confirm('Deseja realmete excluir o franqueado {{$franqueado->nome}}?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </form>
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
