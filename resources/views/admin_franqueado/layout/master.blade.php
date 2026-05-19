<!DOCTYPE html>
<html>

<head>
  <title>Painel Franqueado</title>
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

  @stack('style')
  <style>
    #page-loading-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.45); z-index: 99999;
        flex-direction: column; align-items: center; justify-content: center;
        color: #fff; font-size: 16px;
    }
    #page-loading-overlay.active { display: flex; }
    #page-loading-spinner {
        width: 48px; height: 48px;
        border: 5px solid rgba(255,255,255,.3); border-top-color: #fff;
        border-radius: 50%; animation: pls 0.8s linear infinite; margin-bottom: 14px;
    }
    @keyframes pls { to { transform: rotate(360deg); } }
  </style>
</head>

<body data-base-url="{{url('/')}}" class="sidebar-dark">

  <div id="page-loading-overlay">
    <div id="page-loading-spinner"></div>
    <span>Carregando...</span>
  </div>

  <script src="{{ asset('assets/js/spinner.js') }}"></script>

  <div class="main-wrapper" id="app">
    @include('admin_franqueado.layout.sidebar')
    <div class="page-wrapper">
      @include('admin_franqueado.layout.header')
      <div class="page-content">
        @yield('content')
      </div>
      @include('admin_franqueado.layout.footer')
    </div>
  </div>

  <!-- base js -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
  <!-- end base js -->

  <!-- plugin js -->
  @stack('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}">
  <script>
  function mailsLinkDo() {
    var mails_links = $(".mail-link");
    for (var i = 0; i < mails_links.length; i++) {
      var email = mails_links[i].innerHTML;
      mails_links[i].innerHTML = "<a href='mailto:" + email + "'>" + email + "</a>";
    }
  }

  function whatsLinkDo() {
    var whats_links = $(".whats-link");
    for (var i = 0; i < whats_links.length; i++) {
      var fone = whats_links[i].innerHTML;
      var foneLink = whats_links[i].innerHTML.replace(" ", "").replace("(", "").replace(")", "").replace("-", "");
      whats_links[i].innerHTML = "<a target='_blank' href='https://api.whatsapp.com/send?phone=55" + foneLink + "'>" +
        fone + "</a>";
    }
  }

  //$(document).ready(function() {
  mailsLinkDo();
  whatsLinkDo();

  dataTable = $('.dataTable').DataTable({
    "order": [
      [0, 'asc']
    ],
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
    "order": [
      [0, 'asc']
    ],
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
    "order": [
      [0, 'desc']
    ],
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
    "ordering": false,
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

  dataTableNoOrderNoPage = $('.dataTableNoOrderNoPage').DataTable({
    "ordering": false,
    "paging": false,
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

  //});

  $(".custom-file-input").change(function() {
    showNameInputFile(this);
  });

  function showNameInputFile(obj) {
    var caminhoArquivo = obj.value;
    var nomeArquivo = ""
    if (caminhoArquivo != "") {
      var nomeArquivoMatriz1 = caminhoArquivo.split("\\");
      var nomeArquivoMatriz2 = caminhoArquivo.split("/");
      if (nomeArquivoMatriz1.length > 1) {
        var nomeArquivo = nomeArquivoMatriz1[nomeArquivoMatriz1.length - 1];
      } else if (nomeArquivoMatriz2.length > 1) {
        var nomeArquivo = nomeArquivoMatriz2[nomeArquivoMatriz2.length - 1];
      } else if (nomeArquivoMatriz1.length > 0) {
        var nomeArquivo = nomeArquivoMatriz1[nomeArquivoMatriz1.length - 1];
      } else if (nomeArquivoMatriz2.length > 0) {
        var nomeArquivo = nomeArquivoMatriz2[nomeArquivoMatriz2.length - 1];
      }
    }
    obj.parentNode.getElementsByClassName("custom-file-label")[0].innerHTML = nomeArquivo;
  }
  </script>
  <!-- end plugin js -->

  <!-- common js -->
  <script src="{{ asset('assets/js/template.js') }}"></script>
  <!-- end common js -->

  @stack('custom-scripts')
  <script>
    (function() {
      var overlay = document.getElementById('page-loading-overlay');
      function showOverlay() { overlay.classList.add('active'); }
      $(document).on('click', 'a[href]', function() {
        var href = $(this).attr('href');
        if (!href || href.startsWith('javascript') || href.startsWith('#') || href === '') return;
        if ($(this).attr('target') === '_blank') return;
        showOverlay();
      });
      $(document).on('submit', 'form', function() { showOverlay(); });
      $(window).on('pageshow', function(e) {
        if (e.originalEvent.persisted) overlay.classList.remove('active');
      });
    })();
  </script>
</body>

</html>