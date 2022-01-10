@extends('user.layout.app')

@section('title', 'About us')
@section('description', 'My Road Star provides delivery services that are affordable, reliable, cost efficient and quick. A user-friendly app allowing transportation services all around the world')

@section('content')

<div class="bg-image page-title">
				<div class="container-fluid">
					<a href="#"><h1>ABOUT US</h1></a>
					<div class="pull-right">
						<a href="{{url('/')}}"><i class="fa fa-home fa-lg"></i></a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="{{url('/aboutus')}}">About Us</a>
					</div>
				</div>
			</div>

			<div class="container-fluid inner-offset">
				<div class="hgroup text-center wow zoomIn" data-wow-delay="0.3s">
					<h2>FOR ALL YOUR NEEDS</h2>
					<h1>MYROADSTAR - THE RIGHT CHOICE</h1>
				</div>            
<!--				<ul class="nav nav-tabs wow fadeIn" data-wow-delay="0.3s" id="myTab">
					<li class="active"><a href="#tab1" data-toggle="tab">WHY CHOOSE US?</a></li>
					<li><a href="#tab2" data-toggle="tab">THE MISSION</a></li>
					<li><a href="#tab3" data-toggle="tab">SERVICES</a></li>
					<li><a href="#tab4" data-toggle="tab">The My Road Star Experience</a></li>
					<li><a href="#tab5" data-toggle="tab">A Safer Community</a></li>
				</ul>
-->

	<!--			<div class="tab-content inner-offset wow fadeIn" data-wow-delay="0.3s">.   -->
					<div class="tab-pane active" id="tab1" style="margin-bottom: 50px;">
						<div class="row">
							<div class="col-sm-5">
								<img class="full-width" src="{{asset('proassests/media/whychooseus.png')}}" alt="Img">
							</div>
							<div class="col-sm-7">
								<p><strong style="font-size:23px;">What is Road Star?</strong></p>
								<p>The advent of online service platforms and mobile smartphone applications has led to an increase in fast delivery tasks like somehow achieving the speed of light in real time.</p>

								<p>In this case, shippers send requests on the Roadstar App and drivers accept the request to make delivery along their usual route. 
								Another example would be a driver who can easily pick up parcels from a retail store, a warehouse or any dedicated pickup location, and deliver them to customer locations on their way home or on their way to work, without altering their normal route. This means that our application also provides Route Efficiency.</p>

								<p>We do not only focus on shipping, but we also offer delivery services to international routes, not only by road, but also by flights and ocean delivery services, through our Roadstar groundbreaking Crowd shipping platform.</p>

								<p>The platform is unique because it allows everyone to unleash the power of crowd participation in local and international package delivery. By doing so it offers multiple opportunities to platform users by increasing efficiency, saving costs, and retaining revenue for businesses.</p>

								<p>Simply put this package delivery on steroids!</p>

								<p>At its core, the crowdsourced delivery resolves the pickup and delivery problem, that aims to transport goods from origins to destinations at minimum costs.</p>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab2" style="margin-bottom: 50px;">
						<div class="row">
							<div class="col-sm-5">
								<img class="full-width" src="{{asset('proassests/media/target.jpg')}}" alt="Img">
							</div>
							<div class="col-sm-7 text-block">
								<p><strong style="font-size:23px;">Mission</strong></p>
								<p>To provide transportation services to the public that is affordable, safe and consistent.We aim to be the solution and choice for consumers in an industry that is lacking innovation and often plagued by high prices that puts the customer at a disadvantage.</p>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab3" style="margin-bottom: 50px;">
						<div class="row">
							<div class="col-sm-5">
								<img class="full-width" src="{{asset('proassests/media/services.jpg')}}" alt="Img">
							</div>
							<div class="col-sm-7 text-block">
								<p><strong style="font-size:23px;">Services</strong></p>
								<p>My Road Star is a transportation network company, sometimes known as a mobility service provider (MSP), we provide a variety of services ranging from Airport transfers, Chartered Maxi services and Taxis through our free user friendly Ride sharing app.</p>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab4" style="margin-bottom: 50px;">
						<div class="row">
							<div class="col-sm-5">
								<img class="full-width" src="{{asset('proassests/media/rating.png')}}" alt="Img">
							</div>
							<div class="col-sm-7 text-block">
								<p><strong style="font-size:23px;">The My Road Star Experience</strong></p>
								<p>We at My Road Star have taken on the responsibility, challenge and the task of providing services that are affordable, easy to use, hassle free and that utilize conventional and modern technology. All of our services can be easily accessed by means of website, call center or mobile app and we can also be reached <b>24/7</b> by phone, email, Whatsapp, Facebook and even Instagram.</p>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab5">
						<div class="row">
							<div class="col-sm-5">
								<img class="full-width" src="{{asset('proassests/media/safer.png')}}" alt="Img">
							</div>
							<div class="col-sm-7 text-block">
								<p><strong style="font-size:23px;">A Safer Community</strong></p>
								<p>We believe you deserve a safe transportation service, safe enough for us to trust our own mothers, sisters, children and loved ones with. So we have created guidelines that protect and help passengers and drivers. Both can enjoy a safe comfortable travelling experience. Any person who doesn’t follow these guidelines may be at risk of being removed from our service for the safety of the My Road Star community.</p>
							</div>
						</div>
					</div>
		<!--		</div>.   -->
			</div>

@endsection