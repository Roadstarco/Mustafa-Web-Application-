@extends('user.layout.base')

@section('title', 'Dashboard ')

@section('content')

<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">Deliver Anything</h4>
            </div>
        </div>
        @include('common.notify')
        <div class="row no-margin">
            <div class="col-md-12">
                <form action="{{url('confirm/ride')}}" method="GET" onkeypress="return disableEnterKey(event);" enctype="multipart/form-data">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="form-group col-md-6">
                        <label>Pickup From</label>
                        <input type="text"  class="form-control" id="origin-input" name="s_address"  placeholder="Enter pickup location" >
                    </div>

                    <div class="form-group col-md-6">
                    <label>Deliver To</label>
                    <input type="text" class="form-control" id="destination-input" name="d_address"  placeholder="Enter drop location" >
                    </div>

                    <div class="form-group col-md-6">

                        <label for="category">
                            Category
                        </label>
                        <select id="category" required="" name="category" class="form-control" placeholder="Select Item Category">
                            <option value="">Select Item Category</option>
                            <option value="Wood" >Wood</option>
                            <option value="Steel" >Steel</option>
                            <option value="Rock" >Rock</option>
                        </select>

                    </div>


                     <div class="form-group col-md-6">

                        <label for="product_type">
                            Product Type
                        </label>
                        <select id="product_type" required="" name="product_type" class="form-control" placeholder="Select Item Type">
                            <option value="">Select Item Type</option>
                            <option value="Cargo" >Cargo</option>
                            <option value="Parcel" >Parcel</option>
                        </select>

                    </div>


                    <div class="form-group col-md-6">
                        <label>Product Weight</label>
                        <input type="text"  class="form-control" id="product_weigh" name="product_weight"  placeholder="Enter product weight" >
                    </div>


                    <div class="form-group col-md-6">

                        <label for="weight_unit">
                            Weight Unit
                        </label>
                        <select id="weight_unit" required="" name="weight_unit" class="form-control" placeholder="Select Item Weight Unit">
                            <option value="">Select Item Weight Unit</option>
                            <option value="Gm" >Gm</option>
                            <option value="Kg" >Kg</option>
                        </select>

                    </div>


                    <div class="form-group col-md-6">
                        <label>Product Width</label>
                        <input type="text"  class="form-control" id="product_width" name="product_width"  placeholder="Enter item width eg 5 ft" >
                    </div>

                    <div class="form-group col-md-6">
                        <label>Product Height</label>
                        <input type="text"  class="form-control" id="product_height" name="product_height"  placeholder="Enter item height eg 10 ft" >
                    </div>


                    <div class=" form-group col-md-6" >
                        <label  for="instruction">
                            Instructions
                        </label>
                        <textarea name="instruction" class="form-control"></textarea>

                    </div>

                    <div class=" form-group col-md-6" >
                        <label  for="product_distribution">
                            Product Discription
                        </label>
                        <textarea name="product_distribution" class="form-control"></textarea>

                    </div>

                   

                    <!-- updated by nabeel hassan starts on 04-01-21 -->

                    

                    <!-- updated by nabeel hassan end on 04-01-21 -->


                    <input type="hidden" name="s_latitude" id="origin_latitude">
                    <input type="hidden" name="s_longitude" id="origin_longitude">
                    <input type="hidden" name="d_latitude" id="destination_latitude">
                    <input type="hidden" name="d_longitude" id="destination_longitude">
                    <input type="hidden" name="current_longitude" id="long">
                    <input type="hidden" name="current_latitude" id="lat">
                    <input type="hidden" name="polyline_data" id="polyline_data">

                    <div class="car-detail col-md-6">

                        @foreach($services as $service)
                        <div class="car-radio">
                            <input type="radio" 
                                name="service_type"
                                value="{{$service->id}}"
                                id="service_{{$service->id}}"
                                @if ($loop->first) checked="checked" @endif>
                            <label for="service_{{$service->id}}">
                                <div class="car-radio-inner">
                                    <div class="img"><img src="{{$service->image}}"></div>
                                    <div class="name"><span>{{$service->name}}</span></div>
                                </div>
                            </label>
                        </div>
                        @endforeach


                    </div>

                    <button type="submit"  class="full-primary-btn fare-btn">@lang('user.ride.ride_now')</button>

                </form>
            </div>

           
        </div>


          <div class="row" style="margin: 25px 0px 0px 0px;">

         <div class="col-md-12">
                <div class="map-responsive">
                    <div id="map" style="width: 100%; height: 450px;"></div>
                </div> 
            </div>

        </div>      
                

    </div>
</div>

@endsection

@section('scripts')    
<script type="text/javascript">
    var current_latitude = 33.720001;
    var current_longitude = 73.059998;
</script>

<script type="text/javascript">
    if( navigator.geolocation ) {
       navigator.geolocation.getCurrentPosition( success, fail );
    } else {
        console.log('Sorry, your browser does not support geolocation services');
        initMap();
    }

    function success(position)
    {
      
        document.getElementById('long').value = position.coords.longitude;
        document.getElementById('lat').value = position.coords.latitude

        if(position.coords.longitude != "" && position.coords.latitude != ""){
            current_longitude = position.coords.longitude;
            current_latitude = position.coords.latitude;
        }
        initMap();
    }

    function fail()
    {
        // Could not obtain location
        console.log('unable to get your location');
        initMap();
    }
</script> 

<script type="text/javascript" src="{{ asset('asset/js/map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key') }}&libraries=places&callback=initMap" async defer></script>

<script type="text/javascript">
    function disableEnterKey(e)
    {
        var key;
        if(window.e)
            key = window.e.keyCode; // IE
        else
            key = e.which; // Firefox

        if(key == 13)
            return e.preventDefault();
    }
</script>

@endsection