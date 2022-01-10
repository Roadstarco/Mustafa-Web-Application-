@extends('admin.layout.base')

@section('title', 'Update Place ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.zones.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

			<h5 style="margin-bottom: 2em;">Update Zone</h5>

            <form class="form-horizontal" action="{{route('admin.zones.update', $zone->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">@lang('admin.name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $zone->name }}" name="name" required id="name" placeholder="Name">
					</div>
				</div>
				<div class="form-group row" id="map">
					<label for="name" class="col-xs-2 col-form-label">Map</label>
					<div class="col-xs-10">
						<div style="height: 500px;" id="zones_map"></div>
						<input type="hidden" value="{{ $zone->zone_area }}" name="zone_area" id="zone_area">
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">City</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $zone->city }}" name="city" id="locality" required="required">
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">State</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $zone->state }}" name="state" id="administrative_area_level_1" required="required">
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Country</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $zone->country }}" name="country" id="country" required="required">
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Currency</label>
					<div class="col-xs-10">
						<select name="currency" id="currency" class="form-control" required>
							<option  selected value="$">US Dollar (USD)</option>
							<option  value="£">British Pound (GBP)</option>
							<option  value="€">Euro (EUR)</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Status</label>
					<div class="col-xs-10">
						<select name="status" id="status" class="form-control" required>
							@if ($zone->status  == "active")
								<option selected value="active">Active</option>
								<option  value="inactive">Inactive</option>
							@else
								<option value="active">Active</option>
								<option selected value="inactive">Inactive</option>
							@endif
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Update Place</button>
						<a href="{{route('admin.zones.index')}}" class="btn btn-default">@lang('admin.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

<script>
	var map;
	var Coordinates = JSON.parse("{{$zone->zone_area}}".split("&quot;").join('"'));

	function initGoogleMap() {

		map = new google.maps.Map(document.getElementById('zones_map'), {
			zoom: 14,
			center: Coordinates[0],
			mapTypeId: 'roadmap'
		});

		// Draw Zone Function
		polylineDrawing();
	}


	// Draw Zone Function
	function polylineDrawing() {

		var drawingManager = new google.maps.Polyline({
			path: Coordinates,
			geodesic: true,
			strokeColor: '#000000',
			fillColor: '#abd3f7',
			fillOpacity: 1,
			strokeWeight: 3,
			editable: true

		});
		drawingManager.setMap(map);

		google.maps.event.addListener(drawingManager.getPath(), 'set_at', function(polyline) {
			document.getElementById("zone_area").value = JSON.stringify(drawingManager.getPath().getArray());
		});
	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4B2q5kad51qSGEuWPK9tW0T-5YoWTuUs&libraries=places,drawing&callback=initGoogleMap" async defer></script>

@endsection
