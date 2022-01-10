@extends('admin.layout.base')

@section('title', 'Update Reward Amount')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.reward-amount.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

                <h5 style="margin-bottom: 2em;">Update Rewards</h5>

                <form class="form-horizontal" action="{{route('admin.reward-amount.update', $rewardAmount->id )}}" method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="PATCH">

                    <div class="form-group row">
                        <label for="name" class="col-xs-12 col-form-label">Minimun Points Redemed</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="number" value="{{ $rewardAmount->redeemable_points }}" name="redeemable_points" required id="redeemable_points" placeholder="Minimun Points Redemed">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-xs-12 col-form-label">Reward Money</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="number" value="{{ $rewardAmount->reward_money }}" name="reward_money" required id="reward_money" placeholder="Amount on Redeeming Points">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-2 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">Update Rewards</button>
                            <a href="{{route('admin.reward-amount.index')}}" class="btn btn-default">@lang('admin.cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
