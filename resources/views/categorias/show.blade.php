@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de categorias</h4>
                <dl class="dl-horizontal">
                    <dt>Status</dt>
                    <dd>
                        @if($categoria->status==1)
                            <label class="badge badge-success">Ativo</label>
                        @elseif($categoria->status==-1)
                            <label class="badge badge-danger">Inativo</label>
                        @endif
                    </dd>

                    <dt>Status liberação afiliados</dt>
                    <dd>
                        @if($categoria->show_afiliado==1)
                            <label class="badge badge-success">O Afiliado pode selecionar no App</label>
                        @elseif($categoria->stshow_afiliadoatus==0)
                            <label class="badge badge-warning">O afiliado não pode selecionar no App</label>
                        @endif
                    </dd>

                    <dt>Nome </dt>
                    <dd>{{ $categoria->nome }}</dd>
                    <dt>Descricao</dt>
                    <dd>{{ $categoria->descricao }}</dd>
                    <dt>Imagem</dt>
                    <dd>
                        @if($categoria->imagem)
                            <img src="{{ Storage::url($categoria->imagem) }}" alt="--">
                        @else
                            <a href="{{ route('categorias.edit', $categoria->id ) }}">Adicionar imagem</a>
                        @endif
                    </dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('categorias.destroy', $categoria->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('categorias.index') }}" class="btn btn-primary" title="Ver todos categorias">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('categorias.create') }}" class="btn btn-success" title="Novo categoria">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('categorias.edit', $categoria->id ) }}" class="btn btn-primary" title="Editar categoria">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover categoria" onclick="return confirm('Deseja realmete excluir o categoria {{$categoria->nome}}?')">
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
