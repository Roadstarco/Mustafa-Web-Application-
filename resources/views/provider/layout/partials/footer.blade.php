<!--<div class="footer row no-margin">
    <div class="container">
        <div class="row no-margin">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <ul>
                    <li><a href="#">Ride</a></li>
                    <li><a href="#">Drive</a></li>
                </ul>
            </div> 
            <div class="col-md-3 col-sm-3 col-xs-12">
                <ul>
                    <li><a href="#">Signup to Ride</a></li>
                    <li><a href="#">Become a Driver</a></li>
                    <li><a href="#">Ride Now</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12">
                <h5>Get App on</h5>
                <ul class="app">
                    <li><a href="{{Setting::get('store_link_ios','#')}}"><img src="{{ asset('asset/img/appstore.png') }}"></a></li>
                    <li><a href="{{Setting::get('store_link_android','#')}}"><img src="{{ asset('asset/img/playstore.png') }}"></a></li>
                </ul>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12">
                <h5>Connect us</h5>
                <ul class="social">
                    <li><a href="{{Setting::get('facebook_page_link','#')}}"><i class="fa fa-facebook"></i></a></li>
                     <li><a href="{{Setting::get('instagram_page_link','#')}}"><i class="fa fa-instagram"></i></a></li>
                    
                </ul>
            </div>
        </div>

        <div class="row no-margin">
            <div class="col-md-12 copy">
                <p>{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p>
            </div>
        </div>
    </div>
</div> -->
<!--
        <div class="page-content" >
            <div class="footer row no-margin" style="background-color: #a29999 !important">
                <div class="container">
                    <div class="footer-logo row no-margin">
                        <div class="logo-img">
                           <a href="{{url('/')}}"> <img src="{{Setting::get('site_logo',asset('asset/img/logo-white.png'))}}"> </a>
                        </div>
                    </div>
                    <div class="row no-margin" >
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <ul>
                                <li><a href="{{url('ride')}}">Delivery</a></li>
                                <li><a href="{{url('drive')}}">Company</a></li>

                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <ul>
                                <li><a href="{{url('ride')}}">Signup for Delivery</a></li>
                                <li><a href="{{url('drive')}}">Signup As Company </a></li>
                                <li><a href="{{url('ride')}}">Deliver Now</a></li>
                            </ul>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <h5>Get App on</h5>
                            <ul class="app">
                                <li>
                                    <a href="{{Setting::get('store_link_ios','#')}}">
                                        <img src="{{asset('asset/img/appstore.png')}}">
                                    </a>
                                </li>
                                <li>
                                    <a href="{{Setting::get('store_link_android','#')}}">
                                        <img src="{{asset('asset/img/playstore.png')}}">
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12" style="color: #ffff;" >
                                <h5>Connect us</h5>
                                RoadStar Corporation.<br>
                                <i class="fa fa-map-marker" style="color: #ffff;"></i>
                                Address: 13010 Morris Road,
                                Ste. 648. Alpharetta, GA 30004<br>
                                <i class="fa fa-phone" style="color: #ffff;"></i>
                                Contact No: +1 8006 727316 <br>
                                <i class="fa fa-envelope-o" style="color: #ffff;"></i>
                                Email: Support@myroadstar.org
                                <i class="fa fa-envelope-o" style="color: #ffff;"></i>
                                Email: Admin@myroadstar.org                        
                                <span style="color: #ffff;"> </span>
                            <ul class="social">
                                <li><a href="{{Setting::get('facebook_page_link','#')}}"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="{{Setting::get('instagram_page_link','#')}}"><i class="fa fa-instagram"></i></a></li>
                            </ul>
                            
                        </div>
                    </div>

                    <div class="row no-margin">
                        <div class="col-md-12 copy">
                            <p>{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        -->
        <style>
        footer{
	position:relative;
	border-bottom:10px solid;
    background: #333;
}
footer a{
	font-weight:500;
	font-size:12px;
}
footer nav a{
	display:block;
	margin-bottom:20px;
}
footer nav a:hover{
	text-decoration:none;
}
footer h4{
	margin-bottom:40px;
}
footer .block-content{
	background:url(../img/footer-bg.png) no-repeat center center;
	background-size:cover;
}
footer .color-part{
	position:absolute;
	top:0px;
	bottom:0px;
	left:0px;
	bottom:0px;
	width:50%;
	margin-left:-20%;
    -webkit-transform: skew(-20deg);
   	-moz-transform: skew(-20deg);
	-o-transform: skew(-20deg);
}
.logo-footer{
	display:block;
	background:url(../img/logo-footer.png) no-repeat;
	width:244px;
	height:42px;
}
footer p{
	font-size:13px;
	margin-top:30px;
	padding-right:60px;
	line-height:20px;
}
.footer-icons{
	padding:20px 0 30px 0;
}
.footer-icons a{
	display:inline-block;
	padding-right:5px;
}
.contact-info{
	margin-top:30px;
}
.contact-info span{
	display:block;
	margin-bottom:10px;
}
.contact-info strong{
	font-weight:700;
	font-size:12px;
}
.contact-info span:after{
	content:"";
	display:block;
	width:100%;
	clear:both;
}
.contact-info i{
	display:block;
	float:left;
	margin-right:10px;
	margin-bottom:10px;
}

        </style>
        <link rel="stylesheet" href="https://myroadstar.org/public/proassests/css/theme.css">

        <footer >
                <div class="color-part2"></div>
                <div class="color-part" style="
    background-color: #EB3D26;
"></div>
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
                                <a herf="https://myroadstar.org/provider/profile">Profile</a>
                                <a herf="https://myroadstar.org/provider/earnings">Earnings</a>
                                <a href="{{ route('provider.profile.local_trips') }}">Local Trips</a>
                                <a herf="https://myroadstar.org/provider/international-trips">International Trips</a>
                                
                                
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
                            <p style="color:white;">Everyday is a new day for us and we work really hard to satisfy our customers everywhere.</p>
                            <div class="contact-info" style="text-align: center;">
                                <span><i class="fa fa-location-arrow" style= "float:none;"><strong style="font-weight: 700;
    font-size: 12px;">
                                RoadStar Corporation. <br> </strong></i><p style="color:white;margin-top:0px;">13010 Morris Road, Ste. 648. Alpharetta, GA 30004</p></span>
                                
                            </div>
                        </div>
                    </div>
                    <div class="copy text-right"><a id="to-top" href="#this-is-top"><i class="fa fa-chevron-up"></i></a> &copy; 2021 MyRoadStar All rights reserved.</div>
                 </div>
            </footer>