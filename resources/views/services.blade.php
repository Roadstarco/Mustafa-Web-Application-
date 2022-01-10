@extends('user.layout.app')

@section('content')
<div class="banner row no-margin" style="background-image: url('{{ asset('asset/img/banner-bg.jpg') }}');">
    <div class="banner-overlay"></div>
    <div class="container">
        <div class="col-md-8">
            <h2 class="banner-head"><span class="strong">Always the best service you want</span><br>The best way to get wherever you want to deliver</h2>
        </div>

    </div>
</div>

<div class="row white-section no-margin">
    <div class="container">

        <div class="col-md-4 content-block small" style="background-color: #3582a2 !important">
            <a href="https://myroadstar.org/post-trip?service_type=ground">
                <h2>Package Delivery</h2>
                <div class="title-divider"></div>
            </a>
        </div>
        <div class="col-md-4 content-block small" style= "background-color: #7a95b4 !important">
            <a href="{{url('post-trip?servicetype=sea')}}">
                <h2>Sea Delivery</h2>
                <div class="title-divider"></div>
            </a>
        </div>
        <div class="col-md-4 content-block small" style="background-color: #b05a23 !important">
            <a href="{{url('post-trip?servicetype=air')}}">
                <h2>Air Delivery</h2>
                <div class="title-divider"></div>
            </a>
        </div>


    </div>
</div>


</div>
</div>

<div class="row white-section no-margin"style="background-color: #3582a2 !important">
    <div class="container">                
        <div class="col-md-6 content-block">
            <a href="https://myroadstar.org/post-trip?service_type=ground">
                <h2>Package Delivery</h2>
                <div class="title-divider" style="background-color: #ffff !important"></div> </a>
            <p>Package delivery or parcel delivery is the delivery of shipping containers, parcels, or high value mail as single shipments. {{ Setting::get('site_title', 'Uber') }} provide this service at very low fares.</p>
        </div>
        <div class="col-md-6 img-block text-center"> 
            <img src="{{asset('asset/img/package.png')}}">
        </div>
    </div>
</div>
<div class="row white-section no-margin"style="background-color: #7a95b4 !important">
    <div class="container">                
        <div class="col-md-6 content-block">
            <a href="{{url('post-trip?servicetype=sea')}}">
                <h2>Sea Delivery</h2>
                <div class="title-divider" style="background-color: #ffff !important"></div> </a>
            <p>Much freight transport is done by ships. An individual nation's fleet and the people that crew it are referred to as its merchant navy or merchant marine. Merchant shipping is the lifeblood of the world economy, carrying 90% of international trade with 102,194 commercial ships worldwide. {{ Setting::get('site_title', 'Uber') }} also provides this services at a reasonable fares.</p>
        </div>
        <div class="col-md-6 img-block text-center"> 
            <img src="{{asset('asset/img/seaservice.png')}}">
        </div>
    </div>
</div>
<div class="row white-section no-margin"style="background-color: #b05a23 !important">
    <div class="container">                
        <div class="col-md-6 content-block">
            <a href="{{url('post-trip?servicetype=air')}}">
                <h2>Air Delivery</h2>
                <div class="title-divider" style="background-color: #ffff !important"></div> </a>
            <p>Cargo is transported by air in specialized cargo aircraft and in the luggage compartments of passenger aircraft. Air freight is typically the fastest mode for long-distance freight transport, but it is also the most expensive. {{ Setting::get('site_title', 'Uber') }} provides this services too, althought its expensive but we take care of our customers.</p>
        </div>
        <div class="col-md-6 img-block text-center"> 
            <img src="{{asset('asset/img/airservice.png')}}">
        </div>
    </div>
</div>


<div class="row find-city no-margin">
    <div class="container">
        <h2>{{Setting::get('site_title','Uber')}} is in your city</h2>
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
<?php $footer = asset('asset/img/footer-city.png'); ?>
<div class="footer-city row no-margin" style="background-image: url({{$footer}});"></div>
@endsection
