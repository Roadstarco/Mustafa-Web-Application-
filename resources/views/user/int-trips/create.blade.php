@extends('user.layout.base')

@section('title', 'Trip Bids')

@section('content')

<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">Create Trip</h4>
            </div>
        </div>

        <div class="row no-margin ride-detail">

            <div class="col-md-12">
                <form action="{{url('international-trip/store')}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group col-md-6">
                        <label>From</label>
                        <input type="text" onfocus="initialize(this)" id="tripfrom" name="tripfrom" required="required" placeholder="City or Country" value="{{ old('tripfrom') }}" class="form-control" />

                    </div>
                    <div class="form-group col-md-6">
                        <label>To</label>
                        <input type="text" onfocus="initialize(this)" id="tripto" name="tripto" required="required" placeholder="City or Country" class="form-control" value="{{ old('tripto') }}" />

                    </div>
                    <div class="col-md-6" >
                        <div class="form-group">
                            <label>
                                Arrival Date
                            </label>
                            <input type="date" id="arrival_date" name="arrival_date" required="required" class="form-control" value="{{old('arrival_date')?:date('Y-m-d')}}" min="{{date('Y-m-d')}}"/>

                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Item Name</label>
                        <input type="text" name="item" required="" id="item" value="{{ old('item') }}" class="form-control"/>
                    </div>
                    <div class="form-group col-md-6">

                        <label for="item_size">
                            Your Item Size
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
                        <input type="number" min="1" name="trip_amount" id="trip_amount" value="{{ old('trip_amount') }}" class="form-control"/>

                    </div>
                    <div class=" form-group col-md-6" >
                        <label  for="other_information">
                            Other Information
                        </label>
                        <textarea name="other_information" class="form-control">{{ old('other_information') }}</textarea>

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