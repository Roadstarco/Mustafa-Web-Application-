@extends('user.layout.app')
@section('content')
<style>
    .trip-post-page h1.page-header {
        padding-bottom: 15px;
        margin: 0px 0 20px;
        font-weight: 600;
        border-bottom: none;
    }
    .trip-post-page .page-header-wrapper{
        margin-bottom: 0px;
    }
    .trip-post-page h1.page-header small{
        font-size: 17px;
    }
    hr{
        border-top: 1px solid #dac1c1;
        width: 17%;
        margin-top: 10px;
    }
    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }
    .card-header {
        padding: .75rem 3rem;
        margin-bottom: 0;
        background-color: rgba(0,0,0,.03);
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .card-header:first-child {
        border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
    }
    .form-area form{
        padding: 3rem 2rem;
    }
    .form-area form .control-label{
        font-weight: 500;
        font-family: system-ui;
        font-size: 15px;
    }
    .pb-help-message{
        margin-top: 5px;
    }
    .pb-help-message p,.pb-help-message p strong{
        font-family: system-ui;
        font-size: 13.5px;
        color:#948989;
        font-weight: 400;
    }
    .pb-help-message p i{
        color:#948989;
    }
    .pb-help-message p strong{
        font-weight: 500;
    }
  .form-area form  .form-group {
    margin-bottom: 20px;
}
.form-control {
     background-color:#F7F7F7;
    border-radius:10px;
    border-radius: 0;
    border: 0;
    box-shadow: none;
    padding: 12px;
    height: auto;
}
.fa-check:before {
    color: black;
    content: "\f00c";
}
</style>
<
<div class="bg-image page-title">
                <div class="container-fluid">
                    <a href="#"><h1>POST TRIP</h1></a>
                    <div class="pull-right">
                        <a href=""><i class="fa fa-home fa-lg"></i></a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="">Post trip</a>
                    </div>
                </div>
            </div>
            <br><br><br>
<div class="banner row no-margin trip-post-page">
    <div class="banner-overlay" style="background-color: #fff"></div>
    <div id="main">
        <div class="container">
            <div class="row page-header-wrapper">
                <div class="col-md-12">
                    <h1 class="page-header text-center">CROWD SHIPPING FOR LAND, SEA AND AIR
                        <br> <small>RECOVER YOUR TRAVEL COST</small>
                        <hr>
                    </h1>
                    <!--<p class="post-trip-desc">Delivered by Trusted and Verified travelers (Providers)</p>-->
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 form-area">
                    <div class="card">  
                        <div class="card-header"> <h3>Trip Details</h3></div> 
                        @if(Session::has('error_msg'))
                        <div class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error_msg') }}</div>
                        @elseif(Session::has('success_msg'))
                        <div class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_msg') }}</div>
                        @endif

                        <form action="{{ url('/save-trip') }}" method="post" id="travel-form" class="form-horizontal">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"><label class="control-label col-sm-4 required">Delivery From </label>
                                        <div class="col-sm-8">
                                            <input type="text" onfocus="initialize(this)" id="tripfrom" name="tripfrom" required="required" placeholder="City or Country" value="{{ old('tripfrom') }}" class="form-control" />
                                            <input type="hidden" id="tripfrom_lat" name="tripfrom_lat" value="{{ old('tripfrom_lat') }}"/>
                                            <input type="hidden" id="tripfrom_lng" name="tripfrom_lng" value="{{ old('tripfrom_lng') }}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required">Delivery To </label>
                                        <div class="col-sm-8">
                                            <input type="text" onfocus="initialize(this)" id="tripto" name="tripto" required="required" placeholder="City or Country" class="form-control" value="{{ old('tripto') }}" />
                                            <input type="hidden" id="tripto_lat" name="tripto_lat" value="{{ old('tripto_lat') }}"/>
                                            <input type="hidden" id="tripto_lng" name="tripto_lng" value="{{ old('tripto_lng') }}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required" for="arrival_date">
                                            Arrival Date
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="date" id="arrival_date" name="arrival_date" required="required" class="form-control" value="{{old('arrival_date')?:date('Y-m-d')}}" min="{{date('Y-m-d')}}"/></div></div>
                                </div>
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="return_date">
                                            Return Date (Optional)
                                         <?php 
                                        //  var_dump(session()->all());
                                          ?>
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="date" id="return_date" name="return_date" class="form-control" value="{{old('return_date')?:''}}" min="{{date('Y-m-d')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="recurrence" value="never"/>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required" for="item_size">
                                            Your Item Size
                                        </label>
                                        <div class="col-sm-8">
                                            <select id="item_size" name="item_size" class="form-control" placeholder="Select Item Size">
                                            
                                                <option value="">Select Item Size</option>
                                                <option value="Small" {{ old('item_size')=='Small'?"selected":"" }}>Small</option>
                                                <option value="Medium" {{ old('item_size')=='Medium'?"selected":"" }}>Medium</option>
                                                <option value="Large" {{ old('item_size')=='Large'?"selected":"" }}>Large</option>
                                                <option value="Extra Large" {{ old('item_size')=='Extra Large'?"selected":"" }}>Extra large</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 text-right">
                                            <div class="pb-help-message child-size">
                                                <p><i class="fa fa-info-circle"></i>&nbsp; <strong>Small</strong> (keys), <strong>Medium</strong> (phone, book, bag), <strong>Large</strong> (big box, instrument), <strong>Extra large</strong> (vehicle, pallet)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="service_type">
                                            Service Type
                                        </label>
                                        <div class="col-sm-8">
                                            <select id="service_type" name="service_type" required="" placeholder="Transportation" class="form-control">
                                                <option value="">Select Transportation Type</option>
                                                <?php
                                                if(isset($_GET['service_type']) && $_GET['service_type'] == 'ground')
                                                {
                                                   echo'<option value="By Road" {{ old(\'service_type\')==\'Ground\'?"selected":"" }} selected="selected" >Ground</option>';
                                                }
                                                else
                                                {
                                                    echo'<option value="By Road" {{ old(\'service_type\')==\'Ground\'?"selected":"" }}>Ground</option>';
                                                }
if(isset($_GET['service_type']) && $_GET['service_type'] == 'air')
                                                {
                                                   echo'<option value="By Air" {{ old(\'service_type\')==\'Air\'?"selected":"" }} selected="selected" >Air</option>';
                                                }
                                                else
                                                {
                                                    echo'<option value="By Air" {{ old(\'service_type\')==\'Air\'?"selected":"" }}>Air</option>';
                                                }
                                                if(isset($_GET['service_type']) && $_GET['service_type'] == 'sea')
                                                {
                                                   echo'<option value="By Sea" {{ old(\'service_type\')==\'Sea\'?"selected":"" }} selected="selected" >Sea</option>';
                                                }
                                                else
                                                {
                                                    echo'<option value="By Sea" {{ old(\'service_type\')==\'Sea\'?"selected":"" }}>Sea</option>';
                                                }

                                                ?>
                                                
                                          <!--      <option value="By Air" {{ old('service_type')=='Air'?"selected":"" }}>Air</option>
                                                <option value="By Sea" {{ old('service_type')=='Sea'?"selected":"" }}>Sea</option> -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="more-details">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="travel_form_type_infos">
                                            Parcel Details
                                        </label>
                                        <div class="col-sm-8">
                                            <textarea id="other_information" rows="5" name="other_information" placeholder="Other Information" class="form-control">{{old('other_information') }}</textarea>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"> Email </label>
                                        <div class="col-sm-8">
                                            <input type="email" id="email" name="email" value="{{old('email')}}" required="required" placeholder Email" class="form-control" />
                                            <div class="pb-help-message child-size">
                                                <p> <i class="fa fa-info-circle"></i>&nbsp; Enter Already Registered Email</p>
                                            </div>
                                        </div>
                                    </div>
                                       <button type="submit" class="btn content-more-btn pull-right  btnstyle"><i class="fa fa-check" style="color: white"></i> POST YOUR TRIP</button>
                                         <style type="text/css">
                                         .btnstyle{
                                            color:black;
                                            border-style:solid;
                                            border-width:1px;
                                         }
                                                 
                                         </style>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
<?php $footer = asset('asset/img/footer-city.png'); ?>
<div class="footer-city row no-margin" style="background-image: url({{$footer}});"></div>
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key') }}&libraries=places&callback=initMap" async defer></script>
@section('scripts')
@endsection
<script type="text/javascript">
                                                function initialize(item) {
                                                    var options = {
                                                        types: ['(cities)']
                                                    };
                                                    var autocomplete = new google.maps.places.Autocomplete(item, options);
                                                    google.maps.event.addListener(autocomplete, 'place_changed', function () {
                                                        var place = autocomplete.getPlace();
                                                        document.getElementById($(item).attr('id') + '_lat').value = place.geometry.location.lat();
                                                        document.getElementById($(item).attr('id') + '_lng').value = place.geometry.location.lng();
                                                        console.log(place.geometry.location.lat());
                                                        if(place.geometry.location.lat() == null)
                                                        {
                                                            document.getElementById('tripfrom').value = ""; 
                                                        }

                                                    });


                                                }
</script>
<br><br><br>
@endsection