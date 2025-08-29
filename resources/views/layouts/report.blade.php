<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_NAME', 'Versus ERP') }} - {{$title}} </title>

    <!-- Styles -->
    <!-- Bootstrap core CSS 

    <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('fonts/css/font-awesome.min.css') }}" rel="stylesheet">
    
    <!-- Custom styling plus plugins --
    <link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet"> -->
    <link href="{{ URL::asset('css/report.css') }}" rel="stylesheet">

    <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>
    
<body class="nav-md">

	<!-- page content -->
    <div class="invoice" role="main">

        @yield('content')

    </div>
    <!-- /page content -->

</body>
</html>