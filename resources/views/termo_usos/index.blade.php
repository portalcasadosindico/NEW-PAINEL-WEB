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
            <h4 class="mt-5 mb-5">Listagem de termos de Uso</h4>
        </div>

        <div class="btn-group btn-group-sm pull-right" role="group">
            <a href="{{ route('termo_usos.create') }}" class="btn btn-success" title="Criar novo termoUso">
                <i data-feather="plus"></i> <span>Novo termo de uso</span>
            </a>
        </div>

    </div>



    @if(count($termoUsos) == 0)
    <div class="panel-body text-center">
        <h4>Nenhum termo de Uso listado</h4>
    </div>
    @else
    <div class="panel-body panel-body-with-table">
        <div class="table-responsive">
            <table data-page-length="25" id="dataTableExample" class="table table-striped dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                <thead>
                    <tr>
                        <th width="200">Titulo</th>
                        <th>Data cadastro</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($termoUsos as $termoUso)
                    <td>{{ $termoUso->titulo }}</td>
                    <td>{{ $termoUso->data_cadastro }}</td>
                    <td align="right">

                        <div class="btn-group btn-group-xs pull-right" role="group">
                            <a href="{{ route('termo_usos.show', $termoUso->id ) }}" class="btn btn-info" title="Ver termoUso">
                                <i class="fa fa-eye"></i>
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
