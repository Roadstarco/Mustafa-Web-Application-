@extends('admin.layout.base')

@section('title', 'Update Support Message ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.support-message.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

			<h5 style="margin-bottom: 2em;">Support Message</h5>

            <form class="form-horizontal" action="{{route('admin.support-message.update', $supportMessage->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				<div class="form-group row">
					<label for="promo_code" class="col-xs-2 col-form-label">Subject</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $supportMessage->subject }}" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-xs-2 col-form-label">Message</label>
					<div class="col-xs-10">
						<textarea class="form-control" rows="5"  readonly>{{ $supportMessage->message }}</textarea>
					</div>
				</div>
				<div class="form-group row">
					<label for="discount" class="col-xs-2 col-form-label">Status</label>
					<div class="col-xs-10">
						<select class="form-control" name="status" required id="status">
							<option value="NEW" @if($supportMessage->status=='NEW') selected @endif >New</option>
							<option value="INPROGRESS" @if($supportMessage->status=='INPROGRESS') selected @endif >In Progress</option>
							<option value="CLOSED" @if($supportMessage->status=='CLOSED') selected @endif >Closed</option>
						</select>
					</div>
				</div>


				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Update</button>
						<a href="{{route('admin.support-message.index')}}" class="btn btn-default">@lang('admin.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
