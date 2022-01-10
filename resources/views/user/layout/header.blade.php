<!DOCTYPE html>
<html lang="en">
<head><style>

        :root {
            --main-bg-color: {{ Setting::get('site_color','#37b38b') }};
            --hover-color: {{ Setting::get('site_hover_color','#278064') }};
        }
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    
    <meta name="author" content="My Road Star">
    <link rel="shortcut icon" type="image/png" href="{{asset('proassests/media/icon.png')}}"/>


 <!--    <link href="{{asset('asset/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/css/style.css')}}" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link href="{{asset('proassests/css/master.css')}}" rel="stylesheet">
     
        <!-- SWITCHER -->
        <link rel="stylesheet" id="switcher-css" type="text/css" href="{{asset('proassests/assets/switcher/css/switcher.css')}}" media="all" />
        <link rel="alternate stylesheet" type="text/css" href="{{asset('proassests/assets/switcher/css/color1.css')}}" title="color1" media="all" data-default-color="true" />
        <link rel="alternate stylesheet" type="text/css" href="{{asset('proassests/assets/switcher/css/color2.css')}}" title="color2" media="all" />
        <link rel="alternate stylesheet" type="text/css" href="{{asset('proassests/assets/switcher/css/color3.css')}}" title="color3" media="all" />
        <link rel="alternate stylesheet" type="text/css" href="{{asset('proassests/assets/switcher/css/color4.css')}}" title="color4" media="all" />
        <link rel="alternate stylesheet" type="text/css" href="{{asset('proassests/assets/switcher/css/color5.css')}}" title="color5" media="all" />
        <link rel="alternate stylesheet" type="text/css" href="{{asset('proassests/assets/switcher/css/color6.css')}}" title="color6" media="all" />
        {{-- <link href="{{asset('asset/css/style.css')}}" rel="stylesheet"> --}}

         <!--[if lt IE 9]>
          <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>
<body>

<div class="sp-body">
            <!-- Loader Landing Page -->
            <div id="ip-container" class="ip-container">
                <div class="ip-header" >
                    <div class="ip-loader">
                        <svg class="ip-inner" width="60px" height="60px" viewBox="0 0 80 80">
                            <path class="ip-loader-circlebg" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,39.3,10z"/>
                            <path id="ip-loader-circle" class="ip-loader-circle" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z"/>
                        </svg> 
                    </div>
                </div>
            </div>
            <!-- Loader end -->

             <div class="alert alert-danger" style="margin-top: 0px; margin-bottom: 0px !important;">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Coronavirus (COVID-19) Resources & Updates</strong>
            <p style="margin-top: 10px">The health and safety of the My Road Star community is always our priority. We are actively monitoring the coronavirus (COVID‐19) situation and are taking steps to help keep those that rely on our platform safe.</p>
        </div>

            <header id="this-is-top">
                <div class="container-fluid">
                    <div class="topmenu row">
                        <nav class="col-sm-offset-3 col-md-offset-4 col-lg-offset-4 col-sm-6 col-md-5 col-lg-5">
                            <a href="{{ url('aboutus') }}">ABOUT US</a>
                            <a href="{{ url('faq') }}">FAQs</a>
                            <a href="{{ url('privacy') }}">Privacy Policy</a>.  
                            <a href="{{ url('terms-conditions') }}">Terms and Conditions</a>
                        </nav>
                        <nav class="text-right col-sm-3 col-md-3 col-lg-3">
                            <a href="https://www.facebook.com/Daily-Mcqs" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="instagram.com" target="_blank"><i class="fa fa-instagram"></i></a>
                        </nav>
                    </div>
                    <div class="row header">
                   <div class="mobmargin">     
                    <div class="col-sm-3 col-md-3 col-lg-3">
                <a href="{{url('/')}}"> <img src="{{asset('proassests/media/logo.png')}}" alt="logo"></a>
                     <!--        <a href="01_home.html" ></a> -->
                        </div>
                    </div>
                        <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-8 col-md-8 col-lg-8">
                            <div class="text-right header-padding">
                                <div class="h-block"><span>CALL US</span><a href="tel:+1 8006 727316"><font color="black">+1 8006 727316</font></a></font></div>
                                <div class="h-block"><span>EMAIL US</span><a href="mailto:Support@myroadstar.org"><font color="black">Support@myroadstar.org</font></a></div>
                                <div class="h-block"><span>ADDRESS</span>13010 Morris Road, Ste. 648. Alpharetta, GA 30004</div>
                                <a class="btn btn-primary" href="{{url('/drive')}}">Signup As Company</a>
                            </div>
                        </div>
                    </div>
                    <div id="main-menu-bg"></div>  
                    <a id="menu-open" href="#"><i class="fa fa-bars"></i></a> 
                    <nav class=" main-menu navbar-main-slide " style="max-height: fit-content;">
                        <ul class="nav navbar-nav navbar-main">
                            <li class="dropdown">
                                <a href="{{ url('/ride') }}">DELIVERY <!-- <i class="fa fa-angle-down"></i> --></a>
<!--                                <ul class="dropdown-menu">
                                    <li><a href="01_home.html">HOME 1</a></li>
                                    <li><a href="02_home.html">HOME 2</a></li>
                                </ul> -->
                            </li>
                            <li class="dropdown">
                                <a href="{{ url('/drive') }}">COMPANY <!-- <i class="fa fa-angle-down"></i> --></a>
                                <!-- <ul class="dropdown-menu">
                                    <li><a href="05_services.html">OUR SERVICES 1</a></li>
                                    <li><a href="06_services.html">OUR SERVICES 2</a></li>
                                    <li><a href="07_services.html">OUR SERVICES 3</a></li>
                                </ul> -->
                            </li>
                            <li class="dropdown">
                               <a href="{{url('/service')}}">SERVICES</a>
                            <!--    <ul class="dropdown-menu">
                                    <li><a href="03_about.html">ABOUT US 1</a></li>
                                    <li><a href="04_about.html">ABOUT US 2</a></li>
                                </ul> -->
                            </li>
                            <li class="dropdown">
                                <a href="{{url('/trips')}}">TRIPS <!-- <i class="fa fa-angle-down"></i> --></a>
                            <!--    <ul class="dropdown-menu">
                                    <li><a href="09_blog.html">Blog 1</a></li>
                                    <li><a href="10_blog.html">Blog 2</a></li>
                                </ul> -->
                            </li>
                            <li><a href="{{url('/post-trip')}}">POST YOUR TRIP</a></li>
                            <li><a href="https://www.myroadstar.org/blogs" target="_blank">BLOGS</a></li>
                            <!-- <li><a href="12_contact.html">CONTACT</a></li>
                            <li><a class="btn_header_search" href="#"><i class="fa fa-search"></i></a></li> -->
                        </ul>
                        <div class="search-form-modal transition">
                            <form class="navbar-form header_search_form">
                                <i class="fa fa-times search-form_close"></i>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Search">
                                </div>
                                <button type="submit" class="btn btn_search customBgColor">Search</button>
                            </form>
                        </div>
                    </nav>
                    <a id="menu-close" href="#"><i class="fa fa-times"></i></a>
                </div>
            </header>

@yield('content')

            