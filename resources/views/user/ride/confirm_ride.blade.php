@extends('user.layout.base')

@section('title', 'Ride Confirmation ')

@section('styles')
<style type="text/css">
    .surge-block{
        background-color: black;
        width: 50px;
        height: 50px;
        border-radius: 25px;
        margin: 0 auto;
        padding: 10px;
        padding-top: 15px;
    }
    .surge-text{
        top: 11px;
        font-weight: bold;
        color: white;
    }
    #recreate_ride{
        display: none;
    }
</style>
@endsection

@section('content')
<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                {{--<h4 class="page-title">@lang('user.ride.ride_now')</h4>--}}
                <h4 class="page-title">Deliver {{Request::get('product_type')}}</h4>

            </div>
        </div>
        @if(Session::has('flash_success_recreate'))
        <style type="text/css">
        #create_ride{
            display: none
        }
        #recreate_ride{
            display: none
        }
        </style>
    <div class="alert alert-success" id= "alert alert-success" style="text-align: center;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p> No Drivers Found </p>
        <button type="button" class="half-primary-btn fare-btn" onclick="myFunction()" >Send to other providers</button>
        <button type="button" class="half-primary-btn fare-btn" onclick="myFunction1()" >Retry with same type</button>
        
        {{ Session::get('flash_success') }}
    </div>
@endif
<!-----------------------------------------    ---->
        <div class="row no-margin">
            <div class="col-md-12">
                <form action="{{url('create/ride')}}" method="POST" id="create_ride" enctype="multipart/form-data">

                {{ csrf_field() }}
                    <dl class="dl-horizontal left-right">
                        <dt>@lang('user.type')</dt>
                        <dd>{{$service->name}}</dd>
                        <dt>@lang('user.total_distance')</dt>
                        <dd>{{$fare->distance}} Kms</dd>
                        <dt>@lang('user.eta')</dt>
                        <dd>{{$fare->time}}</dd>
                        <dt>@lang('user.estimated_fare')</dt>
                        <dd>{{currency($fare->estimated_fare)}}</dd>

                        <dt>{{ Request::get('payment_mode') }}</dt>
                        <dd>check</dd>                        
                        <hr>
                        @if(Auth::user()->wallet_balance > 0)

                        <input type="checkbox" name="use_wallet" value="1"><span style="padding-left: 15px;">@lang('user.use_wallet_balance')</span>
                        <br>
                        <br>
                            <dt>@lang('user.available_wallet_balance')</dt>
                            <dd>{{currency(Auth::user()->wallet_balance)}}</dd>
                        @endif
                    </dl>

                    <input type="hidden" name="s_address" value="{{Request::get('s_address')}}">
                    <input type="hidden" name="d_address" value="{{Request::get('d_address')}}">
                    <input type="hidden" name="s_latitude" value="{{Request::get('s_latitude')}}">
                    <input type="hidden" name="s_longitude" value="{{Request::get('s_longitude')}}">
                    <input type="hidden" name="d_latitude" value="{{Request::get('d_latitude')}}">
                    <input type="hidden" name="d_longitude" value="{{Request::get('d_longitude')}}">
                    <input type="hidden" name="service_type" value="{{Request::get('service_type')}}">
                    <input type="hidden" name="category" value="{{Request::get('category')}}">
                     <input type="hidden" name="product_type" value="{{Request::get('product_type')}}">
                    <input type="hidden" name="product_weight" value="{{Request::get('product_weight')}}">
                    <input type="hidden" name="weight_unit" value="{{Request::get('weight_unit')}}">
                    <input type="hidden" name="product_width" value="{{Request::get('product_width')}}">
                     <input type="hidden" name="product_height" value="{{Request::get('product_height')}}">
                    <input type="hidden" name="instruction" value="{{Request::get('instruction')}}">
                    <input type="hidden" name="product_distribution" value="{{Request::get('product_distribution')}}">
                    <input type="hidden" name="distance" value="{{$fare->distance}}">
                    <!--<input type="hidden" name="web" value="1"> -->
                    <div class="form-group col-md-12">
                    <p>@lang('user.payment_method')</p>
                    <select class="form-control" name="payment_mode" id="payment_mode" onchange="card(this.value);">
                     {{-- <option value="CASH">CASH</option> --}}
                     <option value="">Select</option>
                      @if(Setting::get('CARD') == 1)
                      @if($cards->count() > 0)
                        <option value="CARD">CARD</option>
                      @endif
                      @endif
                    </select>
                    <br>

                    @if(Setting::get('CARD') == 1)
                        @if($cards->count() > 0)
                        <select class="form-control" name="card_id" style="display: none;" id="card_id">
                          <option value="">Select Card</option>
                          @foreach($cards as $card)
                            <option value="{{$card->card_id}}">{{$card->brand}} **** **** **** {{$card->last_four}}</option>
                          @endforeach
                        </select>
                        @endif
                    @endif

                    @if($fare->surge == 1)

                        <span><em>Note : Due to High Demand the fare may vary!</em></span>
                        <div class="surge-block"><span class="surge-text">{{$fare->surge_value}}</span>
                        </div>
                    
                    @endif

                    </div>

                    <div class="form-group col-md-4">
                        <label>Product Image 1</label>
                        <input type="file" accept="image/*"  class="form-control dropify" id="attachment1" name="attachment1" 
                        data-allowed-file-extensions='["png", "jpg" ,"jpeg"]'  >
                    </div>

                    <div class="form-group col-md-4">
                        <label>Product Image 2</label>
                        <input type="file" accept="image/*"  class="form-control dropify" id="attachment2" name="attachment2" 
                        data-allowed-file-extensions='["png", "jpg" ,"jpeg"]' >
                    </div>

                     <div class="form-group col-md-4">
                        <label>Product Image 3</label>
                        <input type="file" accept="image/*"  class="form-control dropify" id="attachment3" name="attachment3" 
                        data-allowed-file-extensions='["png", "jpg" ,"jpeg"]' >
                    </div>

                     <div class="form-group col-md-12">
                     
                    <button type="submit" class="half-primary-btn fare-btn">@lang('user.ride.send_package_now')</button>
                    <button type="button" class="half-secondary-btn fare-btn" data-toggle="modal" data-target="#schedule_modal">@lang('user.ride.send_package_later')</button>
                    </div>
                </form>

<!-----------------------------------------    ---->
<form action="{{url('recreate/ride')}}" method="POST" id="recreate_ride" enctype="multipart/form-data">

                {{ csrf_field() }}
                    <dl class="dl-horizontal left-right">
                        <dt>@lang('user.type')</dt>
                        <dd>{{$service->name}}</dd>
                        <dt>@lang('user.total_distance')</dt>
                        <dd>{{$fare->distance}} Kms</dd>
                        <dt>@lang('user.eta')</dt>
                        <dd>{{$fare->time}}</dd>
                        <dt>@lang('user.estimated_fare')</dt>
                        <dd>{{currency($fare->estimated_fare)}}</dd>

                        <dt>{{ Request::get('payment_mode') }}</dt>
                        <dd>check</dd>                        
                        <hr>
                        @if(Auth::user()->wallet_balance > 0)

                        <input type="checkbox" name="use_wallet" value="1"><span style="padding-left: 15px;">@lang('user.use_wallet_balance')</span>
                        <br>
                        <br>
                            <dt>@lang('user.available_wallet_balance')</dt>
                            <dd>{{currency(Auth::user()->wallet_balance)}}</dd>
                        @endif
                    </dl>

                    <input type="hidden" name="s_address" value="{{Request::get('s_address')}}">
                    <input type="hidden" name="d_address" value="{{Request::get('d_address')}}">
                    <input type="hidden" name="s_latitude" value="{{Request::get('s_latitude')}}">
                    <input type="hidden" name="s_longitude" value="{{Request::get('s_longitude')}}">
                    <input type="hidden" name="d_latitude" value="{{Request::get('d_latitude')}}">
                    <input type="hidden" name="d_longitude" value="{{Request::get('d_longitude')}}">
                    <input type="hidden" name="service_type" value="{{Request::get('service_type')}}">
                    <input type="hidden" name="category" value="{{Request::get('category')}}">
                     <input type="hidden" name="product_type" value="{{Request::get('product_type')}}">
                    <input type="hidden" name="product_weight" value="{{Request::get('product_weight')}}">
                    <input type="hidden" name="weight_unit" value="{{Request::get('weight_unit')}}">
                    <input type="hidden" name="product_width" value="{{Request::get('product_width')}}">
                     <input type="hidden" name="product_height" value="{{Request::get('product_height')}}">
                    <input type="hidden" name="instruction" value="{{Request::get('instruction')}}">
                    <input type="hidden" name="product_distribution" value="{{Request::get('product_distribution')}}">
                    <input type="hidden" name="distance" value="{{$fare->distance}}">
                    <!--<input type="hidden" name="web" value="1"> -->
                    <div class="form-group col-md-12">
                    <p>@lang('user.payment_method')</p>
                    <select class="form-control" name="payment_mode" id="payment_mode" onchange="card1(this.value);">
                     {{-- <option value="CASH">CASH</option> --}}
                     <option value="">Select</option>
                      @if(Setting::get('CARD') == 1)
                      @if($cards->count() > 0)
                        <option value="CARD">CARD</option>
                      @endif
                      @endif
                    </select>
                    <br>

                    @if(Setting::get('CARD') == 1)
                        @if($cards->count() > 0)
                        <select class="form-control" name="card_id" style="display: none;" id="card_id1">
                          <option value="">Select Card</option>
                          @foreach($cards as $card)
                            <option value="{{$card->card_id}}">{{$card->brand}} **** **** **** {{$card->last_four}}</option>
                          @endforeach
                        </select>
                        @endif
                    @endif

                    @if($fare->surge == 1)

                        <span><em>Note : Due to High Demand the fare may vary!</em></span>
                        <div class="surge-block"><span class="surge-text">{{$fare->surge_value}}</span>
                        </div>
                    
                    @endif

                    </div>

                    <div class="form-group col-md-4">
                        <label>Image 1</label>
                        <input type="file" accept="image/*"  class="form-control dropify" id="attachment1" name="attachment1" 
                        data-allowed-file-extensions='["png", "jpg" ,"jpeg"]'  >
                    </div>

                    <div class="form-group col-md-4">
                        <label>Product Image 2</label>
                        <input type="file" accept="image/*"  class="form-control dropify" id="attachment2" name="attachment2" 
                        data-allowed-file-extensions='["png", "jpg" ,"jpeg"]' >
                    </div>

                     <div class="form-group col-md-4">
                        <label>Product Image 3</label>
                        <input type="file" accept="image/*"  class="form-control dropify" id="attachment3" name="attachment3" 
                        data-allowed-file-extensions='["png", "jpg" ,"jpeg"]' >
                    </div>

                     <div class="form-group col-md-12">
                     
                    <button type="submit" class="half-primary-btn fare-btn">@lang('user.ride.send_package_now')</button>
                    <button type="button" class="half-secondary-btn fare-btn" data-toggle="modal" data-target="#schedule_modal">@lang('user.ride.send_package_later')</button>
                    </div>
                </form>



            </div>
            

            


            <div class="col-md-12" style="margin:25px 0px 0px 0px">
                <div class="user-request-map">
                    <?php 
                    $map_icon = asset('asset/img/marker-start.png');
                     
                    $static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=600x450&maptype=roadmap&format=png&visual_refresh=true&markers=icon:".$map_icon."%7C".$request->s_latitude.",".$request->s_longitude."&markers=icon:".$map_icon."%7C".$request->d_latitude.",".$request->d_longitude."&path=color:0x191919|weight:3|enc:".$request->polyline_data."&key=".Setting::get('map_key');

//  print_r($static_map);
                     ?>
                                        

                    {{--<div class="map-static" style="background-image: url({{$static_map}});">
                    </div>--}}

                    <img src="{{$static_map}}" alt="Girl in a jacket" width="100%" height="400px">


                    <div class="from-to row no-margin">
                        <div class="from">
                            <h5>FROM</h5>
                            <p>{{$request->s_address}}</p>
                        </div>
                        <div class="to">
                            <h5>TO</h5>
                            <p>{{$request->d_address}}</p>
                        </div>
                    </div>
                </div> 
            </div>
        </div>

    </div>
</div>



<!-- Schedule Modal -->
<div id="schedule_modal" class="modal fade schedule-modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Schedule a Ride</h4>
      </div>
      <form>
      <div class="modal-body">
        
        <label>Date</label>
        <input value="{{date('m/d/Y')}}" type="text" id="datepicker" placeholder="Date" name="schedule_date">
        <label>Time</label>
        <input value="{{date('H:i')}}" type="text" id="timepicker" placeholder="Time" name="schedule_time">

      </div>
      <div class="modal-footer">
        <button type="button" id="schedule_button" class="btn btn-default" data-dismiss="modal">Schedule Ride</button>
      </div>

      </form>
    </div>

  </div>
</div>


@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#schedule_button').click(function(){
                $("#datepicker").clone().attr('type','hidden').appendTo($('#create_ride'));
                $("#timepicker").clone().attr('type','hidden').appendTo($('#create_ride'));
                document.getElementById('create_ride').submit();
                document.getElementById('re_create_ride').submit();
               // re_create_ride
            });
        });
      function  myFunction()
        {
        document.getElementById('recreate_ride').style.display = "block";
        document.getElementById('alert alert-success').style.display = "none";
        }
         function  myFunction1()
        {
        document.getElementById('create_ride').style.display = "block";
        document.getElementById('alert alert-success').style.display = "none";
        }       
    </script>
    <script type="text/javascript">
        var date = new Date();
        date.setDate(date.getDate()-1);
        $('#datepicker').datepicker({  
            startDate: date
        });
        $('#timepicker').timepicker({showMeridian : false});
    </script>
    <script type="text/javascript">
        function card(value){
            if(value == 'CARD'){
                $('#card_id').fadeIn(300);
            }else{
                $('#card_id').fadeOut(300);
            }
        }

        function card1(value){
            if(value == 'CARD'){
                $('#card_id1').fadeIn(300);
            }else{
                $('#card_id1').fadeOut(300);
            }
        }
        $('.dropify').dropify();
    </script>
@endsection