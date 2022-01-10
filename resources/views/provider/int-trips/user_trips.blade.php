@extends('provider.layout.app')

@section('title', 'International Trips ')

@section('styles')
<style>
    select.form-control{display:inline-block}
</style>
@endsection

@section('content')
<div class="pro-dashboard-head">
    <div class="container">
        <a href="{{route('provider.profile.index')}}">Profile</a>
        <a href="{{ route('provider.documents.index') }}" class="pro-head-link">Manage Documents</a>
        <a href="{{ route('provider.location.index') }}" class="pro-head-link">Update Location</a>
        <a  class="pro-head-link active" href="javascript:void(0);" class="pro-head-link">International Trips</a>
    </div>
</div>
<div class="pro-dashboard-content gray-bg international-trips">
    <div class="profile">
        <div class="profile-content gray-bg pad50">
            <div class="container">
                <div class="col-md-12">
                    <div class="dash-content">
                        @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                        @endif

                        @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                        @endif
                        <div class="row no-margin mb-3">
                            <ul class="nav nav-pills int-trips-ul">
                                    <li class="active"><a data-toggle="pill" href="#mytrips">My Trips</a></li>
                                   <li><a data-toggle="pill" href="#int-trips">International Trips</a></li>
                                
                            </ul>
                        </div>
                        <div class="row no-margin ride-detail">

                            <div class="tab-content">
                                <div id="int-trips" class="tab-pane fade ">
                                    <div class="col-md-12">
                                        @if($int_trips)

                                        <table class="table table-condensed" style="border-collapse:collapse;">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Arrival Date</th>
                                                    <th>Amount</th>
                                                    <th>Counter Offer</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($int_trips as $trip)
                                                @if(isset($trip->bid_details->status) || @$trip->bid_details->status!='Rejected' )
                                                <tr id="int-trip-id{{$trip->id}}">
                                                    <td data-toggle="collapse" data-target="#inttrip_{{$trip->id}}" class="accordion-toggle collapsed" style="cursor: pointer;"><i style=" color: #777; font-size: 16px;" class="fa fa-plus-square"></i></td>
                                                    <td>{{$trip->tripfrom}}</td>
                                                    <td>{{$trip->tripto}}</td>
                                                    <td>{{date('d M Y',strtotime($trip->arrival_date))}}</td>
                                                    <td>{{$trip->trip_amount.Setting::get('currency','$')}}</td>
                                                    <td>{{isset($trip->bid_details->is_counter)&& $trip->bid_details->is_counter==1?$trip->bid_details->counter_amount.Setting::get('currency','$'):'N/A'}}</td>
                                                    <td>{{$trip->trip_status}}</td>
                                                    <td>
                                                        @if(!isset($trip->bid_details->id))   
                                                        <a href="{{route('provider.international-trip.bid.add',$trip->id)}}" class="btn btn-primary"><i style="color: #fff;" class="fa fa-plus"></i> Add Bid </a>
                                                        @elseif(isset($trip->bid_details->is_counter) && $trip->bid_details->is_counter==1&& $trip->bid_details->status=='Pending' )
                                                        <a href="{{route('provider.bid.counter.accept',['trip_id'=>$trip->id,'bid_id'=>$trip->bid_details->id])}}" class="btn btn-success"><i style="color: #fff;" class="fa fa-check"></i> Accept Counter Offer </a> <br>
                                                        <a href="{{route('provider.bid.counter.reject',['trip_id'=>$trip->id,'bid_id'=>$trip->bid_details->id])}}" onclick="return alert('Are you Sure to reject the counter offer? This will cancel your bid.');" class="btn btn-danger" style="margin-top: 5px;"><i style="color: #fff;" class="fa fa-ban"></i> Reject Counter Offer </a>
                                                        @else
                                                        <a class="btn btn-primary disabled"> Waiting For Approval</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr class="hiddenRow">

                                                    <td colspan="7">
                                                        <div class="accordian-body collapse row" id="tinttrip_{$trip->id}}">
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
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                                @endforeach


                                            </tbody>
                                        </table>
                                        @else
                                        <hr>
                                        <p style="text-align: center;">No trips Available</p>
                                        @endif
                                    </div>
                                </div>

                                <div id="mytrips" class="tab-pane fade in active">
                                    <div class="col-md-12">
                                        @if(!$my_trips->isEmpty())
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
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($my_trips as $trip)

                                                <tr >
                                                    <td data-toggle="collapse" data-target="#trip_{{$trip->id}}" class="accordion-toggle collapsed" style="cursor: pointer;"><i style=" color: #777; font-size: 16px;" class="fa fa-plus-square"></i></td>
                                                    <td>{{$trip->tripfrom}}</td>
                                                    <td>{{$trip->tripto}}</td>
                                                    <td>{{date('d M Y',strtotime($trip->arrival_date))}}</td>
                                                    <td>{{$trip->trip_amount?$trip->trip_amount.Setting::get('currency','$'):'N/A'}}</td>
                                                    <td>{{$trip->trip_status}}</td>
                                                    <td>
                                                        @if($trip->trip_status=='SCHEDULED' && date('d M Y')>=date('d M Y',strtotime($trip->arrival_date)) && $trip->service_type=='By Road')
                                                        <a href="{{route('provider.international-trip.update-status',['trip_status'=>'STARTED','trip_id'=>$trip->id])}}" class="btn btn-primary">Start Trip </a>
                                                        @elseif($trip->trip_status=='SCHEDULED' && date('d M Y')>=date('d M Y',strtotime($trip->arrival_date)) && $trip->service_type=='By Air')
                                                        <a data-trip='{{$trip->id}}' href="#" class="btn btn-primary" id="btnAddTripFlightInfo">Start Trip </a>
                                                        @elseif($trip->trip_status=='SCHEDULED' && date('d M Y')>=date('d M Y',strtotime($trip->arrival_date)) && $trip->service_type=='By Sea')
                                                        <a data-trip='{{$trip->id}}' href="#" class="btn btn-primary" id="btnAddSeeTripCarrierInfo">Start Trip </a>
                                                        @elseif($trip->trip_status=='STARTED')
                                                        <a href="{{route('provider.international-trip.update-status',['trip_status'=>'ARRIVED','trip_id'=>$trip->id])}}" class="btn btn-primary"> Arrived </a>
                                                        @elseif($trip->trip_status=='ARRIVED')
                                                        <a href="{{route('provider.international-trip.update-status',['trip_status'=>'PICKEDUP','trip_id'=>$trip->id])}}" class="btn btn-primary"> Picked up</a>
                                                        @elseif($trip->trip_status=='PICKEDUP')
                                                        <a href="{{route('provider.international-trip.update-status',['trip_status'=>'DROPPED','trip_id'=>$trip->id])}}" class="btn btn-primary"> Dropped </a>
                                                        @elseif($trip->trip_status=='DROPPED')
                                                        <a href="{{route('provider.international-trip.update-status',['trip_status'=>'COMPLETED','trip_id'=>$trip->id])}}" class="btn btn-primary"> Complete </a>
                                                        @elseif($trip->trip_status=='COMPLETED' && $trip->provider_rated == 0)
                                                        <a data-trip='{{$trip->id}}' href="javascript:void(0);" class="rate-user-trip btn btn-success">Rate Trip </a>
                                                        @elseif($trip->trip_status=='COMPLETED' && $trip->provider_rated == 1)
                                                        <strong class="disabled btn btn-warning">Rated </strong>
                                                        @elseif($trip->trip_status=='PENDING' && $trip->created_by == 'provider' && !$trip->bids )
                                                        No Bids Received
                                                        @elseif($trip->trip_status=='PENDING' && $trip->created_by == 'provider' && $trip->bids )
                                                        <a href="{{route('provider.international-trip.bids',$trip->id)}}" class="btn btn-primary"><i style="color: #fff;" class="fa fa-eye"></i> View Bids </a>
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
                                                        </div>
                                                    </td>
                                                </tr>

                                                @endforeach


                                            </tbody>
                                        </table>
                                        @else
                                        <hr>
                                        <p style="text-align: center;">No trip available</p>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
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
                    <form method="post" action="{{route('provider.international-trip.rate-user')}}" id="rating-form">
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

    <!-- Trip Type See take carrier Info from provider like, vessel id, souce port, destination port start  -->

    <div class="modal fade" id="modalSeeTripCarrierInfo" tabindex="-1" aria-labelledby="modalSeeTripCarrierInfoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSeeTripCarrierInfoLabel">Trip Carrier Info</h5>

                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('provider.international-trip.service-type-see.start')}}" id="formSeaTripStart">
                        {{-- <form method="post" action="{{route('provider.test')}}" id="formSeaTripStart"> --}}

                        {{ csrf_field() }}
                        {{-- @csrf --}}
                        <div class="row col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vessels" class="col-form-label">Vessel:</label>
                                    <select id="vessels" required="" name="vessel_id" class="form-control" placeholder="Select Vessel">
                                       
                                    </select>
                                </div>
                            </div>

                        </div>
                        
                        
                        <div class="form-group" style="display: block; text-align: right; margin-bottom: 5px; margin-right: 8%;">
                            <button type="submit" class="btn btn-primary">Start</button>
                        </div>
                        <input type="hidden" value=""  name="trip_id" id="trip_id"> 
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Trip Type See take carrier Info from provider like, vessel id, souce port, destination port end  -->


    
    <!-- Trip Flight Info from provider like, flight no, airport, departure time end  -->

    <div class="modal fade" id="modalFlightInfo" tabindex="-1" aria-labelledby="modalFlightInfoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFlightInfoLabel">Trip Flight Info</h5>

                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('provider.international-trip.service-type-air.start')}}" id="formAirTripStart">
                        {{-- <form method="post" action="{{route('provider.test')}}" id="formSeaTripStart"> --}}

                        {{ csrf_field() }}
                        {{-- @csrf --}}
                        <div class="row col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="airport" class="col-form-label">Airport:</label>
                                    <select id="airport" required="" name="airport" class="form-control" placeholder="Select  Airport">
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="flight_no" class="col-form-label">Flight No:</label>
                                    <input type="text"   required name="flight_no" id="flight_no" class="form-control">
                                </div>
                            </div>

                        </div>
                        
                        
                        <div class="form-group" style="display: block; text-align: right; margin-bottom: 5px; margin-right: 8%;">
                            <button type="submit" class="btn btn-primary">Start</button>
                        </div>
                        <input type="hidden" value=""  name="trip_id" id="trip_id"> 
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Trip Flight Info from provider like, flight no, airport, departure time end  -->


</div>
<script src="/asset/js/jquery.min.js"></script>
<script type="text/javascript">
    $('.rate-user-trip').click(function () {
        $("#rating-form #trip_id").val($(this).data('trip'));
        $("#inttripmodal").modal('show');
    });

    // $('#btnAddSeeTripCarrierInfo').click(function () {
    //     $("#formSeaTripStart #trip_id").val($(this).data('trip'));
    //     $("#modalSeeTripCarrierInfo").modal('show');


    //     $("#source_ports").select2({
    //     dropdownParent: $('#modalSeeTripCarrierInfo'),
    //      width: '100%',
    //     ajax: {
    //      url: "ports",
    //      type: "get",
    //      dataType: 'json',
    //      delay: 250,
    //      data: function (params) {
    //       return {
    //         searchTerm: params.term // search term
    //       };
    //      },
    //      processResults: function (response) {

    //        return {
    //           results: $.map(response.portsList, function (obj) {
    //             return {
    //                 text: obj.name,
    //                 id: obj.marine_traffic_id
    //            }
    //           }),
    //        }
    //      },
    //      cache: true
    //     },
    //     // formatResult: FormatResult,
    //    });


    //    $("#destination_ports").select2({
    //     dropdownParent: $('#modalSeeTripCarrierInfo'),
    //      width: '100%',
    //     ajax: {
    //      url: "ports",
    //      type: "get",
    //      dataType: 'json',
    //      delay: 250,
    //      data: function (params) {
    //       return {
    //         searchTerm: params.term // search term
    //       };
    //      },
    //      processResults: function (response) {

    //        return {
    //           results: $.map(response.portsList, function (obj) {
    //             return {
    //                 text: obj.name,
    //                 id: obj.marine_traffic_id
    //            }
    //           }),
    //        }
    //      },
    //      cache: true
    //     },
    //     // formatResult: FormatResult,
    //    });


    //    $("#vessels").select2({
    //     dropdownParent: $('#modalSeeTripCarrierInfo'),
    //      width: '100%',
    //     ajax: {
    //      url: "vessels",
    //      type: "get",
    //      dataType: 'json',
    //      delay: 250,
    //      data: function (params) {
    //       return {
    //         searchTerm: params.term // search term
    //       };
    //      },
    //      processResults: function (response) {

    //        return {
    //           results: $.map(response.vesselsList, function (obj) {
    //             return {
    //                 text: obj.name,
    //                 id: obj.marine_traffic_id
    //            }
    //           }),
    //        }
    //      },
    //      cache: true
    //     },
    //     // formatResult: FormatResult,
    //    });



    //    function FormatResult(item) {
    //     var markup = "";
    //     if (item.name !== undefined) {
    //         markup += "<option value='" + item.id + "' title='"+item.id+"'>" + item.name + "</option>";
    //     }
    //     return markup;
    // }

    // });


    $('#btnAddSeeTripCarrierInfo').click(function () {

        $("#formSeaTripStart #trip_id").val($(this).data('trip'));
        $("#modalSeeTripCarrierInfo").modal('show');
        $("#vessels").select2({
        dropdownParent: $('#modalSeeTripCarrierInfo'),
         width: '100%',
         minimumInputLength: 4,
        ajax: {
         url: "vessels",
         type: "get",
         dataType: 'json',
         delay: 250,
         data: function (params) {
          return {
            searchTerm: params.term // search term
          };
         },
         processResults: function (response) {
             //console.log(response);
           return {
              results: $.map(response, function (obj) {
                  console.log(obj);
                return {
                    text: obj.name,
                    id: obj.marine_traffic_id
               }
               
              }),
           }
         },
         cache: true
        },
        // formatResult: FormatResult,
       });
       function FormatResult(item) {
           console.log(item);
        var markup = "";
        if (item.name !== undefined) {
            markup += "<option value='" + item.id + "' title='"+item.id+"'>" + item.name + "</option>";
        }
        return markup;
    }

    });

    $('#btnAddTripFlightInfo').click(function () {

        $("#formAirTripStart #trip_id").val($(this).data('trip'));
        $("#modalFlightInfo").modal('show');


        $("#airport").select2({
        dropdownParent: $('#modalFlightInfo'),
         width: '100%',
         minimumInputLength: 4,
        ajax: {
         url: "airports",
         type: "get",
         dataType: 'json',
         delay: 250,
         data: function (params) {
          return {
            searchTerm: params.term // search term
          };
         },
         processResults: function (response) {
                    //$("ident").value = obj.ident
           return {
              results: $.map(response, function (obj) {
                 
                
                //document.getElementById("ident").value = obj.ident;
return {
                    text: obj.name,
                    id: obj.iata_code,
                    ident: obj.ident
               }
              }),
           }
         },
         cache: true
        },
        // formatResult: FormatResult,
       });

       function FormatResult(item) {
        var markup = "";
        if (item.name !== undefined) {
           
            markup += "<option value='" + item.id + "' title='"+item.id+"'>" + item.name + "</option>";
            //$("ident").value = item.ident;
        }
        return markup;
    }

    });



    
</script>

@endsection