@extends('user.layout.app')

@section('title', 'Services')
@section('description', 'A wide network of connections providing sea, air and package delivery all in one place. Get anything delivered anywhere')

@push('scripts')
<script>
 $( document ).ready(function() {
     setTimeout(function(){ 
        console.log("i am here");
        if (window.location.href.indexOf("#sea-delivery-section") > -1) {
        window.location = 'https://myroadstar.org/service#sea-delivery-section';
        }
        if (window.location.href.indexOf("#air-delivery-section") > -1) {
        window.location = 'https://myroadstar.org/service#air-delivery-section';
        }
        if (window.location.href.indexOf("#package-delivery-section") > -1) {
        window.location = 'https://myroadstar.org/service#package-delivery-section';
        }
     }, 5000);
 });
</script>
@endpush
@section('content')


<div class="bg-image page-title">
				<div class="container-fluid">
					<a href="#"><h1>OUR SERVICES</h1></a>
					<div class="pull-right">
						<a href="{{url('/')}}"><i class="fa fa-home fa-lg"></i></a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="#">Our services</a>
					</div>
				</div>
			</div>

			<div style="margin-bottom:-20px;" class="container-fluid block-content">
                <div class="text-center hgroup wow zoomInUp" data-wow-delay="0.3s">
                    <h1>OUR SERVICES</h1>
                    <h2>WE HAVE A WIDE NETWORK OF CONNECTIONS IN ALL THE MAJOR LOCATIONS TO HELP YOU WITH <br> the services we offer</h2>
                </div>
                <div class="row our-services">
                    <div class="col-sm-6 col-md-4 col-lg-4 wow zoomInLeft" data-wow-delay="0.3s">
                        <a href="#sea-delivery-section">
                            <span><i class="glyph-icon flaticon-boats4"></i></span>
                            <h4>SEA DELIVERY</h4>
                            <button type="button" class="btn btn-outline-secondary">Read more</button>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 wow zoomInUp" data-wow-delay="0.3s">
                        <a href="#air-delivery-section">
                            <span><i class="glyph-icon flaticon-flying"></i></span>
                            <h4>AIR DELIVERY</h4>
                            <button type="button" class="btn btn-outline-secondary">Read more</button>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 wow zoomInRight" data-wow-delay="0.3s">
                        <a href="#package-delivery-section">
                            <span><i class="glyph-icon flaticon-package7"></i></span>
                            <h4>PACKAGE DELIVERY</h4>
                           <button type="button" class="btn btn-outline-secondary">Read more</button>
                        </a>
                    </div>
                </div>
                <hr>
            </div>

			<div  style="margin-bottom:-130px;" id="opening" class="container-fluid inner-offset">    
				<div class="row services">
					<div class="service-item col-xs-12 col-sm-4 wow zoomIn box1" id="package-delivery-section" data-wow-delay="0.3s">
						 <img class="full-width" src="{{asset('proassests/media/3-column-info/6.jpg')}}" alt="slider">
						<h4>Package Delivery</h4>
						<p align="justify" style="padding-bottom:70px;">Package delivery or parcel delivery includes shipping containers, parcels or high value mail as a single shipment. My Road Star provides this service at minimal fares.</p>
						<a style="margin-bottom:20px;" class="btn btn-success btn-sm" href="{{url('post-trip?service_type=ground')}}">BOOK NOW</a>
					</div>
					<div class="service-item col-xs-12 col-sm-4 wow zoomIn" data-wow-delay="0.3s" id="sea-delivery-section">
						<img class="full-width" src="{{asset('proassests/media/3-column-info/5.jpg')}}" alt="slider">
						<h4>Sea Delivery</h4>
						<p align="justify">Much freight transport is done by ships. An individual's nation's fleet and the people that crew it are referred to as its merchant navy or merchant marine. This kind of shipping is the lifeblood of the world economy, carrying 90% of international trade with 102,194 commercial ships worldwide. My Road Star also provides this services at a reasonable fares.</p>
						<a style="margin-bottom:20px;" class="btn btn-success btn-sm" href="{{url('post-trip?service_type=sea')}}">BOOK NOW</a>
					</div>
					<div class="service-item col-xs-12 col-sm-4 wow zoomIn box1" data-wow-delay="0.3s" id="air-delivery-section">
						<img class="full-width" src="{{asset('proassests/media/3-column-info/4.jpg')}}" alt="slider">
						<h4>Air Delivery</h4>
						<p align="justify">Cargo is transported by air in specialized cargo aircraft and in the luggage compartments of passenger aircraft. Air freight is typically the fastest mode of long-distance freight transport, but it is also the most expensive. My Road Star provides this service too, although it's expensive but we take care of our customers.</p>
						<a style="margin-bottom:20px;" class="btn btn-success btn-sm" href="{{url('post-trip?service_type=air')}}">BOOK NOW</a>
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
                            <form action="{{url('trips')}}">
            <div class="input-group find-form">
                <input type="text" name="search" class="form-control" placeholder="Search" />
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