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
            <h4 class="mt-5 mb-5">Listagem de responsavel politicas</h4>
        </div>

        <div class="btn-group btn-group-sm pull-right" role="group">
            <a href="{{ route('responsavel_politicas.create') }}" class="btn btn-success" title="Criar nova responsavelPolitica">
                <i data-feather="plus"></i> <span>Novo responsavel Politica</span>
            </a>
        </div>

    </div>



    @if(count($responsavelPoliticas) == 0)
    <div class="panel-body text-center">
        <h4>Nenhum responsavel Politica listado</h4>
    </div>
    @else
    <div class="panel-body panel-body-with-table">
        <div class="table-responsive">
            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th width="200">Nome</th>
                        <th>Politica Privacidade</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($responsavelPoliticas as $responsavelPolitica)
                    <td>{{ $responsavelPolitica->nome }}</td>
                    <td>{{ optional($responsavelPolitica->PoliticaPrivacidade)->titulo }}</td>
                    <td align="right">

                        <div class="btn-group btn-group-xs pull-right" role="group">
                            <a href="{{ route('responsavel_politicas.show', $responsavelPolitica->id ) }}" class="btn btn-info" title="Ver responsavelPolitica">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('responsavel_politicas.edit', $responsavelPolitica->id ) }}" class="btn btn-primary" title="Editar responsavelPolitica">
                                <i class="fa fa-pencil"></i>
                            </a>

                        </div>

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
