@extends('user.layout.app')

@section('title', 'Company')
@section('description', 'As a driver, accept delivery requests when you want to, set your own schedule. And when you decide to take riders, our turn-by-turn directions will guide you')

@section('content')

<div class="bg-image page-title">
				<div class="container-fluid">
					<a href="#"><h1>COMPANY</h1></a>
					<div class="pull-right">
						<a href="{{url('/')}}"><i class="fa fa-home fa-lg"></i></a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="{{url('/drive')}}">COMPANY</a>
					</div>
				</div>
			</div>

			<div class="container-fluid block-content">
                <div class="text-center hgroup wow zoomInUp" data-wow-delay="0.3s">
                    <h1>REGISTRATION</h1>
                    <h2>MYROADSTAR</h2>
                </div>
                <div class="row our-services">
                	  <div class="col-sm-3 col-md-2 col-lg-2 wow zoomInLeft" data-wow-delay="0.3s">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 wow zoomInLeft" data-wow-delay="0.3s">
                        <a href="{{url('/fleet/login')}}">
                            <span><i class="fa fa-user" style="font-size:28px;"></i></span>
                            <h4>Sign in As Company</h4>
                            <button type="button" class="btn btn-outline-secondary">SIGN IN</button>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 wow zoomInRight" data-wow-delay="0.3s">
                        <a href="{{url('/fleet/register')}}">
                            <span><i class="fa fa-user-plus" style="font-size:28px;"></i></span>
                            <h4>Sign up As Company</h4>
                           <button type="button" class="btn btn-outline-secondary">SIGN UP</button>
                        </a>
                    </div>
                </div>
                <hr>
            </div>

<!--<div id="working" style="padding-bottom:50px;" class="container">
	<div class="row"> -->
        <!-- Boxes de Acoes -->
<!--    	<div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><i class="fa fa-calendar-check-o"></i></div>
					<div class="info">
						<h3 class="title">Set your own schedule</h3>
						<p>
							With My Road Star, anyone can be a partner at any time! You can even choose flexible hours to work, so you never lose sight of what matters!
						</p>
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>
			
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><i class="fa fa-usd"></i></div>
					<div class="info">
						<h3 class="title">Make more at every turn</h3>
    					<p>
							Trip fares start with a basic amount, then gradually increase with time and distance. The higher the demand, the more money you make!
						</p>
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>
			
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><i class="fa fa-mobile"></i></div>
					<div class="info">
						<h3 class="title">Let the app lead the way</h3>
    					<p>
							Just tap and go. You’ll get turn-by-turn directions, tools to help you make more and 24/7 support—everything you need is right there in the app!
						</p>
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>		    -->
		<!-- /Boxes de Acoes -->
<!--	</div>
</div> -->

<section>
	<div class="container hgroup text-center wow zoomIn" data-wow-delay="0.3s">
					<h2>About the app</h2>
					<h1>We deliver anywhere in the world.</h1>
				</div>  
</section>

<!-- Jumbotron -->
<div
  class="bgcompany p-5 text-center shadow-1-strong rounded mb-5 text-white"
  style="background-image: url('https://cdn.motor1.com/images/mgl/x1Alk/s1/car-phone-mount.jpg'); padding-top: 100px; padding-bottom:100px;">
  <h1 style="color: white" class="mb-3 h2">DESIGNED JUST FOR OUR PARTNERS</h1>
<!--   <p style="font-size:25px; color:#FFD700;"><i>Feel like making money?</i></p> -->

  <div class="container" style="padding-left:50px; padding-right:50px;"><p style="color: white; font-size:15px;">
    Feel like making money? Just hit the app to be actively receiving delivery requests. You'll immediately be provided with the delivery information i.e. location and destination along with the directions and customer details. Once you deliver the package, a new request will pop up. Just sign off when you're done for the day!  
  </p></div>
   <div align="center" style="padding-top:20px;">
                <a href="#working"> <button type="button" class="btn btn-outline-secondary">SEE HOW IT WORKS</button></a>
                    </div>
</div>
<div id="working" style="padding-bottom:50px;" class="container">
	<div class="row"> 
        <!-- Boxes de Acoes -->
    	<div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><i class="fa fa-calendar-check-o"></i></div>
					<div class="info">
						<h3 class="title">Set your own schedule</h3>
						<p>
							With My Road Star, anyone can be a partner at any time! You can even choose flexible hours to work, so you never lose sight of what matters!
						</p>
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>
			
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><i class="fa fa-usd"></i></div>
					<div class="info">
						<h3 class="title">Make more at every turn</h3>
    					<p>
							Trip fares start with a basic amount, then gradually increase with time and distance. The higher the demand, the more money you make!
						</p>
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>
			
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><i class="fa fa-mobile"></i></div>
					<div class="info">
						<h3 class="title">Let the app lead the way</h3>
    					<p>
							Just tap and go. You’ll get turn-by-turn directions, tools to help you make more and 24/7 support—everything you need is right there in the app!
						</p>
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>		    
		<!-- /Boxes de Acoes -->
	</div>
</div> 
<!-- Jumbotron -->

<div style="padding-top:50px; padding-bottom:50px;" class="container">
	<div class="row">
        <!-- Boxes de Acoes -->
    	<div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><i class="fa fa-handshake-o"></i></div>
					<div class="info">
						<h3 class="title">Become a Partner</h3>
						<p>
							Many established businesses, stores, Contractors, government firms, etc. Can leverage the flexible options available, All you need to do is partner with us and access a wide community of delivery providers.
						</p>
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>
			
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><i class="fa fa-trophy"></i></div>
					<div class="info">
						<h3 class="title">Rewards</h3>
    					<p>
							You’re in the driver’s seat. So reward yourself with discounts on fuel, vehicle maintenance, cell phone bills, and more. Reduce your daily expenses and take home extra cash.
						</p>
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>
			
        <div class="col-xs-12 col-sm-6 col-lg-4">
			<div class="box">							
				<div class="icon">
					<div class="image"><i class="fa fa-road"></i></div>
					<div class="info">
						<h3 class="title">Requirements</h3>
    					<p>
							Now you’re ready to hit the road. Whether you’re driving your own car or a commercially-licensed vehicle, you must meet the minimum requirements and complete a safety screening online.

						</p>
					</div>
				</div>
				<div class="space"></div>
			</div> 
		</div>		    
		<!-- /Boxes de Acoes -->
	</div>
</div>

<div class="block-content bg-image blog-section inner-offset">
                <div class="container-fluid">
                    <div class="hgroup wow fadeInLeft" data-wow-delay="0.3s">
                        <h1 align="center">Start making money</h1>
                        <h2 align="center">Ready to make money? The first step is to sign up online.</h2>  
                      <div align="center" style="padding-top:20px;">
                        <button type="button" class="btn btn-outline-secondary">BECOME A PARTNER NOW</button>
                    </div>
                         </div>

                </div>
            </div>
@endsection