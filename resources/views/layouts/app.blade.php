<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_NAME', 'Versus ERP') }} - {{env('APP_DESCRIPTION')}} </title>

    <!-- Styles -->
    <!-- Bootstrap core CSS -->
    
    <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/fonts/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/animate.min.css') }}" rel="stylesheet">

     <!-- SELECT with Combobox -->
    <link rel="stylesheet" href="{{ URL::asset('/js/jquery-tree-master/css/jquery.tree.css') }}" type="text/css" media="screen"/>
    
    <!-- Custom styling plus plugins -->
    <link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/maps/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/css/icheck/flat/green.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('/css/floatexamples.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/css/select/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <!--jQuery and jQuery UI-->
    <link href="{{ URL::asset('/css/jquery-ui.min.css') }}" rel="stylesheet" >
    <link href="{{ URL::asset('/css/jquery-ui.theme.min.css') }}" rel="stylesheet" >

    <link rel="stylesheet" href="{{ URL::asset('css/ion.rangeSlider.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('css/ion.rangeSlider.skinFlat.css') }}" />

    <link rel="stylesheet" href="{{ URL::asset('css/daterangepicker.css') }}" />

    <!-- Alguns Script são necessários carregar aqui acima -->
    <script src="{{ URL::asset('js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('js/nprogress.js') }}"></script>
    <!-- select seach -->
    <script src="{{ URL::asset('js/select/select2.full.js') }}"></script> 
    <!-- function system -->
    <script src="{{ URL::asset('js/function.js') }}"></script>
    
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

    <div class="container body">

    <div class="main_container">

        <div class="col-md-3 left_col">
        
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="{{ url('/') }}" class="site_title"><img src="{{ URL::asset('images/icon.png') }}" height="32" /> <span>{{env('APP_NAME')}}</span></a>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                      <img src="{{ URL::asset('images/img.jpg') }}" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                      <span>Bem-vindo,</span>
                      @if (!Auth::guest())
                        <h2>{{  Auth::user()->name }}</h2>
                      @endif
                    </div>
                </div>
                <!-- /menu prile quick info -->
                <div class="clearfix"></div>
                <br />
                
                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    @include('layouts/menu')

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ url('/logout') }}">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->

            </div>

        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    
                    <div class="nav toggle">
                      <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                              <img src="{{ URL::asset('images/img.jpg') }}" alt="">
                              @if (!Auth::guest()) {{ Auth::user()->name }} @endif
                              <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li>
                                    <a href="javascript:;">  Profile</a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-red pull-right">50%</span>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">Help</a>
                                </li>
                                <li>
                                    {!! Form::open(['method' => 'POST', 'id'=>'logout_form', 'route' => 'auth.logout']) !!}
                                    {!! Form::close() !!}
                                    <a href="#" onclick="$('#logout_form').submit()"><i class="fa fa-sign-out pull-right"></i> Sair</a>
                                </li>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown">
                        
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                              <i class="fa fa-envelope-o"></i>
                              <span class="badge bg-green">6</span>
                            </a>

                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                
                                <li>
                                    <a>
                                        <span class="image">
                                            <img src="{{ URL::asset('images/img.jpg') }}" alt="Profile Image" />
                                        </span>
                                        <span>
                                            <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                        </span>
                                        <span class="message">
                                            Film festivals used to be do-or-die moments for movie makers. They were where...
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a>
                                        <span class="image">
                                            <img src="{{ URL::asset('images/img.jpg') }}" alt="Profile Image" />
                                        </span>
                                        <span>
                                            <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                        </span>
                                        <span class="message">
                                            Film festivals used to be do-or-die moments for movie makers. They were where...
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a>
                                        <span class="image">
                                            <img src="{{ URL::asset('images/img.jpg') }}" alt="Profile Image" />
                                        </span>
                                        <span>
                                            <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                        </span>
                                        <span class="message">
                                            Film festivals used to be do-or-die moments for movie makers. They were where...
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a>
                                        <span class="image">
                                            <img src="{{ URL::asset('images/img.jpg') }}" alt="Profile Image" />
                                        </span>
                                        <span>
                                            <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                        </span>
                                        <span class="message">
                                            Film festivals used to be do-or-die moments for movie makers. They were where...
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <div class="text-center">
                                        <a href="inbox.html">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>

                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->


        <!-- page content -->
        <div class="right_col" role="main">

            @yield('content')

        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
              {{ env('APP_NAME', 'Versus ERP') }} - {{env('APP_DESCRIPTION')}}
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->

    </div>
    
    </div>
    

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>

    <script src="{{ URL::asset('js/jquery-ui.min.js') }}" type="text/javascript" ></script>

    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        var bootstrapButton = $.fn.button.noConflict() // return $.fn.button to previously assigned value
        $.fn.bootstrapBtn = bootstrapButton            // give $().bootstrapBtn the Bootstrap functionality
    </script>

    <!-- gauge js 
    <script src="{{ URL::asset('js/gauge/gauge.min.js') }}" type="text/javascript" ></script>
    <script src="{{ URL::asset('js/gauge/gauge_demo.js') }}" type="text/javascript"></script>-->
    <!-- bootstrap progress js -->
    <script src="{{ URL::asset('js/progressbar/bootstrap-progressbar.min.js') }}"></script>
    <!-- icheck -->
    <script src="{{ URL::asset('js/icheck/icheck.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ URL::asset('js/moment/moment.min.js') }}" type="text/javascript" ></script>
    <script src="{{ URL::asset('js/datepicker/daterangepicker.js') }}" type="text/javascript" ></script>
    <!-- chart js -->
    <script src="{{ URL::asset('js/chartjs/chart.min.js') }}"></script>
    <!-- input mask -->
    <script src="{{ URL::asset('js/jquery-mask-plugin/dist/jquery.mask.min.js') }}" type="text/javascript"></script>
    <!-- account plugin -->
    <script src="{{ URL::asset('js/accounting.min.js') }}" type="text/javascript"></script>
    <!-- form validation -->
    <script src="{{ URL::asset('js/validator/validator.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/jquery-tree-master/js/jquery.tree.js') }}"></script>

    <script src="{{ URL::asset('js/custom.js') }}"></script>

    <!-- flot js -->
    <!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
    <script type="text/javascript" src="{{ URL::asset('js/flot/jquery.flot.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/flot/jquery.flot.pie.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/flot/jquery.flot.orderBars.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/flot/jquery.flot.time.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/flot/date.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/flot/jquery.flot.spline.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/flot/jquery.flot.stack.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/flot/curvedLines.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/flot/jquery.flot.resize.js') }}"></script>

    <!-- pace -->
    <script src="{{ URL::asset('js/pace/pace.min.js') }}"></script>
    <!-- range slider -->
    <script src="{{ URL::asset('js/ion_range/ion.rangeSlider.min.js') }}"></script>
    
    <script>
    NProgress.done();
    </script>
    <!-- /datepicker -->
    <!-- /footer content -->

</body>
</html>
