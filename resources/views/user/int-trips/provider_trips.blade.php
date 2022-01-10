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
                <h4 class="page-title display-inline">Providers International Trips</h4>
            </div>
        </div>

        <div class="row no-margin ride-detail">
            <div class="col-md-12">
                @if(isset($error))
                <hr>
                <p style="text-align: center;">No trips Available</p>
                
                 @else
                <hr>
                <table class="table table-condensed" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Arrival Date</th>
                            <th>Item Size</th>
                            <th>Service</th>
                            <th>Counter Offer</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($provider_trips as $trip)
                        <tr >
                            <td>{{$trip->tripfrom}}</td>
                            <td>{{$trip->tripto}}</td>
                            <td>{{date('d M Y',strtotime($trip->arrival_date))}}</td>
                            <td>{{$trip->item_size?$trip->item_size:'N/A'}}</td>
                            <td>{{$trip->service_type?$trip->service_type:'N/A'}}</td>
                                <td>{{isset($trip->bid_details->is_counter)&& $trip->bid_details->is_counter==1?"You've recieved a counter offer of ".$trip->bid_details->counter_amount.Setting::get('currency','$'):'N/A'}}</td>
                                                
                            <td>
                                @if(!isset($trip->bid_details->id))    
                                <a href="{{route('international-trip.bid.add',['trip_id'=>$trip->id])}}" class="btn btn-primary"><i style="color: #fff;" class="fa fa-plus"></i> Send Bid </a>
                                @elseif(isset($trip->bid_details->id) && $trip->bid_details->is_counter==1 && $trip->bid_details->status=='Pending' )
                                <a href="{{route('bid.counter.accept',['trip_id'=>$trip->id,'bid_id'=>$trip->bid_details->id])}}" class="btn btn-success"><i style="color: #fff;" class="fa fa-check"></i> Accept Counter Offer </a> <br>
                                <a href="{{route('bid.counter.reject',['trip_id'=>$trip->id,'bid_id'=>$trip->bid_details->id])}}" onclick="return alert('Are you Sure to reject the counter offer? This will cancel your bid.');" class="btn btn-danger" style="margin-top: 5px;"><i style="color: #fff;" class="fa fa-ban"></i> Reject Counter Offer </a>
                                @else
                                <a class="btn btn-primary disabled"> Waiting For Approval</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
<script src="/asset/js/jquery.min.js"></script>
<script type="text/javascript">
                                    $('.rate-user-trip').click(function () {
                                        $("#rating-form #trip_id").val($(this).data('trip'));
                                        $("#inttripmodal").modal('show');
                                    });
</script>
@endsection