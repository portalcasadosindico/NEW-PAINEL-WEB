<?php

use App\Models\BlogComentario;
use App\Uteis\Formatacao;
?>
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card col-8">
        	<div class="card-body">
                <dl class="dl-horizontal">
                    <dt>Status</dt>
                    <dd>{{ $blog->status }}</dd>
                </dl>
                <hr>
                <p>
                    @if($blog->imagem_principal)
                        <img src="{{ Storage::url($blog->imagem_principal) }}" style="max-width: 100%;" alt="logo">
                    @endif
                </p>
                <h2>{{ $blog->nome }}</h2>
                <p><b><?php echo $blog->resumo; ?></b></p>
                <label><b style="text-size: 12px; color: #676767;">Em <?php echo Formatacao::data($blog->data_cadastro); ?></b></label>
                <hr>
                <p style="margin-top: 16px;"><?php echo $blog->descricao; ?></p>
                <p style="text-align: right; margin-top: 16px; font-size: 12px; font-style: italic;"><?php echo $blog->fonte; ?></p>
                
                <h6>TAG's</h6>
                @foreach($tags as $i=> $t)
                    <label class="badge badge-primary">{{$t->nome}}</label>
                @endforeach


                <h6 style="margin-top: 15px; ">Comentários ({{count($comentarios)}})</h6>
                @if(count($comentarios)>0)
                   
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Comentário</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comentarios as $i => $coment)
                                <tr id="coment{{$coment->id}}">
                                    <td>#{{$coment->id}}</td>
                                    <td>
                                        <b>Autor:</b> {{$coment->autor}}<br>
                                        <b>E-mail:</b> <span class="mail-link">{{$coment->email}}</span><br>
                                        {{$coment->titulo}}<br>
                                        <?php echo nl2br( strip_tags($coment['descricao']) ); ?>
                                        <?php if($coment['blog_comentario_pai_id']>0){ ?>
                                            <br><br>
                                            <label style="font-size: 12px;" class="badge badge-warning">Resposta ao comentário <a href="javascript:void(0)" onclick="showHideResposta(<?php echo $coment['id']; ?>)">#{{$coment->blog_comentario_pai_id}}</a></label>
                                            <br><br>
                                            <div style="display: none;" class="resposta-comentario-{{$coment->id}}">
                                                <?php
                                                    $comentarioPai = BlogComentario::where("id", $coment['blog_comentario_pai_id'])->first();
                                                    echo nl2br( strip_tags($comentarioPai['descricao']) );
                                                ?>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        @if($coment->status=="aceito" || $coment->status=="aprovado")
                                            <label class="badge badge-success">Aceito</label>
                                            <br><br>
                                            <a href="javascript:void(0)" onclick="alterarStatus('recusado', <?php echo $coment['id']; ?>)">Recusar</a>
                                        @elseif($coment->status=="pendente")
                                            <label class="badge badge-warning">Pendente</label>
                                            <br><br>
                                            <a href="javascript:void(0)" onclick="alterarStatus('aceito', <?php echo $coment['id']; ?>)">Aceitar</a> - <a  href="javascript:void(0)" onclick="alterarStatus('recusado', <?php echo $coment['id']; ?>)">Recusar</a>
                                        @elseif($coment->status=="recusado")
                                            <label class="badge badge-warning">Recusado</label>
                                            <br><br>
                                            <a  href="javascript:void(0)" onclick="alterarStatus('aceito', <?php echo $coment['id']; ?>)">Aceitar</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <label class="badge badge-warning">Nenhum comentário ainda.</label>
                @endif
                <br><br>
                <div class="pull-right">
                    <form method="POST" action="{!! route('blogs.destroy', $blog->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('blogs.index') }}" class="btn btn-primary" title="Ver todos blogs">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('blogs.create') }}" class="btn btn-success" title="Novo blog">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('blogs.edit', $blog->id ) }}" class="btn btn-primary" title="Editar blog">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover blog" onclick="return confirm('Deseja realmete excluir o post {{$blog->nome}}?')">
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

  <script>
      function showHideResposta(id){
            if($(".resposta-comentario-"+id).css("display")=="none"){
                $(".resposta-comentario-"+id).show(300);
            } else {
                $(".resposta-comentario-"+id).hide(300);
            }
      }

      function alterarStatus(newStatus, id){
        $.getJSON({
            url: window.location.origin + "/admin/blogs/alterarStatus/"+newStatus+"/"+id,
            method: "GET",
            data: {
            },
            success: function(data) {
                window.location.reload();
            },
            error: function(e) {
                console.log(e);
                alert("Tente novamente em breve.");
            }
        });
        
      }
    </script>
@endpush
