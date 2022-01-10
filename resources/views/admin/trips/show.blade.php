@extends('admin.layout.base')

@section('title', 'Trip details ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h4>Trip details</h4>
            <a href="{{ url('admin/trips/index') }}" class="btn btn-default pull-right">
                <i class="fa fa-angle-left"></i> Back
            </a>
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">User Name :</dt>
                        <dd class="col-sm-8">{{ $trip->user->first_name }}</dd>

                        <dt class="col-sm-4">Provider Name :</dt>
                        @if($trip->provider)
                        <dd class="col-sm-8">{{ $trip->provider->first_name }}</dd>
                        @else
                        <dd class="col-sm-8">Provider not yet assigned!</dd>
                        @endif

                        <dt class="col-sm-4">Pickup Date :</dt>
                        <dd class="col-sm-8">{{ $trip->arrival_date }}</dd>
                                        
                        <!--<dt class="col-sm-4">Total Distance :</dt>
                        <dd class="col-sm-8">{{ $trip->distance ? $trip->distance : '-' }}</dd>-->

                       {{-- @if($trip->status == 'SCHEDULED')
                        <dt class="col-sm-4">Ride Scheduled Time :</dt>
                        <dd class="col-sm-8">
                            @if($trip->schedule_at != "0000-00-00 00:00:00")
                                {{ date('jS \of F Y h:i:s A', strtotime($trip->schedule_at)) }} 
                            @else
                                - 
                            @endif
                        </dd>
                        @else
                        <dt class="col-sm-4">Ride Start Time :</dt>
                        <dd class="col-sm-8">
                            @if($trip->started_at != "0000-00-00 00:00:00")
                                {{ date('jS \of F Y h:i:s A', strtotime($trip->started_at)) }} 
                            @else
                                - 
                            @endif
                         </dd>

                        <dt class="col-sm-4">Ride End Time :</dt>
                        <dd class="col-sm-8">
                            @if($trip->finished_at != "0000-00-00 00:00:00") 
                                {{ date('jS \of F Y h:i:s A', strtotime($trip->finished_at)) }}
                            @else
                                - 
                            @endif
                        </dd>
                        @endif --}}

                        <dt class="col-sm-4">Pickup Address :</dt>
                        <dd class="col-sm-8">{{ $trip->tripfrom ? $trip->tripfrom : '-' }}</dd>

                        <dt class="col-sm-4">Drop Address :</dt>
                        <dd class="col-sm-8">{{ $trip->tripto ? $trip->tripto : '-' }}</dd>

                        @if($trip->payment)
                        <dt class="col-sm-4">Base Price :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->fixed) }}</dd>

                        <dt class="col-sm-4">Distance Price :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->distance) }}</dd>

                        <dt class="col-sm-4">Service Charges :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->commision) }}</dd>

                        <dt class="col-sm-4">Discount Price :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->discount) }}</dd>

                        <dt class="col-sm-4">Tax Price :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->tax) }}</dd>

                        <dt class="col-sm-4">Surge Price :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->surge) }}</dd>

                        <dt class="col-sm-4">Total Amount :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->total) }}</dd>

                        <dt class="col-sm-4">Wallet Deduction :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->wallet) }}</dd>

                        <dt class="col-sm-4">Paid Amount :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->payable) }}</dd>

                        <dt class="col-sm-4">Provider Earnings:</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->provider_pay) }}</dd>

                        <dt class="col-sm-4">Provider Admin Commission :</dt>
                        <dd class="col-sm-8">{{ currency($trip->payment->provider_commission) }}</dd>
                        @endif

                        <dt class="col-sm-4">Ride Status : </dt>
                        <dd class="col-sm-8">
                            {{ $trip->trip_status }}
                        </dd>

                        @if($trip->cancel_reason)
                        <dt class="col-sm-4">Cancel Reason : </dt>
                        <dd class="col-sm-8">
                            {{ $trip->cancel_reason }}
                        </dd>
                        @endif

                    </dl>
                </div>
               <!-- <div class="col-md-6">
                    <div id="map"></div>
                </div>-->
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style type="text/css">
    #map {
        height: 450px;
    }
</style>
@endsection

@section('scripts')


@endsection