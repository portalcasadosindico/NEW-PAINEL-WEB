<!DOCTYPE html>
<html>
<head>
  <title>Casa do Síndico</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  
  <link rel="shortcut icon" href="{{ asset('assets/images/icon.png') }}">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <!-- plugin css -->
  <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" />
  <!-- end plugin css -->

  @stack('plugin-styles')

  <!-- common css -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <!-- end common css -->
<script>
  var dataTable;
</script>
  @stack('style')
  <style>
      .erro{
        border: 1px solid #ef9a9a !important;
      }

      .sucesso{
        border: 1px solid #00aa00 !important;
      }
    </style>
</head>
<body data-base-url="{{url('/')}}" class="sidebar-dark">

  <!-- <script src="{{ asset('assets/js/spinner.js') }}"></script> -->

  <div class="main-wrapper" id="app">
    @include('layout.sidebar')
    <div class="page-wrapper">
      @include('layout.header')
      <div class="page-content">
        @yield('content')
      </div>
      @include('layout.footer')
    </div>
  </div>

    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->

  
    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" >
    <script>
      
      function mailsLinkDo(){
        var mails_links = $(".mail-link");
        for(var i=0; i<mails_links.length; i++){
          var email = mails_links[i].innerHTML;
          mails_links[i].innerHTML = "<a href='mailto:"+email+"'>"+email+"</a>";
        }
      }

      function whatsLinkDo(){
        var whats_links = $(".whats-link");
        for(var i=0; i<whats_links.length; i++){
          var fone = whats_links[i].innerHTML;
          var foneLink = whats_links[i].innerHTML.replace(" ", "").replace("(", "").replace(")", "").replace("-", "");
          whats_links[i].innerHTML = "<a target='_blank' href='https://api.whatsapp.com/send?phone=55"+foneLink+"'>"+fone+"</a>";
        }
      }

     // $(document).ready( function () {
      mailsLinkDo();
      whatsLinkDo();


          dataTable = $('.dataTable').DataTable({
            "order": [[0, 'asc']],
            "paging": true,
            "lengthChange": true,
            //"searching": false,
            "language": {
              "paginate": {
                "next": "Próximo",
                "previous": "Anterior",
                "emptyTable": "Nada para listar",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "decimal": ",",
                "thousands": "."
              },
              "searchPlaceholder": "Pesquise",
              "search": ""
            }
          });

          dataTable2 = $('.dataTable2').DataTable({
            "order": [[0, 'asc']],
            "paging": true,
            "lengthChange": true,
            //"searching": false,
            "language": {
              "paginate": {
                "next": "Próximo",
                "previous": "Anterior",
                "emptyTable": "Nada para listar",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "decimal": ",",
                "thousands": "."
              },
              "searchPlaceholder": "Pesquise",
              "search": ""
            }
          });

          dataTableDesc = $('.dataTableDesc').DataTable({
            "order": [[0, 'desc']],
            "paging": true,
            "lengthChange": true,
            //"searching": false,
            "language": {
              "paginate": {
                "next": "Próximo",
                "previous": "Anterior",
                "emptyTable": "Nada para listar",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "decimal": ",",
                "thousands": "."
              },
              "searchPlaceholder": "Pesquise",
              "search": ""
            }
          });

          dataTableNoOrder = $('.dataTableNoOrder').DataTable({
                  "ordering":false
                , "paging": true
                , "lengthChange": true,
                //"searching": false,
                "language": {
                    "paginate": {
                        "next": "Próximo"
                        , "previous": "Anterior"
                        , "emptyTable": "Nada para listar"
                        , "info": "Mostrando página _PAGE_ de _PAGES_"
                        , "decimal": ","
                        , "thousands": "."
                    }
                    , "searchPlaceholder": "Pesquise"
                    , "search": ""
                }
            });

          dataTableNoOrderNoPage = $('.dataTableNoOrderNoPage').DataTable({
                  "ordering":false
                , "paging": false
                , "lengthChange": true,
                //"searching": false,
                "language": {
                    "paginate": {
                        "next": "Próximo"
                        , "previous": "Anterior"
                        , "emptyTable": "Nada para listar"
                        , "info": "Mostrando página _PAGE_ de _PAGES_"
                        , "decimal": ","
                        , "thousands": "."
                    }
                    , "searchPlaceholder": "Pesquise"
                    , "search": ""
                }
            });

      //} );
    </script>
    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <!-- end common js -->


    <script>
      function validarForm(){
        var enviar = true;
          var camposObrigatorios = document.getElementsByClassName("required");
          var contErros = 0;
          for(var i=0; i<camposObrigatorios.length; i++){
            var campo = camposObrigatorios[i].getElementsByClassName("form-control")[0];
            
            console.log(campo);
            if(!campo.value==""){
              $(campo).removeClass("erro");
              $(campo).addClass("sucesso");

              if(camposObrigatorios[i].getElementsByClassName("error").length>0){
                camposObrigatorios[i].getElementsByClassName("error")[0].style.display="none";
              }

              if(campo.type=="file"){
                if(camposObrigatorios[i].getElementsByClassName("custom-file-label").length>0){
                  var labelCampo = camposObrigatorios[i].getElementsByClassName("custom-file-label")[0];
                  $(labelCampo).removeClass("erro");
                  $(labelCampo).addClass("sucesso"); 
                }
              }

            } else {
              $(campo).removeClass("sucesso");
              $(campo).addClass("erro");

              if(camposObrigatorios[i].getElementsByClassName("error").length>0){
                camposObrigatorios[i].getElementsByClassName("error")[0].style.display="block";
              }

              if(campo.type=="file"){
                if(camposObrigatorios[i].getElementsByClassName("custom-file-label").length>0){
                  var labelCampo = camposObrigatorios[i].getElementsByClassName("custom-file-label")[0];
                  $(labelCampo).removeClass("sucesso");
                  $(labelCampo).addClass("erro"); 
                }
              }

              if(contErros==0){
                campo.focus();
              }
              contErros++;
              enviar = false;
            }
          }

          if(enviar==true){
            document.getElementById("formjs").submit();
            document.getElementById("formjs").getElementsByClassName("btn-enviar")[0].innerHTML = "Enviando dados...";
            document.getElementById("formjs").getElementsByClassName("btn-enviar")[0].setAttribute("disabled","disabled");
          }
      } 
      
      $(".custom-file-input").change(function(){
        showNameInputFile(this);
      });
      function showNameInputFile(obj){
        var caminhoArquivo = obj.value;
        var nomeArquivo = ""
        if(caminhoArquivo!=""){
          var nomeArquivoMatriz1 = caminhoArquivo.split("\\");
          var nomeArquivoMatriz2 = caminhoArquivo.split("/");
          if(nomeArquivoMatriz1.length>1){
            var nomeArquivo = nomeArquivoMatriz1[nomeArquivoMatriz1.length-1];
          } else if(nomeArquivoMatriz2.length>1){
            var nomeArquivo = nomeArquivoMatriz2[nomeArquivoMatriz2.length-1];
          } else if(nomeArquivoMatriz1.length>0){
            var nomeArquivo = nomeArquivoMatriz1[nomeArquivoMatriz1.length-1];
          } else if(nomeArquivoMatriz2.length>0){
            var nomeArquivo = nomeArquivoMatriz2[nomeArquivoMatriz2.length-1];
          }
        }
        obj.parentNode.getElementsByClassName("custom-file-label")[0].innerHTML = nomeArquivo;
      }
    </script>

    @stack('custom-scripts')
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/inputmask.js') }}"></script>
</body>
</html>