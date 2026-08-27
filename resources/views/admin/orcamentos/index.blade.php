@php use App\Models\FranqueadoRegiao;
use App\Uteis\Formatacao;
use App\Uteis\StatusOrcamento;
@endphp
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
        <div>
            <h4 class="mt-5 mb-5">Listagem de solicitações/orçamentos/serviços</h4>
        </div>
        <div class="btn-group btn-group-sm " style="" role="group">
            <form action="{{route('admin.orcamentos.indexByDate')}}" method="POST" class="form-inline">
                @csrf
                <div class="form-group row m-2">
                    <label for="filtro_ano" class="mr-1">Ano</label>
                    <select id="filtro_ano" name="filtro_ano">
                        @for ($i = date("Y"); $i >= 2017; $i--)
                        <option value="{{$i}}" {{ ($ano ?? date("Y"))==strval($i) ? 'selected' : '' }}>
                            {{$i}}
                        </option>
                        @endfor
                        <option value="-1" {{ ($ano ?? date("Y"))=='-1' ? 'selected' : '' }}>Todos os meses</option>
                    </select>
                </div>

                <div class="form-group row m-2">
                    <label for="filtro_mes" class="mr-1">Mês</label>
                    <select id="filtro_mes" name="filtro_mes">
                        @for ($i=1; $i<=12; $i++) <option value="{{$i}}" {{ ($mes ?? date('m'))==strval($i) ? 'selected'
                            : '' }}>
                            {{Formatacao::mesTexto($i)}}
                            </option>
                            @endfor
                    </select>
                </div>

                <div class="form-group row m-2">
                    <label for="franqueado_ id" class="mr-1">Filtro por franqueado</label>
                    <select class="form-control" id="franqueado_id" name="franqueado_id">
                        <option value="0">Todas as franquias</option>
                        @foreach ($franqueados as $key => $franqueado)
                        <option {{($franqueado_id==$franqueado->id) ? "selected" : ''}} value="{{ $franqueado->id }}">
                            {{ $franqueado->nome }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary m-1" type="submit">Pesquisar</button>
                <a href="{{route('admin.orcamentos.index')}}" class="btn btn-secondary m-1">Limpar filtros</a>
            </form>
        </div>

        @if($franqueado_id == null)
        <div class="btn-group btn-group-sm mt-2" role="group">
            <form action="{{route('admin.orcamentos.index')}}" method="GET" class="form-inline">
                <div class="form-group row m-2">
                    <label for="q" class="mr-1">Buscar por título, ID ou síndico</label>
                    <input type="text" class="form-control" id="q" name="q" value="{{ request('q') }}">
                </div>
                <button class="btn btn-primary m-1" type="submit">Buscar</button>
                @if(request('q'))
                <a href="{{route('admin.orcamentos.index')}}" class="btn btn-secondary m-1">Limpar busca</a>
                @endif
            </form>
        </div>
        @endif


        @if(count($orcamentos) == 0 && $franqueado_id != null)
        <div class="panel-body text-center">
            <h4>Nenhuma solicitação listada</h4>
        </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table data-page-length="15" id="dataTableExample" class="table table-striped dataTableDesc no-footer" role="grid" aria-describedby="dataTableExample_info">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th width="150">Título</th>
                            <th>Região</th>
                            <th>Síndico/Afiliado</th>
                            <th>Contrato</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orcamentos as $orcamento)
                        <td>{{ $orcamento->id }}</td>

                        <td>
                            @if($orcamento->modo=="debug")
                            <label class="badge badge-danger mb-2">MODO TESTE</label>
                            <br>
                            @endif
                            <h6>Categoria: <?php echo isset($orcamento->categoria->nome) ? $orcamento->categoria->nome : ''; ?></h6>
                            <br>
                            <h6>{{ $orcamento->nome }}</h6>
                            <br>
                            Cadastrado em
                            <b>
                                <?php echo Formatacao::data($orcamento->data_cadastro); ?>
                            </b>
                            <br>

                        </td>
                        <td>{{ $orcamento->regiao ? $orcamento->regiao->nome : "SEM REGIÃO" }}
                            @php
                            $franqueadoRegiaoAuxiliar = FranqueadoRegiao::where("regiao_id", ($orcamento->regiao ?
                            $orcamento->regiao->id : null))->orderBy("id","desc")->first();
                            $franqueado = ($franqueadoRegiaoAuxiliar ? $franqueadoRegiaoAuxiliar->franqueado : null)
                            @endphp


                            <span class="citacao">- Franquia,
                                {{($franqueado) ? $franqueado->nome : ''}}</span>
                            @if($assinaturas ?? '')
                            @if(isset($assinaturas[$orcamento->id]['franqueado']) &&
                            $assinaturas[$orcamento->id]['franqueado']->signed)
                            <label class="badge badge-success" title="Autenticado pelo Autentique">{{"Assinado pelo
                                franqueado em ".Formatacao::data($assinaturas[$orcamento->id]['franqueado']->signed,
                                false, false)}}</label>
                            @elseif($orcamento->formato_contrato_atual==2)
                            <label class="badge badge-success" title="Autenticado pelo Franqueado">Assinado pelo
                                franqueado</label>
                            @endif
                            @else
                            <label class="badge badge-warning">O franqueado não assinou</label>
                            @endif
                            <br>
                            @if($orcamento->status==StatusOrcamento::$CANCELADO_PELO_FRANQUEADO ||
                            $orcamento->status==StatusOrcamento::$CANCELADO_PELO_ADMIN ||
                            $orcamento->status==StatusOrcamento::$CANCELADO_PELO_AFILIADO ||
                            $orcamento->status==StatusOrcamento::$CANCELADO_PELO_SINDICO)
                            <label class="badge badge-danger">{{StatusOrcamento::getLabel($orcamento->status)}}</label>
                            @else
                            <label class="badge badge-info">{{"Status Geral:
                                ".StatusOrcamento::getLabel($orcamento->status)}}</label>
                            @endif


                        </td>

                        <td>
                            <b>Síndico</b><br>{{ isset($orcamento->condominio->sindico->nome)
                            ?$orcamento->condominio->sindico->nome : ''}}<br>
                            @if($assinaturas ?? '')
                            @if(isset($assinaturas[$orcamento->id]['sindico']) &&
                            $assinaturas[$orcamento->id]['sindico']->signed)
                            <label class="badge badge-success" title="Autenticado pelo Autentique">Assinado em
                                <?php echo Formatacao::data($assinaturas[$orcamento->id]['sindico']['signed'], false, false); ?>
                            </label>
                            @elseif($orcamento->formato_contrato_atual==2)
                            <label class="badge badge-success" title="Autenticado pelo Franqueado">Assinado pelo
                                síndico</label>
                            @endif
                            @else
                            <label class="badge badge-warning">Ainda não assinou</label>
                            @endif
                            @if($orcamento->data_rejeicao_sindico)
                            <br><label class="badge badge-success">Rejeitado em
                                <?php echo Formatacao::data($orcamento->data_rejeicao_sindico, false, false); ?>
                            </label>

                            @endif
                            <br>

                            @if($orcamento->status==StatusOrcamento::$CANCELADO_PELO_FRANQUEADO ||
                            $orcamento->status==StatusOrcamento::$CANCELADO_PELO_ADMIN ||
                            $orcamento->status==StatusOrcamento::$CANCELADO_PELO_AFILIADO ||
                            $orcamento->status==StatusOrcamento::$CANCELADO_PELO_SINDICO)
                            <label class="badge badge-danger">{{StatusOrcamento::getLabel($orcamento->status_sindico)}}</label><br><br>
                            @else
                            <label class="badge badge-info">{{"Status Síndico:
                                ".StatusOrcamento::getLabel($orcamento->status_sindico)}}</label><br><br>
                            @endif

                            <br><br>
                            <b>Afiliado</b><br>
                            @if(isset($orcamento->afiliado()->withTrashed()->first()->razao_social))
                            {{$orcamento->afiliado()->withTrashed()->first()->razao_social}}
                            @if(isset($assinaturas[$orcamento->id]['afiliado']) &&
                            $assinaturas[$orcamento->id]['afiliado']->signed)
                            <br><label class="badge badge-success" title="Autenticado pelo Autentique">Assinado em
                                <?php echo Formatacao::data($assinaturas[$orcamento->id]['afiliado']['signed'], false, false); ?>
                            </label>
                            @elseif($orcamento->formato_contrato_atual==2)
                            <label class="badge badge-success" title="Autenticado pelo Franqueado">Assinado pelo
                                afiliado</label>
                            @else
                            <br><label class="badge badge-warning">Ainda não assinou</label>
                            @endif
                            @if($orcamento->data_rejeicao_afiliado)
                            <br><label class="badge badge-danger">Rejeitado em
                                <?php echo Formatacao::data($orcamento->data_rejeicao_afiliado, false, false); ?>
                            </label>
                            @endif
                            <br>
                            @else
                            <label class="badge badge-danger">Solicitação sem afiliado</label>
                            <a href="{{ route('admin.orcamentos.edit', $orcamento->id ) }}#add_afiliado">Selecionar</a>

                            @endif

                            @if($orcamento->status==StatusOrcamento::$CANCELADO_PELO_FRANQUEADO ||
                            $orcamento->status==StatusOrcamento::$CANCELADO_PELO_ADMIN ||
                            $orcamento->status==StatusOrcamento::$CANCELADO_PELO_AFILIADO ||
                            $orcamento->status==StatusOrcamento::$CANCELADO_PELO_SINDICO)
                            <label class="badge badge-danger">{{StatusOrcamento::getLabelAfiliado($orcamento->status_afiliado)}}</label><br><br>
                            @else
                            <label class="badge badge-info">{{"Status Afiliado:
                                ".StatusOrcamento::getLabelAfiliado($orcamento->status_afiliado)}}</label><br><br>
                            @endif

                        </td>
                        <td>
                            <label clas="badge badge-default"><b>{{$orcamento->titulo_contrato}}</b></label><br>
                            @if($orcamento->formato_contrato_atual==2)
                            <a href="{{$orcamento->contrato_assinado ? $orcamento->contrato_assinado : "
                                https://admin2.casadosindico.srv.br/storage/".$orcamento->contrato}}" class="btn btn-success" target="_blank">Ver contrato</a>
                            @elseif($orcamento->formato_contrato_atual==1)
                            <a href="{{$orcamento->contrato_original ? $orcamento->contrato_original : "
                                https://admin2.casadosindico.srv.br/storage/".$orcamento->contrato}}" class="btn btn-secondary" target="_blank">Ver contrato</a>
                            <a href="{{$orcamento->contrato_assinado ? $orcamento->contrato_assinado : "
                                https://admin2.casadosindico.srv.br/storage/".$orcamento->contrato}}" class="btn
                                btn-success" target="_blank">Ver contrato assinado</a>
                            @else
                            <a class="btn btn-danger" href="{{ route('admin.orcamentos.edit', $orcamento->id ) }}#upload">Upload
                                do
                                contrato</a>
                            @endif

                        </td>
                        <td align="right">

                            <form method="POST" action="{!! route('admin.orcamentos.destroy', $orcamento->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                <div class="btn-group btn-group-xs pull-right" role="group">
                                    <a href="{{ route('admin.orcamentos.edit', $orcamento->id ) }}" class="btn btn-primary" title="Editar orcamento">
                                        <i class="fa fa-pencil"></i>
                                    </a>

                                    <button type="submit" class="btn btn-danger" title="Remover orcamento" onclick="return confirm('Deseja realmete excluir o orcamento {{$orcamento->nome}}?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>

                            </form>

                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(method_exists($orcamentos, 'links'))
                <div class="mt-3">
                    {{ $orcamentos->links('pagination::bootstrap-4') }}
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dragula.js') }}"></script>
@endpush
@endsection