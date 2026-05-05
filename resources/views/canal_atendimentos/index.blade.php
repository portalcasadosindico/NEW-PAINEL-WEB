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
            <h4 class="mt-5 mb-5">Listagem de canal atendimentos</h4>
        </div>

        <div class="btn-group btn-group-sm pull-right" role="group">
            <a href="{{ route('canal_atendimentos.create') }}" class="btn btn-success" title="Criar novo canal atendimento">
                <i data-feather="plus"></i> <span>Novo canal atendimento</span>
            </a>
        </div>

    </div>



    @if(count($canalAtendimentos) == 0)
    <div class="panel-body text-center">
        <h4>Nenhum canal atendimento listado</h4>
    </div>
    @else
    <div class="panel-body panel-body-with-table">
        <div class="table-responsive">
            <table class="table table-striped ">
                <thead>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach($canalAtendimentos as $canalAtendimento)
                    <td>#{{ $canalAtendimento->id }}</td>
                    <td>{{ $canalAtendimento->nome }}</td>
                    <td>{{ $canalAtendimento->email }}</td>
                    <td>{{ $canalAtendimento->telefone }}</td>

                    <td align="right">

                        <form method="POST" action="{!! route('canal_atendimentos.destroy', $canalAtendimento->id) !!}" accept-charset="UTF-8">
                            <input name="_method" value="DELETE" type="hidden">
                            {{ csrf_field() }}

                            <div class="btn-group btn-group-xs pull-right" role="group">
                                <a href="{{ route('canal_atendimentos.show', $canalAtendimento->id ) }}" class="btn btn-info" title="Ver canalAtendimento">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('canal_atendimentos.edit', $canalAtendimento->id ) }}" class="btn btn-primary" title="Editar canalAtendimento">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <button type="submit" class="btn btn-danger" title="Remover canalAtendimento" onclick="return confirm('Deseja realmete excluir o canalAtendimento {{$canalAtendimento->nome}}?')">
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
