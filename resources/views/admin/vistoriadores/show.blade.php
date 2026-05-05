<?php use App\Uteis\Formatacao; ?>
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
                <h4 class="card-title">Visualizando dados do vistoriador</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Dados do vistoriador</h5>
                                <dl class="dl-horizontal">
                                    <dt>Nome </dt>
                                    <dd>{{ $vistoriador->nome }}</dd>
                                    <dt>Usuario app</dt>
                                    <dd class="mail-link">{{ $vistoriador->usuarioApp->email }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Vistorias</h5>
                                <a href="../vistorias/create" class="btn btn-success">Nova vistoria</a>

                                <table class="dataTable table table-striped">
                                    <thead>
                                        <th>Condomínio</th>
                                        <th>Síndico</th>
                                        <th>Afiliado</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        @foreach($vistorias as $vistoria)
                                            <tr>
                                                <td>
                                                    <h6>Dia <?php echo Formatacao::data($vistoria->data_vistoria) ?></h6>
                                                    
                                                    <b>Condomínio: {{$vistoria->orcamento->condominio->nome}}</b>.
                                                    <br>{{$vistoria->orcamento->condominio->endereco}}. {{$vistoria->orcamento->condominio->numero}}. 
                                                    {{$vistoria->orcamento->condominio->cep}}. {{$vistoria->orcamento->condominio->bairro->nome}}, {{$vistoria->orcamento->condominio->bairro->cidade->nome}}/{{$vistoria->orcamento->condominio->bairro->cidade->estado->uf}}
                                                </td>
                                                <td>{{$vistoria->orcamento->condominio->sindico->nome}}</td>
                                                <td>{{$vistoria->orcamento->afiliado->razao_social}}</td>
                                                <td>
                                                    <label class="badge badge-warning">Agendado</label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="pull-right">
                    <form method="POST" action="{!! route('admin.vistoriadores.destroy', $vistoriador->id) !!}" accept-charset="UTF-8">
                        <input name="_method" value="DELETE" type="hidden">
                        {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.vistoriadores.index') }}" class="btn btn-primary" title="Ver todos vistoriadores">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('admin.vistoriadores.create') }}" class="btn btn-success" title="Novo vistoriador">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('admin.vistoriadores.edit', $vistoriador->id ) }}" class="btn btn-primary" title="Editar vistoriador">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover vistoriador" onclick="return confirm('Deseja realmete excluir o vistoriador {{$vistoriador->nome}}?')">
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
