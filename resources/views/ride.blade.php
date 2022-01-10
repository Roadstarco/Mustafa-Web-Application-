@extends('user.layout.app')

@section('title', 'Delivery')
@section('description', 'My Road Star is the most efficient way to get your package delivered in time, with any means of payment option that suits you, through the delivery option of your choice')

@section('content')

<div class="bg-image page-title">
				<div class="container-fluid">
					<a href="#"><h1>DELIVERY</h1></a>
					<div class="pull-right">
						<a href="{{url('/')}}"><i class="fa fa-home fa-lg"></i></a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="{{url('/ride')}}">Delivery</a>
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
                        <a href="{{url('/provider/login')}}">
                            <span><i class="fa fa-user" style="font-size:28px;"></i></span>
                            <h4>Sign in As Provider</h4>
                            <button type="button" class="btn btn-outline-secondary">SIGN IN</button>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 wow zoomInRight" data-wow-delay="0.3s">
                        <a href="{{url('/provider/register')}}">
                            <span><i class="fa fa-user-plus" style="font-size:28px;"></i></span>
                            <h4>Sign up As Provider</h4>
                           <button type="button" class="btn btn-outline-secondary">SIGN UP</button>
                        </a>
                    </div>
                </div>
                <hr>
            </div>

            <style type="text/css">
            	.boxstyle{
            		padding-top:15px;
            		padding-left:10px;
            		padding-right:10px;
            		border-style:solid;
            		border-width:1px;
            	}
            </style>

            <div class="container-fluid block-content">
				<div class="hgroup text-center wow zoomIn" data-wow-delay="0.3s">
					<h1>HOW WE WORK</h1>
					<h2>dedicated & professional</h2>
				</div>	
				<!-- <div class="col-sm-1 wow zoomIn" data-wow-delay="0.3s">
					</div> -->
					<div class="col-sm-4 wow zoomIn" data-wow-delay="0.3s">
						<div class="boxstyle" style="padding-bottom:45px;">
							<div class="userpic">
								<div align="center"><span><i class="fa fa-cubes hgroup text-center" style="font-size:48px;"></i></span></div>
							</div>				
						<div class="user-info text-center">
							<h6>Tap the app, deliver a package</h6>
							<p>My Road Star is by far the most efficient way to get around! Your car services are now just a tap away!</p>
						</div>
					  </div>
					</div>

					
					<div class="col-sm-4 wow zoomIn" data-wow-delay="0.3s">
						<div class="boxstyle" style="padding-bottom:23px;">
						<div class="userpic">
					<div align="center"><span><i class="fa fa-credit-card hgroup text-center" style="font-size:48px;"></i></span></div>
						</div>
						<div class="user-info text-center">
							<h6>Choose how to pay</h6>
							<p>When your package arrive at the destination, either pay cash or have your card automatically charged! With My Road Star, the choice is yours.</p>
						</div>
						 </div>
					</div>

					<div class="col-sm-4 wow zoomIn" data-wow-delay="0.3s">
						<div class="boxstyle" style="padding-bottom:22px;">
						<div class="userpic">
					<div align="center"><span><i class="fa fa-star hgroup text-center" style="font-size:48px;"></i></span></div>
						</div>
						<div class="user-info text-center">
							<h6>You rate, we listen</h6>
							<p>Rate your driver and provide anonymous feedback about the trip. Your input will help us make every delivery a 5(five)-star experience!</p>
						</div>
					</div>
					</div>
				</div>
			</div>   
		</div>

<div style="background-color:#f7f7f7; overflow: hidden;">
		<div class="container-fluid inner-offset">
				<div class="hgroup text-center wow zoomIn" data-wow-delay="0.3s">
					<h2>MYROADSTAR</h2>
					<h1>There’s a delivery for every price</h1>
				</div>            
				<ul class="nav nav-tabs wow fadeIn" data-wow-delay="0.3s" id="myTab">
					<li class="active"><a href="#tab1" data-toggle="tab">ECONOMY</a></li>
					<li><a href="#tab2" data-toggle="tab">PREMIUM</a></li>
					<li><a href="#tab3" data-toggle="tab">ACCESSIBILITY</a></li>
					<li><a href="#tab4" data-toggle="tab">CARPOOL</a></li>
				</ul>
				<div class="tab-content inner-offset wow fadeIn" data-wow-delay="0.3s">
					<div class="tab-pane active" id="tab1">
						<div align="center">
							<div class="row">
                              <img src="{{asset('proassests/media/cars/1.png')}}" alt="Img">
						</div>
					</div>
				</div>
					<div class="tab-pane" id="tab2">
					<div align="center">
						<div class="row">
							<div class="col-sm-12">
								 <img src="{{asset('proassests/media/cars/4.png')}}" alt="Img">
							</div>
						</div>
					  </div>
					</div>
					<div class="tab-pane" id="tab3">
						<div align="center">
						<div class="row">
							<div class="col-sm-12">
								<img src="{{asset('proassests/media/cars/3.png')}}" alt="Img">
							</div>
						</div>
					</div>
					</div>
					<div class="tab-pane" id="tab4">
						<div align="center">
						<div class="row">
							<div class="col-sm-12">
								<img src="{{asset('proassests/media/cars/2.png')}}" alt="Img">
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
		 <div class="block-content bg-image blog-section inner-offset">
                <div class="container-fluid">
                    <div class="hgroup wow fadeInLeft" data-wow-delay="0.3s">
                        <h1 align="center">By the Power of the Crowd.</h1>
                        <h2 align="center">My Road Star</h2>             
                    </div>
                    <div class="row" align="center">
                    <div class="col-sm-12 col-md-12 col-lg-12 one-news wow fadeInLeft" data-wow-delay="0.3s">
                        <style type="text/css">
                            .input-group .form-control{
                                border-top-left-radius: 6px;
                                border-bottom-left-radius: 6px;
                            }
                        </style>
                            <form>
            <div class="input-group find-form">
                <input type="text" class="form-control"  placeholder="Search" >
                <span class="input-group-addon">
                    <button type="submit">
                        <i class="fa fa-arrow-right"></i>
                    </button>  
                </span>
            </div>
        </form>
                        </div>
                    
                    </div>
                </div>
            </div>

@endsection