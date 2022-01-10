@extends('admin.layout.base')

@section('title', 'Add Rewards')

@section('content')

	<div class="content-area py-1">
		<div class="container-fluid">
			<div class="box box-block bg-white">
				<a href="{{ route('admin.rewards.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

				<h5 style="margin-bottom: 2em;">New Rewards</h5>

				<form class="form-horizontal" action="{{route('admin.rewards.store')}}" method="POST" enctype="multipart/form-data" role="form">
					{{csrf_field()}}
					<div class="form-group row">
						<label for="name" class="col-xs-12 col-form-label">Service Type</label>
						<div class="col-xs-10">
							<select class="form-control input" name="service_type_id" required>
								@forelse($ServiceTypes as $Type)
									<option value="{{ $Type->id }}">{{ $Type->name }}</option>
								@empty
									<option>- Please Create a Service Type -</option>
								@endforelse
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="description" class="col-xs-12 col-form-label">Spending Amount ($)</label>
						<div class="col-xs-10">
							<input class="form-control" type="number" value="1" name="spending_amount"  id="spending_amount" placeholder="spending_amount" readonly>
						</div>
					</div>
					<div class="form-group row">
						<label for="description" class="col-xs-12 col-form-label">Points on Amount</label>
						<div class="col-xs-10">
							<input class="form-control" type="number" value="{{ old('points') }}" name="points" required id="points" placeholder="Points">
						</div>
					</div>
					<div class="form-group row">
						<label for="zipcode" class="col-xs-12 col-form-label"></label>
						<div class="col-xs-10">
							<button type="submit" class="btn btn-primary">Add Rewards</button>
							<a href="{{route('admin.rewards.index')}}" class="btn btn-default">@lang('admin.cancel')</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
