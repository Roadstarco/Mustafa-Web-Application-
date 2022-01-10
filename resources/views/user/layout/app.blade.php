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
                                <a class="btn btn-primary" href="{{url('/provider/register')}}" >Signup As Provider</a>
                                <a class="btn btn-primary" href="{{url('/register')}}" style="margin-top: 10px;">Signup As User</a>
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
                            <li><a href="https://blogs.myroadstar.org/" target="_blank">BLOGS</a></li>
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

            <footer>
                <div class="color-part2"></div>
                <div class="color-part"></div>
                <div class="container-fluid">
                    <div class="row block-content">
                        <div class="col-sm-4 wow zoomIn" data-wow-delay="0.3s" style="text-align: center;">
                          <a href="{{url('/')}}"> <img src="{{asset('proassests/media/footerlogo.png')}}" alt="Footerlogo"></a>
                            <p>The advent of online service platforms and mobile smartphone apps have led to increased fast delivery tasks like the speed of light in real time.</p>
                            <div class="footer-icons">
                                <a href="https://www.facebook.com/Daily-Mcqs" target="_blank"><i class="fa fa-facebook-square fa-2x"></i></a>
                                <a href="instagram.com" target="_blank"><i class="fa fa-instagram fa-2x"></i></a>
                            </div>
                        </div>
                        <div class="col-sm-2 wow zoomIn" data-wow-delay="0.3s" style="text-align: center;">
                            <h4>MAIN LINKS</h4>
                            <nav>
                                <a herf="{{url('/faq')}}">FAQ</a>
                                <a herf="{{url('/aboutus')}}">About Us</a>
                                <a href="{{ url('privacy') }}">Privacy Policy</a>
                                <a herf="{{url('/terms-conditions')}}">Terms & Conditions</a>
                                <a href="{{url('/ride')}}">Signup for Delivery</a>
                                <a href="{{url('/drive')}}">Signup As Company</a>
                                
                         <!--       <a href="{{url('/ride')}}">Deliver Now</a>
                                <a href="{{url('/drive')}}">Company</a>
                                <a href="{{url('/ride')}}">Delivery</a> -->
                            </nav>
                        </div>
                        <div class="col-sm-2 wow zoomIn" data-wow-delay="0.3s" style="text-align: center;">
                            <h4>GET APP ON</h4>
                            <nav>
                               <a href="https://play.google.com/store/apps/details?id=com.suffescomroadstarcustomer" target="_blank"> <img src="{{asset('proassests/media/googlestore.png')}}" style="width: 150px; height: 44px;" alt="slider"></a>

                               <a href="https://apps.apple.com/us/app/roadstar-driver/id1507973452" target="_blank"> <img src="{{asset('proassests/media/Playstore.png')}}" style="width: 150px; height: 44px;" alt="slider"> </a>
                            </nav>
                        </div>
                        <div class="col-sm-4 wow zoomIn" data-wow-delay="0.3s" style="text-align: center;">
                            <h4>CONTACT INFO</h4>
                            Everyday is a new day for us and we work really hard to satisfy our customers everywhere.
                            <div class="contact-info" style="text-align: center;">
                                <span><i class="fa fa-location-arrow" style= "float:none;"><strong style="font-weight: 700;
    font-size: 12px;">
                                RoadStar Corporation. <br> </strong></i>13010 Morris Road, Ste. 648. Alpharetta, GA 30004</span>
                                <span><i class="fa fa-phone" style="float:none !important; "<a href="tel:+1 8006 727316"><font color="white">+1 8006 727316</font></a></i></span>
                                <span><i class="fa fa-envelope" style="float:none !important;"><a href="mailto:Support@myroadstar.org"><font color="white">Support@myroadstar.org</font></a>   |  <a href="mailto:Admin@myroadstar.org"> <font color="white">Admin@myroadstar.org</font></a></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="copy text-right"><a id="to-top" href="#this-is-top"><i class="fa fa-chevron-up"></i></a> &copy; 2021 MyRoadStar All rights reserved.</div>
                 </div>
            </footer>
</div>



<!-- <script src="{{asset('asset/js/jquery.min.js')}}"></script>
<script src="{{asset('asset/js/bootstrap.min.js')}}"></script>
<script src="{{asset('asset/js/scripts.js')}}"></script> -->
<!--Main-->   
        <script src="{{asset('proassests/js/jquery-1.11.3.min.js')}}"></script>
        <script src="{{asset('proassests/js/jquery-ui.min.js')}}"></script>
        <script src="{{asset('proassests/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('proassests/js/modernizr.custom.js')}}"></script>
        
        <script src="{{asset('proassests/assets/rendro-easy-pie-chart/dist/jquery.easypiechart.min.js')}}"></script>
        <script src="{{asset('proassests/js/waypoints.min.js')}}"></script>
        <script src="{{asset('proassests/js/jquery.easypiechart.min.js')}}"></script>
        <!-- Loader -->
        <script src="{{asset('proassests/assets/loader/js/classie.js')}}"></script>
        <script src="{{asset('proassests/assets/loader/js/pathLoader.js')}}"></script>
        <script src="{{asset('proassests/assets/loader/js/main.js')}}"></script>
        <script src="{{asset('proassests/js/classie.js')}}"></script>
        <!--Switcher-->
        <script src="{{asset('proassests/assets/switcher/js/switcher.js')}}"></script>
        <!--Owl Carousel-->
        <script src="{{asset('proassests/assets/owl-carousel/owl.carousel.min.js')}}"></script>
        <!-- SCRIPTS -->
        <script type="text/javascript" src="{{asset('proassests/assets/isotope/jquery.isotope.min.js')}}"></script>
        <!--Theme-->
        <script src="{{asset('proassests/js/jquery.smooth-scroll.js')}}"></script>
        <script src="{{asset('proassests/js/wow.min.js')}}"></script>
        <script src="{{asset('proassests/js/jquery.placeholder.min.js')}}"></script>
        <script src="{{asset('proassests/js/smoothscroll.min.js')}}"></script>
        <script src="{{asset('proassests/js/theme.js')}}"></script>
        @stack('scripts')




@if(Setting::get('demo_mode', 0) == 1)
    <!-- Start of LiveChat (www.livechatinc.com) code -->
    <script type="text/javascript">
        window.__lc = window.__lc || {};
        window.__lc.license = 8256261;
        (function() {
            var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
            lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
        })();
    </script>
    <!-- End of LiveChat code -->
@endif
</body>
</html>
