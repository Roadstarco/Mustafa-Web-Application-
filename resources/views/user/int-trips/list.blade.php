@extends('user.layout.base')

@section('title', 'International Trips ')

@section('content')

<div class="col-md-9">
    <div class="dash-content">
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title display-inline">International Trips</h4>
                <a href="{{url('international-trip/create')}}" class="btn btn-success pull-right"><i style="color: #fff;" class="fa fa-plus"></i> Add Trip</a>
            </div>

        </div>

        <div class="row no-margin ride-detail">
            <div class="col-md-12">
                @if(!isset($user_trips->error))

                <table class="table table-condensed" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Arrival Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
<!--                            <th>Receiver Name</th>
                            <th>Mobile</th>
                            
                            <th>Item</th>
                            <th>Size</th>
                            <th>Service Type</th>-->
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($user_trips as $trip)

                        <tr >
                            <td data-toggle="collapse" data-target="#trip_{{$trip->id}}" class="accordion-toggle collapsed" style="cursor: pointer;"><i style=" color: #777; font-size: 16px;" class="fa fa-plus-square"></i></td>
                            <td>{{$trip->tripfrom}}</td>
                            <td>{{$trip->tripto}}</td>
                            <td>{{date('d M Y',strtotime($trip->arrival_date))}}</td>
                            <td>{{$trip->trip_amount?$trip->trip_amount.Setting::get('currency','$'):'N/A'}}</td>
                            <td>{{$trip->trip_status}}</td>
                            <td>
                                @if($trip->trip_status=='PENDING')
                                <a href="{{route('international-trip.bids',$trip->id)}}" class="btn btn-primary"><i style="color: #fff;" class="fa fa-eye"></i> View Bids </a>
                                @elseif($trip->trip_status=='COMPLETED' && $trip->user_rated == 0)
                                <a data-trip='{{$trip->id}}' href="javascript:void(0);" class="rate-user-trip btn btn-success">Rate Trip </a>
                                @elseif($trip->trip_status=='COMPLETED' && $trip->user_rated == 1)
                                <strong class="disabled btn btn-warning">Rated </strong>
                                @else
                                N/A
                                @endif                                                       
                            </td>
                        </tr>
                        <tr class="hiddenRow">

                            <td colspan="7">
                                <div class="accordian-body collapse row" id="trip_{{$trip->id}}">
                                    <div class="col-md-3 my-trip-left">

                                        <div class="from-to row">
                                            <div class="from">
                                                <h5>Receiver Name</h5>
                                                <p>{{$trip->receiver_name}}</p>
                                            </div>
                                            <div class="to">
                                                <h5>Phone</h5>
                                                <p>{{$trip->receiver_phone}}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 my-trip-left">

                                        <div class="from-to row ">
                                            <div class="from">
                                                <h5>Item</h5>
                                                <p>{{$trip->item}}</p>
                                            </div>
                                            <div class="to">
                                                <h5>Item Type</h5>
                                                <p>{{$trip->item_type}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 my-trip-left">

                                        <div class="from-to row ">
                                            <div class="from">
                                                <h5>Item Size</h5>
                                                <p>{{$trip->item_size}}</p>
                                            </div>
                                            <div class="to">
                                                <h5>Service</h5>
                                                <p>{{$trip->service_type ?$trip->service_type:'N/A'}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 my-trip-left">
                                        @if ($trip->service_type && $trip->service_type =='By Road')
                                            <div class="from-to row ">
                                                <div class="from">
                                         <!--           <h5>Attention!</h5>
                                                    <small>Package tracking use's expensive api's behind the scene so it will charge you almost 1$ per 3 tracking calls</small>-->
                                                </div>
                                                <div class="to">
                                                    <a data-trip='{{$trip->id}}' href="javascript:void(0);" id="btnTrackRoadPosition" class="btn btn-info btnTrackRoadPosition">Track Your Package </a>
                                                </div>

                                            </div> 
                                        @endif                                        
                                    <div class="col-md-3 my-trip-left">
                                        @if ($trip->service_type && $trip->service_type=='By Sea')
                                            <div class="from-to row ">
                                                <div class="from">
                <!--                                    <h5>Attention!</h5>
                                                    <small>Package tracking use's expensive api's behind the scene so it will charge you almost 1$ per 3 tracking calls</small> -->
                                                </div>
                                                <div class="to">
                                                    <a data-trip='{{$trip->id}}' href="javascript:void(0);" id="btnTrackVesselPosition" class="btn btn-info btnTrackVesselPosition">Track Your Package </a>
                                                </div>

                                            </div> 
                                        @endif
                                        @if ($trip->service_type && $trip->service_type=='By Air')
                                            <div class="from-to row ">
                                                <div class="from">
                         <!--                           <h5>Attention!</h5>
                                                    <small>Package tracking use's expensive api's behind the scene so it will charge you almost 1$ per 3 tracking calls</small> -->
                                                </div>
                                                <div class="to">
                                                    <a data-trip='{{$trip->id}}' href="javascript:void(0);" id="btnTrackFlight" class="btn btn-info btnTrackFlight">Track Your Package </a>
                                                </div>

                                            </div> 
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>

                        @endforeach


                    </tbody>
                </table>
                @else
                <hr>
                <p style="text-align: center;">No trips Available</p>
                @endif
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="inttripmodal" tabindex="-1" aria-labelledby="inttripmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inttripmodalLabel">Rate Trip</h5>

            </div>
            <div class="modal-body">
                <form method="post" action="{{route('international-trip.rate-user')}}" id="rating-form">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Comment:</label>
                        <textarea class="form-control" name="comment" id="message-text"></textarea>
                    </div>
                    <div class="form-group" style="display: inline-block;">
                        <label class="col-form-label" style="float: left;margin-top: 13px;">Rating</label>
                        <div class="rate">
                            <input type="radio" id="star5" name="rating" value="5" required=""/>
                            <label for="star5" title="text">5 stars</label>
                            <input type="radio" id="star4" name="rating" value="4" />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star3" name="rating" value="3" />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star2" name="rating" value="2" />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star1" name="rating" value="1" />
                            <label for="star1" title="text">1 star</label>
                        </div>
                    </div>
                    <div class="form-group" style="display: block; text-align: center; margin-bottom: 5px;">
                        <button type="submit" class="btn btn-primary">Rate</button>
                    </div>
                    <input type="hidden" value=""  name="trip_id" id="trip_id"> 
                </form>
            </div>

        </div>
    </div>
</div>

    <!--  Model  Vessel Position  Start   -->

    <div class="modal fade" id="modalVesselPosition" tabindex="-1" aria-labelledby="modalVesselPositionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVesselPosition">Package Current Position</h5>
    
                </div>
                <div class="modal-body">
                    <div class="row" style="margin: 25px 0px 0px 0px;">

                        <div class="col-md-12">
                               <div class="map-responsive">
                                   <div id="map" style="width: 100%; height: 400px;"></div>
                               </div> 
                           </div>
               
                    </div>

                </div>
    
            </div>
        </div>
    </div>

        <!--  Model  Vessel Position  End   -->

    <!--  Model  Flight Info Start   -->

    <div class="modal fade" id="modalFlightInfo" tabindex="-1" aria-labelledby="modalFlightInfoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFlightInfo">Package Current Position</h5>
    
                </div>
                <div class="modal-body">
                    <div class="row" style="margin: 25px 0px 0px 0px;">

                        <div class="col-md-12" id="flightEnRouteMap" style="width: 100%; height: 400px;">
                        </div>
               
                    </div>

                </div>
    
            </div>
        </div>
    </div>



        <!--  Model  Flight Info  End   -->

        <!--   MODEL ROAD POSITION.  -->
        <div class="modal fade" id="modalRoadInfo" tabindex="-1" aria-labelledby="modalRoadInfoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRoadInfo">Package Current Position</h5>
    
                </div>
                <div class="modal-body">
                    <div class="row" style="margin: 25px 0px 0px 0px;">

                        <div class="col-md-12" id="RoadEnRouteMap" style="width: 100%; height: 400px;">
                        </div>
               
                    </div>

                </div>
    
            </div>
        </div>
    </div>

<script src="/asset/js/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key') }}&libraries=places" async defer></script>
<script type="text/javascript" src="{{asset('main/vendor/flights-map-master/src/lib/ammap/ammap.js')}}"></script>
<script type="text/javascript">


$('.rate-user-trip').click(function () {
    $("#rating-form #trip_id").val($(this).data('trip'));
    $("#inttripmodal").modal('show');
});

/*
|--------------------------------------------------------------------------
| Track Vessel Position
|--------------------------------------------------------------------------
|
| This URL is used to track the current vessel position and make charge against
| the 1$ charge per 3 tracks, and return response as json
|
*///btnTrackRoadPosition

$('.btnTrackRoadPosition').click(function () {
    $tripId = ($(this).data('trip'));

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var data={'trip_id' : $tripId };
    $.ajax({
            url: '/track-vessel1',
            type: 'get',
            data: data ,
            success: (response) => {
                console.log(response);
                if(response.card_not_found)
                {
                    alert(response.card_not_found);
                    return;
                }
                if(response.error)
                {
                    alert(response.error);
                    return;
                }

                drawRoadRoute(response);
                $("#modalRoadInfo").modal('show');
                //initMap(Number(response.current_latitude), Number(response.current_longitude));  
            },
            error: (errorResponse) => {

                // console.log(errorResponse.responseJSON.errors);

            }
        });

});


//---------------------
$('#btnTrackVesselPosition').click(function () {
    $tripId = ($(this).data('trip'));

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var data={'trip_id' : $tripId };
    $.ajax({
            url: '/track-vessel1',
            type: 'get',
            data: data ,
            success: (response) => {
                console.log(response);
                if(response.card_not_found)
                {
                    alert(response.card_not_found);
                    return;
                }
                if(response.error)
                {
                    alert(response.error);
                    return;
                }

            
                $("#modalVesselPosition").modal('show');
                initMap(Number(response.current_latitude), Number(response.current_longitude));  
            },
            error: (errorResponse) => {

                // console.log(errorResponse.responseJSON.errors);

            }
        });

});
function initMap(current_latitude, current_longitude) {

var map = new google.maps.Map(document.getElementById('map'), {
    mapTypeControl: false,
    zoomControl: true,
    center: {lat: current_latitude, lng: current_longitude},
    zoom: 6,
    styles : [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#e4e8e9"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#7de843"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#9bd0e8"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]
});

var marker = new google.maps.Marker({
            map: map,
            icon: '/asset/img/20-x-20-ship.png',
            // anchorPoint: new google.maps.Point(0, -29),
            // scaledSize: new google.maps.Size(30, 30), 
        });

        markerPosition = new google.maps.LatLng(current_latitude, current_longitude);

        marker.setPosition(markerPosition);

}

/*
|--------------------------------------------------------------------------
| Track Flight
|--------------------------------------------------------------------------
|
| This URL is used to track the flight  and make charge against
| the 1$ charge per 3 tracks, and return response as json
|
*/

$('.btnTrackFlight').click(function () {
    $tripId = ($(this).data('trip'));
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var data={'trip_id' : $tripId };
    $.ajax({
            url: '/track-vessel1',
            type: 'get',
            data: data ,
            success: (response) => {
                console.log(response);
                
                if(response.error)
                {
                    alert(response.error);
                    return;
                }
                

            
                drawFlightRoute(response);
                $("#modalFlightInfo").modal('show');
            },
            error: (errorResponse) => {

                // console.log(errorResponse.responseJSON.errors);

            }
        });

});


function drawFlightRoute(flightData) { 
        // var map = new google.maps.Map(document.getElementById('flightEnRouteMap'), {
        // zoom: 4,
        // center: {
        //     lat: Number(flightData.origin_airport_lat),
        //     lng: Number(flightData.origin_airport_long)
        // },
        // mapTypeId: google.maps.MapTypeId.TERRAIN
        // });

        // var Lat = [flightData.origin_airport_lat, flightData.destination_airport_lat];
        // var Lng = [flightData.origin_airport_long, flightData.destination_airport_long];

        // alert(flightData.origin_airport_lat);
        // var lineSymbol = {
        // path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
        // };

        // var Poly = new Array();
        // for (var i = 0; i < Lat.length; i++) {
        // var pos = new google.maps.LatLng(Lat[i], Lng[i]);
        // Poly.push(pos);
        // }
        // var flowline = new google.maps.Polyline({
        // path: Poly,
        // geodesic: false,
        // strokeColor: "#00FF00",
        // strokeOpacity: .8,
        // strokeWeight: 2,
        // map: map
        // });
        // for (var j = 0; j < Poly.length; j++) {
        // if (j % 2 == 0) {
        //     var poly = Poly.slice(j, j + 2);
        //     var flowline = new google.maps.Polyline({
        //     map: map,
        //     path: poly,
        //     geodesic: true,
        //     strokeColor: "#DC143C",
        //     strokeOpacity: .8,
        //     strokeWeight: 2,
        //     icons: [{
        //         icon: lineSymbol,
        //         offset: '100%'
        //     }],
        //     });
        // }
        // };

        // flowline.setMap(map);
        const map = new google.maps.Map(document.getElementById("flightEnRouteMap"), {
                    center: { lat: Number(flightData.current_latitude), lng: Number(flightData.current_longitude) },
                    zoom: 2,
                    mapTypeId: "terrain",
        });

        //source marker
        // new google.maps.Marker({
        // position: { lat: Number(flightData.origin_airport_lat), lng: Number(flightData.origin_airport_long) },
        // map,
        // icon : '/asset/img/marker-start.png',
        // title: flightData.origin_airport_name
        
        // });

        //destination marker
        // new google.maps.Marker({
        // position: { lat: Number(flightData.destination_airport_lat), lng: Number(flightData.destination_airport_long) },
        // map,
        // icon : '/asset/img/marker-end.png',
        // title: flightData.destination_airport_name
        // });

        //flight actual lat lonng  marker

        var icon = {
        url: "/asset/img/plane-icon.png", 
        scaledSize: new google.maps.Size(30, 30), // size
    };

        new google.maps.Marker({
        position: { lat: Number(flightData.current_latitude), lng: Number(flightData.current_longitude) },
        map,
        icon : icon,
        });
 }


 function drawRoadRoute(flightData) { 
        
        const map = new google.maps.Map(document.getElementById("RoadEnRouteMap"), {
                    center: { lat: Number(flightData.current_latitude), lng: Number(flightData.current_longitude) },
                    zoom: 15,
                    mapTypeId: "terrain",
        });


        var icon = {
        url: "/asset/img/cars/car.png", 
        scaledSize: new google.maps.Size(30, 30), // size
    };

        new google.maps.Marker({
        position: { lat: Number(flightData.current_latitude), lng: Number(flightData.current_longitude) },
        map,
        icon : icon,
        });
 }

</script>
@endsection