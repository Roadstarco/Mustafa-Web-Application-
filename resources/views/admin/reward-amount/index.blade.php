@extends('admin.layout.base')

@section('title', 'Reward Amount')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                @if(Setting::get('demo_mode') == 1)
                    <div class="col-md-12" style="height:50px;color:red;">
                        ** Demo Mode : No Permission to Edit and Delete.
                    </div>
                @endif
                <h5 class="mb-1">
                    Rewards
                    @if(Setting::get('demo_mode', 0) == 1)
                        <span class="pull-right">(*personal information hidden in demo)</span>
                    @endif
                </h5>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>Points to be Redeemed</th>
                        <th>Reward Money</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rewardAmounts as $index => $rewardAmountItem)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $rewardAmountItem->redeemable_points }}</td>
                            <td>{{ $rewardAmountItem->reward_money }}</td>
                            <td>
                                <form action="{{ route('admin.reward-amount.destroy', $rewardAmountItem->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    @if( Setting::get('demo_mode') == 0)
                                        <a href="{{ route('admin.reward-amount.edit', $rewardAmountItem->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.edit')</a>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>Points to be Redeemed</th>
                        <th>Reward Money</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

