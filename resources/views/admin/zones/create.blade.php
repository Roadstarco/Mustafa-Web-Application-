@extends('admin.layout.base')

@section('title', 'Add Zone ')

@section('content')

	<style>
		.pac-card {
			margin: 10px 10px 0 0;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			outline: none;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			background-color: #fff;
			font-family: Roboto;
		}

		#pac-container {
			padding-bottom: 12px;
			margin-right: 12px;
		}


		.pac-controls label {
			font-size: 13px;
			font-weight: 300;
		}

		#pac-input {
			background-color: #fff;
			font-size: 15px;
			font-weight: 300;
			padding: 0 11px 0 11px;
			text-overflow: ellipsis;
			width: 300px;
			margin-left: 12px;
			margin-top: 12px;
		}

		#pac-input:focus {
			border-color: #4d90fe;
		}

		#title {
			color: #fff;
			background-color: #4d90fe;
			font-size: 20px;
			font-weight: 500;
			padding: 6px 12px;
		}
	</style>
	<div class="content-area py-1">
		<div class="container-fluid">
			<div class="box box-block bg-white">
				<a href="{{ route('admin.zones.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

				<h5 style="margin-bottom: 2em;">Add New Zone</h5>

				<form class="form-horizontal" action="{{route('admin.zones.store')}}" method="POST" enctype="multipart/form-data" role="form">
					{{csrf_field()}}

					<div class="form-group row">
						<label for="image" class="col-xs-12 col-form-label">Zone Name</label>
						<div class="col-xs-10">
							<div style="padding:10px">
								<input class="form-control" name="name" id="name" type="text" placeholder="Enter Zone Name" required="required">
							</div>
						</div>
					</div>
{{--					<div class="form-group row">--}}
{{--						<label for="complete_address" class="col-xs-12 col-form-label">Zone Name</label>--}}
{{--						<div class="col-xs-10">--}}
{{--							<div style="padding:10px">--}}
{{--								<input class="form-control" id="pac-input" name="complete_address" type="text" placeholder="Enter Place Name" required="required">--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					</div>--}}
					<div class="pac-card" id="pac-card">
						<div>
							<div id="title">
								Autocomplete search
							</div>
						</div>
						<div id="pac-container">
							<input id="pac-input" type="text" name="complete_address" placeholder="Enter a location">
						</div>
					</div>

					<div class="form-group row" id="map">
						<label for="name" class="col-xs-12 col-form-label">Map</label>
						<div class="col-xs-10">
							<div style="padding:10px">
								<div style="height: 500px;" id="zones_map"></div>
								<input type="hidden" name="zone_area" id="zone_area">

							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="locality" class="col-xs-12 col-form-label">City</label>
						<div class="col-xs-10">
							<div style="padding:10px">
								<input class="form-control" type="text" name="city" id="city" required="required">
							</div>
						</div>
					</div>
					<div class="form-group row" >
						<label for="state" class="col-xs-12 col-form-label">State</label>
						<div class="col-xs-10">
							<div style="padding:10px">
								<input class="form-control" type="text" name="state" id="state" required="required">
							</div>
						</div>
					</div>
					<div class="form-group row" >
						<label for="country" class="col-xs-12 col-form-label">Country</label>
						<div class="col-xs-10">
							<div style="padding:10px">
								<input class="form-control" type="text" name="country" id="country" required="required">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="image" class="col-xs-12 col-form-label">Currency</label>
						<div class="col-xs-10">
							<div style="padding:10px">
								<select name="currency" id="currency" class="form-control" required>
									<option  selected value="$">US Dollar (USD)</option>
									<option  value="£">British Pound (GBP)</option>
									<option  value="€">Euro (EUR)</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="image" class="col-xs-12 col-form-label">Status</label>
						<div class="col-xs-10">
							<div style="padding:10px">
								<select name="status" id="status" class="form-control" required>
									<option  value="active">Active</option>
									<option  value="inactive">Inactive</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="zipcode" class="col-xs-12 col-form-label"></label>
						<div class="col-xs-10">
							<button type="submit" class="btn btn-primary">Add Zone</button>
							<a href="{{route('admin.zones.index')}}" class="btn btn-default">@lang('admin.cancel')</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		// This example adds a search box to a map, using the Google Place Autocomplete
		// feature. People can enter geographical searches. The search box will return a
		// pick list containing a mix of places and predicted search terms.

		// This example requires the Places library. Include the libraries=places
		// parameter when you first load the API. For example:
		// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

		var map;
		var markers = [];

		var polylineArray = [];

		var drawingManager;

		function initGoogleMap() {

			map = new google.maps.Map(document.getElementById('zones_map'), {
				zoom: 7,
				center: {lat: 0, lng: -180},
				mapTypeId: 'roadmap'
			});

			// Place Auto Complete Function
			autocompletePlace();

			// Draw Zone Function
			callDrawingManager();
		}


		// Draw Zone Function
		function callDrawingManager() {

			drawingManager = new google.maps.drawing.DrawingManager({
				drawingControl: true,
				drawingControlOptions: {
					position: google.maps.ControlPosition.TOP_CENTER,
					drawingModes: ['polyline']
				},
				polylineOptions: {
					strokeColor: '#000000',
					fillColor: '#abd3f7',
					fillOpacity: 1,
					strokeWeight: 3,
					editable: true
				}
			});

			drawingManager.setMap(map);

			google.maps.event.addListener(drawingManager, 'polylinecomplete', function(polyline) {

				$.each(polyline.getPath().getArray(), function(key, latlng){
					polylineArray.push( latlng );
				});

				document.getElementById("zone_area").value = JSON.stringify(polylineArray);

				polyline.setMap(null);
				drawingManager.setDrawingMode(null);
				drawingManager.setMap(null);

				// Draw Zone Function
				polylineDrawing();
			});
		}



		// Draw Zone Function
		function polylineDrawing() {
			drawingManager = new google.maps.Polyline({
				path: polylineArray,
				geodesic: true,
				strokeColor: '#000000',
				fillColor: '#abd3f7',
				fillOpacity: 1,
				strokeWeight: 3,
				editable: true

			});
			drawingManager.setMap(map);

			google.maps.event.addListener(drawingManager.getPath(), 'set_at', function(polyline) {
				console.log("set_at");
				document.getElementById("zone_area").value = JSON.stringify(drawingManager.getPath().getArray());
			});
		}

		// Place Auto Complete Function
		function autocompletePlace(){
			// Create the search box and link it to the UI element.
			var card = document.getElementById('pac-card');
			var input = document.getElementById('pac-input');
			var searchBox = new google.maps.places.SearchBox(input);

			map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

			// Bias the SearchBox results towards current map's viewport.
			map.addListener('bounds_changed', function(event) {
				searchBox.setBounds(map.getBounds());
			});

			// Listen for the event fired when the user selects a prediction and retrieve
			// more details for that place.
			searchBox.addListener('places_changed', function() {
				var places = searchBox.getPlaces();

				if (places.length == 0) {
					return;
				}

				// Clear out the old markers.
				markers.forEach(function(marker) {
					marker.setMap(null);
				});
				markers = [];



				// For each place, get the icon, name and location.
				var bounds = new google.maps.LatLngBounds();
				places.forEach(function(place) {

					console.log(place)

					for (var i = 0; i < place.address_components.length; i++) {
						address = [
							(place.address_components[0] && place.address_components[0].short_name || ''),
							(place.address_components[1] && place.address_components[1].short_name || ''),
							(place.address_components[2] && place.address_components[2].short_name || ''),
						].join(' ');

						document.getElementsByName("complete_address").value = address;


						if (place.address_components[i].types[0] === 'locality') {
							document.getElementById("city").value = place.address_components[i] && place.address_components[i].short_name;
						} else if (place.address_components[i].types[0] === 'administrative_area_level_1'){
							document.getElementById("state").value = place.address_components[i] && place.address_components[i].short_name;
						} else if (place.address_components[i].types[0] === 'country'){
							document.getElementById("country").value = place.address_components[i] && place.address_components[i].long_name;
						}
					}

					if (!place.geometry) {
						console.log("Returned place contains no geometry");
						return;
					}
					var icon = {
						url: place.icon,
						size: new google.maps.Size(71, 71),
						origin: new google.maps.Point(0, 0),
						anchor: new google.maps.Point(17, 34),
						scaledSize: new google.maps.Size(25, 25)
					};

					// Create a marker for each place.
					markers.push(new google.maps.Marker({
						map: map,
						icon: icon,
						title: place.name,
						position: place.geometry.location
					}));

					if (place.geometry.viewport) {
						// Only geocodes have viewport.
						bounds.union(place.geometry.viewport);
					} else {
						bounds.extend(place.geometry.location);
					}
				});
				map.fitBounds(bounds);
			});

		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4B2q5kad51qSGEuWPK9tW0T-5YoWTuUs&libraries=places,drawing&callback=initGoogleMap" async defer></script>

@endsection