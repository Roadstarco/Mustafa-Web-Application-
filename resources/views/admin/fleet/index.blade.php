@extends('admin.layout.base')

@section('title', 'Company Owners ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            @if(Setting::get('demo_mode') == 1)
        <div class="col-md-12" style="hSetting::get('demo_mode', 0) == 0eight:50px;color:red;">
                    ** Demo Mode : No Permission to Edit and Delete.
                </div>
                @endif
            <h5 class="mb-1">
                Company Owners
                @if(Setting::get('demo_mode', 1) == 1)
                <span class="pull-right">(*personal information hidden in demo)</span>
                @endif
            </h5>
            <a href="{{ route('admin.fleet.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Company Owner</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.account-manager.full_name')</th>
                        <th>@lang('admin.fleet.company_name')</th>
                        <th>@lang('admin.email')</th>
                        <th>@lang('admin.mobile')</th>
                        <th>@lang('admin.picture')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fleets as $index => $fleet)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $fleet->name }}</td>
                        <td>{{ $fleet->company }}</td>
                        @if(Setting::get('demo_mode', 0) == 1)
                        <td>{{ substr($fleet->email, 0, 3).'****'.substr($fleet->email, strpos($fleet->email, "@")) }}</td>
                        @else
                        <td>{{ $fleet->email }}</td>
                        @endif
                        @if(Setting::get('demo_mode', 0) == 1)
                        <td>+919876543210</td>
                        @else
                        <td>{{ $fleet->mobile }}</td>
                        @endif
                        <td><img src="{{img($fleet->logo)}}" style="height: 100px;"></td>
                        <td>
                            <div class="input-group-btn">
                              
                               
                             @if($fleet->status=='approved')
                              <a class="btn btn-danger btn-block" href="{{ route('admin.fleet.disapprove', $fleet->id ) }}">@lang('Disable')</a>
                             @else
                             <a class="btn btn-success btn-block" href="{{ route('admin.fleet.approve', $fleet->id ) }}">@lang('Enable')</a>
                             @endif 
                               
                                <button type="button" 
                                    class="btn btn-info btn-block dropdown-toggle"
                                    data-toggle="dropdown">@lang('admin.action')
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @if( Setting::get('demo_mode') == 0)
                                    <li>
                                        <a href="{{ route('admin.fleet.edit', $fleet->id) }}" class="btn btn-default"><i class="fa fa-pencil"></i> @lang('admin.edit')</a>
                                    </li>
                                    @endif
                                    <li>
                                        <form action="{{ route('admin.fleet.destroy', $fleet->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            @if( Setting::get('demo_mode') == 0)
                                            <button class="btn btn-default look-a-like" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i>@lang('admin.delete')</button>
                                            @endif
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.account-manager.full_name')</th>
                        <th>@lang('admin.fleet.company_name')</th>
                        <th>@lang('admin.email')</th>
                        <th>@lang('admin.mobile')</th>
                        <th>@lang('admin.picture')</th>
                        <th>@lang('admin.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection