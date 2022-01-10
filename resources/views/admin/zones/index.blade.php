@extends('admin.layout.base')

@section('title', 'Zones')

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
                Zones
                @if(Setting::get('demo_mode', 0) == 1)
                <span class="pull-right">(*personal information hidden in demo)</span>
                @endif
            </h5>
            <a href="{{ route('admin.zones.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Zone</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.name')</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zones as $index => $zone)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $zone->name }}</td>
                        <td>{{ $zone->city }}</td>
                        <td>{{ $zone->state }}</td>
                        <td>{{ $zone->country }}</td>
                        <td>
                            <form action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST">
                                <a href="{{ route('admin.zones.show', $zone->id) }}" class="btn btn-warning"><i class="fa fa-eye"></i>Show</a>

                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                @if( Setting::get('demo_mode') == 0)
                                <a href="{{ route('admin.zones.edit', $zone->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.edit')</a>
                                <button class="btn btn-danger" onclick="return confirm('Are you sure to remove this zone?')"><i class="fa fa-trash"></i> @lang('admin.delete')</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.name')</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection