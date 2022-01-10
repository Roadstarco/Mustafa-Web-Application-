@extends('admin.layout.base')

@section('title', 'Trip Details ')

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
                Trip Details
                @if(Setting::get('demo_mode', 1) == 1)
                <span class="pull-right">(*personal information hidden in demo)</span>
                @endif
            </h5>
            <a href="{{ url('/trips') }}" style="margin-left: 1em;"> </a>
            <table class="table table-striped table-bordered dataTable dt-responsive nowrap" id="table-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Provider Name</th>
                        <!--<th>Email</th>-->
                        <th>Trip From</th>
                        <th>Trip To</th>
                        <th>Arrival Date</th>
                        <th>Trip Status</th>
                        <th>Recurrence</th>
                        <th>Item Size</th>
                         <th>Action</th>
                        <th>Other Information</th>
                        <th>Service Type</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @if (@$trips)
                    @foreach($trips as $index => $trips)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $trips->first_name.' '.$trips->last_name }}</td>
                         <!--<td>{{ $trips->email }}</td>-->
                        <td>{{ $trips->tripfrom }}</td>
                        <td>{{ $trips->tripto }}</td>
                        <td>{{ $trips->arrival_date }}</td>
                        <td>{{ $trips->trip_status }}</td>
                        <td>{{ $trips->recurrence }}</td>
                        <td>{{ $trips->item_size }}</td>
                        <!--<td> 
                        <div class="input-group-btn">
                            <li>
                                        <form action="{{ url('admin/trips/destroy', $trips->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            @if( Setting::get('demo_mode') == 0)
                                            <button class="btn btn-default look-a-like" onclick="return confirm('Are you sure?')"> <i class="fa fa-trash"> </i> 
                                             @lang('admin.delete') </button>
                                            @endif
                                        </form>
                                    </li>
                         </div>
                        </td>-->
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary waves-effect dropdown-toggle" data-toggle="dropdown"
                                 aria-haspopup="true"  aria-expanded="true">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ url('admin/trips/show', $trips->id) }}" class="dropdown-item">
                                        <i class="fa fa-search"></i> More Details
                                    </a>
                                    <form action="{{ url('admin/trips/destroy', $trips->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        @if( Setting::get('demo_mode') == 0)
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">
                                            <i class="fa fa-trash"></i>  @lang('admin.delete')
                                        </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td>{{ $trips->other_information }}</td>
                        <td>{{ $trips->service_type }}</td>
                        
                        
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                
            </table>
        </div>
    </div>
</div>


@endsection