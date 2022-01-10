@extends('admin.layout.base')

@section('title', 'Zone Details')

@section('content')

	<div class="content-area py-1">
		<div class="container-fluid">
			<div class="box box-block bg-white">
				<a href="{{ route('admin.user.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

				<h5 style="margin-bottom: 2em;">Zone Detail</h5>



				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Zone Name</label>
					<div class="col-xs-10">
						<div class="form-control">
							{{ $zone->name }}
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Zone Map</label>
					<div class="col-xs-10">
						<div class="form-control">
							<div style="height: 500px;" id="zones_map"></div>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">City</label>
					<div class="col-xs-10">
						<div class="form-control">
							{{ $zone->city }}
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">State</label>
					<div class="col-xs-10">
						<div class="form-control">
							{{ $zone->state }}
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Country</label>
					<div class="col-xs-10">
						<div class="form-control">
							{{ $zone->country }}
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Currency</label>
					<div class="col-xs-10">
						<div class="form-control">
							{{ $zone->currency }}
						</div>
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">Status</label>
					<div class="col-xs-10">
						<td>
							<div class="form-control">
								@if($zone->status == 'active')
									Active
								@else
									Inactive
								@endif
							</div>
						</td>
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<div class="col-xs-1">
							<form action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST">
								{{ csrf_field() }}
								<input type="hidden" name="_method" value="DELETE">
								<button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> @lang('admin.delete')</button>
							</form>
						</div>
						<div class="col-xs-2">
							<a href="{{route('admin.zones.index')}}" class="btn btn-default">Back to User Listing</a>
						</div>
					</div>
				</div>
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

			});
			drawingManager.setMap(map);

			google.maps.event.addListener(drawingManager.getPath(), 'set_at', function(polyline) {
				document.getElementById("zone_area").value = JSON.stringify(drawingManager.getPath().getArray());
			});
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4B2q5kad51qSGEuWPK9tW0T-5YoWTuUs&libraries=places,drawing&callback=initGoogleMap" async defer></script>


@endsection
