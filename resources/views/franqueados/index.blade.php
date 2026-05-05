@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="panel panel-secondary">
    @if(Session::has('success_message'))
    <div class="alert alert-success">
        <span class="glyphicon glyphicon-ok"></span>
        {!! session('success_message') !!}

        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>
    @endif

    <div class="panel-heading clearfix">

        <div class="pull-left">
            <h4 class="mt-5 mb-5">Listagem de franqueados</h4>
        </div>

        <div class="btn-group btn-group-sm pull-right" role="group">
            <a href="{{ route('franqueados.create') }}" class="btn btn-success" title="Criar novo franqueado">
                <i data-feather="plus"></i> <span>Novo franqueado</span>
            </a>
        </div>

    </div>



    @if(count($franqueados) == 0)
    <div class="panel-body text-center">
        <h4>Nenhum franqueado listado</h4>
    </div>
    @else
    <div class="panel-body panel-body-with-table">
        <div class="table-responsive">
            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th width="200">Nome</th>
                        <th>Contato</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($franqueados as $franqueado)
                    <td>
                        {{ $franqueado->nome }}
                        <div>
                            <form method="POST" action="{!! route('admin_franqueado.autoLogin',['franqueado_id' => $franqueado->id] ) !!}" accept-charset="UTF-8">
                                <input name="_method" value="POST" type="hidden">
                                {{ csrf_field() }}
                                <button class="btn btn-primary">Logar</button>
                            </form>
                        </div>
                    </td>
                    <td>
                        <label class="badge badge-default mail-link" style="font-size: 14px;">{{ $franqueado->email }}</label>
                        <br>
                        <label class="badge badge-default whats-link" style="font-size: 14px;" target="_blank">{{ $franqueado->telefone_responsavel }}</label>
                    </td>
                    <td align="right">

                        <form method="POST" action="{!! route('franqueados.destroy', $franqueado->id) !!}" accept-charset="UTF-8">
                            <input name="_method" value="DELETE" type="hidden">
                            {{ csrf_field() }}

                            <div class="btn-group btn-group-xs pull-right" role="group">
                                <a href="{{ route('franqueados.show', $franqueado->id ) }}" class="btn btn-info" title="Ver franqueado">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('franqueados.edit', $franqueado->id ) }}" class="btn btn-primary" title="Editar franqueado">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <button type="submit" class="btn btn-danger" title="Remover franqueado" onclick="return confirm('Deseja realmete excluir o franqueado {{$franqueado->nome}}?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>

                        </form>

                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif


    @endsection

    @push('plugin-scripts')
    <script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
    @endpush

    @push('custom-scripts')
    <script src="{{ asset('assets/js/dragula.js') }}"></script>
    @endpush
