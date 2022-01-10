@extends('admin.layout.base')

@section('title', 'Support Message')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">Support Message</h5>

                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>@lang('admin.id')</th>
                            <th>BY </th>
                            <th>Subject </th>
                            <th>@lang('admin.status')</th>
                            <th>@lang('admin.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($supportMessages as $index => $supportMessage)
                        <tr>
                            <td>{{$supportMessage->id}}</td>
                            <td>{{$supportMessage->user_type}} (ID: {{$supportMessage->user_id}})</td>
                            <td>{{$supportMessage->subject}}</td>
                            <td>{{$supportMessage->status}}</td>
                            <td>
                                <form action="{{ route('admin.support-message.destroy', $supportMessage->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    @if( Setting::get('demo_mode') == 0)
                                        <a href="{{ route('admin.support-message.show', $supportMessage->id) }}" class="btn btn-warning"><i class="fa fa-eye"></i> Show</a>
                                        <a href="{{ route('admin.support-message.edit', $supportMessage->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
                                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('admin.id')</th>
                            <th>BY </th>
                            <th>Subject </th>
                            <th>@lang('admin.status')</th>
                            <th>@lang('admin.action')</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection