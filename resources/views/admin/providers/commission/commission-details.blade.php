@extends('admin.layout.base')

@section('title', "Provider Commission Details")

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">

					<div class="row row-md mb-2" style="padding: 15px;">
						<div class="col-md-12">
							<div class="box bg-white">
								<div class="box-block clearfix">
									<h5 class="float-xs-left">Provider Payable Commission: {{currency($Provider->commission_payable)}}</h5>

									<div class="float-xs-right">
									</div>

									@if($Provider->commission_payable > 0)

									<br>
									<br>
									<form class="form-horizontal" action="{{ route('admin.provider.commission-pay') }}" method="post">
										{{csrf_field()}}

										<div class="form-group row">
											<label for="mobile" class="col-xs-12 col-form-label">Commission Amount</label>
											<div class="col-xs-10">
												<input type="hidden" name="provider_id" value="{{$Provider->id}}" >
												<input class="form-control" type="number" max="{{$Provider->commission_payable }}" step="0.01" name="commission_amount" id="commission_amount" placeholder="Enter Amount" required>
											</div>
										</div>

										<div class="form-group row">
											<label for="zipcode" class="col-xs-12 col-form-label"></label>
											<div class="col-xs-10">
												<button type="submit" class="btn btn-primary">Submit</button>
												<a href="{{route('admin.provider.index')}}" class="btn btn-default">@lang('admin.cancel')</a>
											</div>
										</div>

									</form>
									@else
										<br>
										<br>
										No Payable Commission for this Provider
									@endif
								</div>
							</div>
						</div>

					</div>

            </div>
        </div>
    </div>

@endsection
