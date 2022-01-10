@extends('admin.layout.base')

@section('title', 'Update Referral Code Amount')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.referral-code.show', 1) }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

			<h5 style="margin-bottom: 2em;">Referral Code Amount</h5>

            <form class="form-horizontal" action="{{route('admin.referral-code.update', $referral_code_amount->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Referral Code to User Amount</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $referral_code_amount->referral_code_amount_to_user }}" name="referral_code_amount_to_user" required id="referral_code_amount_to_user" placeholder="Referral Code Amount to User">
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Referral Code by User Amount</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $referral_code_amount->referral_code_amount_by_user }}" name="referral_code_amount_by_user" required id="referral_code_amount_by_user" placeholder="Referral Code Amount by User">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Update Referral Code Amount</button>
						<a href="{{route('admin.referral-code.show', 1)}}" class="btn btn-default">@lang('admin.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
