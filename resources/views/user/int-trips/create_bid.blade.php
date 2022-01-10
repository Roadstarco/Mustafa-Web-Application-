@extends('user.layout.base')

@section('title', 'Trip Bids')

@section('content')

<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">Trip Details</h4>
            </div>
        </div>
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
        <hr>
        @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
        @endif
        <div class="row no-margin ride-detail">

            <div class="col-md-12">

                <h4 class="page-title">Send Bid Request</h4>
                <form action="{{route('international-trip.bid.store')}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
   <input type="hidden" name="trip_id" value="{{$trip->id}}">

                    <div class="form-group col-md-6">
                        <label>Parcel Name</label>
                        <input type="text" name="item" required="" id="item" value="{{ old('item') }}" class="form-control"/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="item_size">
                            Item Size
                        </label>
                        <select id="item_size" required="" name="item_size" class="form-control" placeholder="Select Item Size">
                            <option value="">Select Item Size</option>
                            <option value="Small" {{ old('item_size')=='Small'?"selected":"" }}>Small</option>
                            <option value="Medium" {{ old('item_size')=='Medium'?"selected":"" }}>Medium</option>
                            <option value="Large" {{ old('item_size')=='Large'?"selected":"" }}>Large</option>
                            <option value="Extra Large" {{ old('item_size')=='Extra Large'?"selected":"" }}>Extra large</option>
                        </select>
                    </div>
                    <div class=" form-group col-md-6" >
                        <label  for="service_type">
                            Item Type
                        </label>
                        <select id="item_type" name="item_type" required="" class="form-control">
                            <option value="">Select Item Type</option>
                            <option value="Item to be bought" {{ old('service_type')=='Item to be bought'?"selected":"" }}>Item to be bought</option>
                            <option value="Personal item" {{ old('service_type')=='Personal item'?"selected":"" }}>Personal item</option>
                        </select>

                    </div>
                    <div class=" form-group col-md-6" >
                        <label>Amount</label>
                        <input type="number" min="1" name="amount" id="amount" value="{{ old('amount') }}" class="form-control"/>

                    </div>
                    <div class=" form-group col-md-12" >
                        <label  for="other_information">
                            Other Information
                        </label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>

                    </div>

                    <div class="form-group col-md-12" >
                        <label class="col-md-12" style="float: left">Upload Images</label>
                        <div class="col-md-4">
                            <label style="font-size: 12px; ">Picture 1</label>
                            <input type="file"  name="picture1" class="form-control" accept="image/*" />
                        </div>
                        <div class="col-md-4">
                            <label style="font-size: 12px; ">Picture 2</label>
                            <input type="file"  name="picture2" class="form-control" accept="image/*" />
                        </div>
                        <div class="col-md-4">
                            <label style="font-size: 12px; ">Picture 3</label>
                            <input type="file"  name="picture3" class="form-control" accept="image/*" />
                        </div>
                    </div>
                    <div class=" form-group col-md-6" >
                        <label>Receiver Name</label>
                        <input type="receiver_name"  name="receiver_name" id="receiver_name" value="{{ old('receiver_name') }}" class="form-control"/>
                    </div>
                    <div class=" form-group col-md-6" >
                        <label>Receiver Phone</label>
                        <input type="receiver_phone"  name="receiver_phone" id="receiver_name" value="{{ old('receiver_phone') }}" class="form-control"/>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="form-sub-btn big pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
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

    });


}
</script>
@endsection