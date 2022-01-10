@extends('provider.layout.app')

@section('title', 'International Trips ')

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
                        <div class="row no-margin">
                            <div class="col-md-12">
                                <h4 class="page-title">Trip Details</h4>
                            </div>
                        </div>
                        @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                        @endif
                        <div class="row no-margin ride-detail">
                            <div class="item item-travel">

                                <div class="description">
                                    <div class="infos">
                                        <span class="date">
                                            <i class="fa fa-calendar"></i> 
                                            <strong>
                                                {{ date('d M Y',strtotime($trip->arrival_date)) }}
                                            </strong>
                                        </span>
                                        <i class="transportation fa fa-plane"></i>
                                    </div>
                                    <div class="path">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="origin">
                                                    <i class="fa fa-map-marker"></i>
                                                    <strong>
                                                        {{$trip->tripfrom}}
                                                    </strong>
                                                            <!--<span class="country">(US)</span>-->
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="steps">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="arrival">
                                                    <i class="fa fa-map-marker"></i>
                                                    {{$trip->tripto}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <h3 class="page-title">Trip Bids</h3>
                                @if(!isset($trip_bids->error))
                                <hr>
                                <table class="table table-condensed" style="border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th>Provider Name</th>
                                            <th>Service Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($trip_bids as $bid)
                                        <tr id="{{$bid->id}}">
                                            <td>{{$bid->first_name.' '.$bid->last_name}}</td>
                                            {{--<td>{{$bid->description}}</td>--}}
                                            <td>{{$bid->trip_service_type}}</td>
                                            <td>{{$bid->amount.Setting::get('currency','$')}}</td>
                                            <td>{{$bid->status}}</td>
                                            @if(\Illuminate\Support\Str::length($bid->description)<15)
                                            <td>{{ \Illuminate\Support\Str::limit($bid->description, 15, $end='') }}</td>
                                            @else
                                            <td>{{ \Illuminate\Support\Str::limit($bid->description, 15, $end='...') }}
                                             <a href="#" class="readMoreDescription text-primary">Read More</a></td>
                                            @endif
                                            <td class="" style="    display: flex;">
                                                @if($bid->status=='Approved')
                                                Approved
                                                @elseif($bid->is_counter)
                                                Your Counter Offer of&nbsp;<strong> {{ $bid->counter_amount. Setting::get('currency', '$') }} </strong>&nbsp;is sent 
                                                @else
                                                <button class="btn btn-primary send-counter" data-bid="{{$bid->id}}" style="margin-right: 5px;"> <i style="color: white;" class="fa fa-plus"></i> Offer Counter</button>
                                                <form method="POST" action="{{url('provider/bid/accept')}}">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="bid_id" value="{{$bid->id}}" />
                                                    <input type="hidden" name="trip_id" value="{{$trip->id}}" />
                                                    <button class="btn btn-success" type="submit"> <i style="color: white;" class="fa fa-check"></i> Accept Bid</button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <hr>
                                <p style="text-align: center;">No Bids Available</p>
                                @endif
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
                <h5 class="modal-title" id="inttripmodalLabel">Send Counter Offer</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="{{url('provider/bid/counter-offer')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="counter_amount" class="col-form-label">Enter Counter Amount:</label>
                        <input type="number" min="0"  required="" name="counter_amount" id="counter_amount" class="form-control" id="recipient-name">
                    </div>
                    <input type="hidden" name="bid_id" id="counter_bid_id" value=""/>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tripRequestDescriptionModal" tabindex="-1" aria-labelledby="tripRequestDescriptionModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="tripRequestDescriptionModal">
                <h5 class="modal-title" id="">Request Description</h5>
                <button type="button" class="close" data-dismiss="tripRequestDescriptionModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button
            </div>
            <div class="modal-body" id="tripRequestDescription">
               
            </div>
        </div>
    </div>
</div>
<script src="/asset/js/jquery.min.js"></script>
<script src="/asset/js/bootstrap.min.js"></script>
<script type="text/javascript">
$('.send-counter').click(function () {
    $("#counter_amount").val('');
    $("#counter_bid_id").val($(this).data('bid'));
    $("#inttripmodal").modal('show');
});

$('.readMoreDescription').on('click',function () {
    $tripRequestId=$(this).closest('tr').prop('id');
    $.ajax({
            url: 'https://myroadstar.org/provider/international-trips-requests/'+$tripRequestId,
            dataType: 'json',
            type: 'GET',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            success: function(data) {

            $("#tripRequestDescription").html(data.description);
            $("#tripRequestDescriptionModal").modal('show');
                
            },
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
});

</script>

@endsection