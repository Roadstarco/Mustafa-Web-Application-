@extends('provider.layout.app')

@section('title', 'Trip - Create Bid ')

@section('content')
<div class="pro-dashboard-head">
    <div class="container">
        <a href="{{route('provider.profile.index')}}">Profile</a>
        <a href="{{ route('provider.documents.index') }}" class="pro-head-link">Manage Documents</a>
        <a href="{{ route('provider.location.index') }}" class="pro-head-link">Update Location</a>
        <a  class="pro-head-link active" href="javascript:void(0);" class="pro-head-link">International Trips</a>
    </div>
</div>
<div class="pro-dashboard-content gray-bg ">
    <div class="profile">
        <div class="profile-content gray-bg pad50">
            <div class="container">
                <div class="col-md-12">
                    <div class="dash-content">
                        <div class="row no-margin">
                            <div class="col-md-12">
                                <h4 class="page-title">Trip Details</h4>
                            </div>
                        </div>
                        <div class="item item-travel">

                            <div class="description">
                                <div class="infos">
                                    <span class="date">
                                        <i class="fa fa-calendar"></i> 
                                        <strong>
                                            {{ date('d M Y',strtotime($trip->arrival_date)) }}
                                        </strong>
                                    </span>
                                    <i class="transportation fa fa-plane"></i>
                                </div>
                                <div class="path">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="origin">
                                                <i class="fa fa-map-marker"></i>
                                                <strong>
                                                    {{$trip->tripfrom}}
                                                </strong>
                                                        <!--<span class="country">(US)</span>-->
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="steps">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="arrival">
                                                <i class="fa fa-map-marker"></i>
                                                {{$trip->tripto}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr>
                        @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                        @endif
                        <div class="row no-margin ride-detail">

                            <form action="{{route('provider.international-trip.bid.store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="trip_id" value="{{$trip->id}}">

                                <div class=" form-group col-md-12" >      
                                    <div class="form-group col-md-6">
                                        <label for="service_type">
                                            Service Type
                                        </label>
                                        <select id="service_type" required="" name="service_type" class="form-control" placeholder="Select Item Size">
                                            <option value="">Select Transportation Type</option>
                                            <option value="By Road">Ground</option>
                                            <option value="By Air">Air</option>
                                            <option value="By Sea">Sea</option>    
                                        </select>
                                    </div>
                                    <div class=" form-group col-md-6" >
                                        <label>Amount</label>
                                        <input type="number" min="1" name="amount" id="amount" value="{{ old('amount') }}" class="form-control"/>
                                    </div>
                                </div>
                                <div class=" form-group col-md-12" >
                                    <div class=" form-group col-md-6" >
                                        <label  for="other_information">
                                            Other Information
                                        </label>
                                        <textarea name="traveller_response" class="form-control">{{ old('traveller_response') }}</textarea>
                                    </div> 
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="form-sub-btn big pull-right">Save</button>
                                    </div>
                                </div>


                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection