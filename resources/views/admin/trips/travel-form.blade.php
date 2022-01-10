@extends('user.layout.app')
@section('content')
<div class="banner row no-margin">
<div class="banner-overlay" style="background-color: #ebccd1"></div>
<div id="main">
<div class="container">
<div class="row page-header-wrapper">
<div class="col-md-12">
<h1 class="page-header">Post a trip: Main Information.</h1>
</div>
</div>
<div class="row">
<div class="col-md-12">
<div class="cms-block">
<p>No&nbsp;time to post your trip?<br />
Send us your next itinerary (booking, tickets, ...) on <strong>support@myroadstar.org</strong><br />
We will post it for you!</p>
</div>
@if(Session::has('error_msg'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error_msg') }}</p>
@endif
@if(Session::has('success_msg'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_msg') }}</p>
@endif
<form action="{{ url('/trips-store') }}" method="post" id="travel-form" class="form-horizontal">
    {{csrf_field()}}
<fieldset>
<div class="row">
<div class="col-md-12">
<div class="form-group"><label class="control-label col-sm-2 required"> From </label>
<div class="col-sm-10">
<input type="text" onfocus="initialize(this)" id="tripfrom" name="tripfrom" required="required" placeholder="City or Country" class="form-control" />
<input type="hidden" id="tripfrom_lat" name="tripfrom_lat"/>
<input type="hidden" id="tripfrom_lng" name="tripfrom_lng" />
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<div class="form-group">
<label class="control-label col-sm-2 required"> To </label>
<div class="col-sm-10">
<input type="text" onfocus="initialize(this)" id="tripto" name="tripto" required="required" placeholder="City" class="form-control" />
<input type="hidden" id="tripto_lat" name="tripto_lat"/>
<input type="hidden" id="tripto_lng" name="tripto_lng"/>
</div>
</div>
</div>
</div>
</fieldset>
<div class="row">
<div class="col-sm-5" id="fixed-travel-fields">
<fieldset>
<h4 class="section-header"><span style="color: #0474fb">Fixed date trip</span> <br>I'm only making this trip once</h4>
<div class="fields-wrapper">
<div class="form-group">
<label class="control-label col-sm-4 required" for="arrival_date">
Arrival date of your trip
</label>
<div class="col-sm-8">
<input type="date" id="arrival_date" name="arrival_date" required="required" class="form-control" value="date" /></div></div>
<div class="pb-help-message child-">
</div>
<div class="form-group">
<label class="control-label col-sm-4" for="return_date">
Arrival date of your optional return trip
</label>
<div class="col-sm-8">
<input type="date" id="return_date" name="return_date" class="form-control" />
</div>
</div>
</div>
</fieldset>
</div>
<div class="col-sm-2 col-xs-12" id="or-travel-fields">
<h4 class="sub-sub-section-header text-center">OR</h4>
</div>
<div class="col-sm-5" id="reccurent-travel-fields">
<fieldset>
<h4 class="section-header"><span style="color: #0474fb">Recurring trip</span> <br>I'm making this trip multiple times</h4>
<div class="fields-wrapper">
<div class="form-group">
<label class="control-label col-sm-4">
Recurrence
</label>
<div class="col-sm-8">
<div id="recurrence">
<div class="radio">
<label for="travel_form_type_recurrence_placeholder" class="">
<input type="radio" id="travel_form_type_recurrence_placeholder" name="recurrence" value="never" checked="checked" />
Never</label>
</div>
<div class="radio">
<label for="travel_form_type_recurrence_0" class="">
<input type="radio" id="travel_form_type_recurrence_0" name="recurrence" value="rarely" />
Once in a while</label>
</div><div class="radio">
<label for="travel_form_type_recurrence_1" class="">
<input type="radio" id="travel_form_type_recurrence_1" name="recurrence" value="sometimes" />
Occasionally (several times per year)</label>
</div>
<div class="radio">
<label for="travel_form_type_recurrence_2" class="">
<input type="radio" id="travel_form_type_recurrence_2" name="recurrence" value="often" />
Often (several times per month)</label>
</div>
<div class="radio">
<label for="travel_form_type_recurrence_3" class="">
<input type="radio" id="travel_form_type_recurrence_3" name="recurrence" value="regularly" />
Regularly (several times per week)</label>
</div>
</div>
</div>
</div>

<div class="pb-help-message child-">
<i class="fa fa-info-circle pull-left"></i>
<div class="cms-block">
<p>A recurring journey will be automatically considered as a round trip</p>
</div>
</div>
</div>
</fieldset>
</div>
</div>
<div class="row">
<div class="col-md-12">
<fieldset>
<div class="form-group">
<label class="control-label col-sm-2 required" for="item_size">
Size you may transport
</label>
<div class="col-sm-10">
<select id="item_size" name="item_size" class="form-control" placeholder="Select Item Size">
<option value="">Select Item Size</option>
<option value="smal">Small</option>
<option value="medium">Medium</option>
<option value="large">Large</option>
<option value="xl">Extra large</option>
</select>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<div class="pb-help-message child-size">
<i class="fa fa-info-circle pull-left"></i>
<div class="cms-block">
<p>Small = keys. ||   Medium = phone, book, bag. ||   Large = big box, instrument.||   Extra large = vehicle, pallet</p>
</div>

</div>
</div>
</div>

</fieldset>
</div>
</div>
<div class="row" id="more-details">
<div class="col-md-12">
<fieldset>
<div class="form-group">
<label class="control-label col-sm-2" for="travel_form_type_infos">
Practical details
</label>
<div class="col-sm-10">
<textarea id="other_information" name="other_information" placeholder="Other Information" class="form-control"></textarea>
</div>
</div>
<div class="form-group">
<label class="control-label col-sm-2" for="service_type">
Service Type
</label>
<div class="col-sm-10">
<select id="service_type" name="service_type" required="" placeholder="Transportation" class="form-control">
<option value="">Select Transportation Type</option>
<option value="ground">Ground</option>
<option value="air">Air</option>
<option value="sea">Sea</option>
</select>
</div>
</div>
<div class="form-group">
<label class="control-label col-sm-2 required"> Email </label>
<div class="col-sm-10">
<input type="email" id="email" name="email" required="required" placeholder="Email" class="form-control" />
<div class="pb-help-message child-size" style="margin-top: 10px;">
<i class="fa fa-info-circle pull-left"></i>
<div class="cms-block">
<p>Enter Already Registered Email</p>
</div>
</div>
</div>
</div>
</fieldset>
</div>
</div>
<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check" style="color: white"></i> Post your trip</button>
</form>
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

});


}
</script>
@endsection