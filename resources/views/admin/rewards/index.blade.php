@extends('admin.layout.base')

@section('title', 'Rewards')

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
                <a href="{{ route('admin.rewards.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Rewards</a>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>Service Type</th>
                        <th>Amount Spend</th>
                        <th>Points on Amount Spending</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rewardsList as $index => $rewardListItem)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @foreach($ServiceTypes as $Type)
                                 @if(  $rewardListItem->service_type_id  ==  $Type->id )
                                    <td>{{ $Type->name }}</td>
                                @endif
                            @endforeach

                            <td>{{ $rewardListItem->spending_amount }}</td>
                            <td>{{ $rewardListItem->points }}</td>
                            <td>
                                <form action="{{ route('admin.rewards.destroy', $rewardListItem->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    @if( Setting::get('demo_mode') == 0)
                                        <a href="{{ route('admin.rewards.edit', $rewardListItem->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.edit')</a>
                                        <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> @lang('admin.delete')</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>Service Type</th>
                        <th>Amount Spend</th>
                        <th>Points on Amount Spending</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

