@extends('admin.layout.base')

@section('title', 'Referral Code ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <h5 style="margin-bottom: 2em;">@lang('admin.include.referral_code')</h5>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Referral Code to User Amount</label>
					<div class="col-xs-10">
						<div class="form-control" >
							{{ $referral_code_amount->referral_code_amount_to_user }}
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Referral Code by User Amount</label>
					<div class="col-xs-10">
						<div class="form-control" >
							{{ $referral_code_amount->referral_code_amount_by_user }}
						</div>
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<a href="{{ route('admin.referral-code.edit', 1) }}" class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.edit')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
