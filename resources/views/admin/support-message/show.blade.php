@extends('admin.layout.base')

@section('title', 'Show Support Message ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.support-message.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

			<h5 style="margin-bottom: 2em;">Support Message</h5>

			<div class="form-group row">
				<label for="promo_code" class="col-xs-2 col-form-label">Subject</label>
				<div class="col-xs-10">
					<input class="form-control" type="text" value="{{ $supportMessage->subject }}" readonly>
				</div>
			</div>
			<div class="form-group row">
				<label for="promo_code" class="col-xs-2 col-form-label">Message</label>
				<div class="col-xs-10">
					<input class="form-control" type="text" value="{{ $supportMessage->message }}" readonly>
				</div>
			</div>
			<div class="form-group row">
				<label for="promo_code" class="col-xs-2 col-form-label">Status</label>
				<div class="col-xs-10">
					<input class="form-control" type="text" value="{{ $supportMessage->status }}" readonly>
				</div>
			</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<a href="{{route('admin.support-message.index')}}" class="btn btn-default">Back</a>
					</div>
				</div>
		</div>
    </div>
</div>

@endsection
