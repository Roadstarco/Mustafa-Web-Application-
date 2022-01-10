@extends('provider.layout.auth')

@section('content')
<div class="col-md-12">
    <h2>Upload Documents</h2>
</div>

<div class="col-md-12" style="margin-top: 20px;">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/provider/save_documents') }}" enctype="multipart/form-data">


        {{ csrf_field() }}
        <label class="control-label">Company Name</label>
        <input type="text" class="form-control" name="comp_name" required="">
        <label class="control-label">Company Reg No.</label>
        <input type="text" class="form-control" name="comp_reg_no" required="">
        <label class="control-label">Vehicle No.</label>
        <input type="text" class="form-control" name="number_of_vehicle" required="">
        @foreach($DriverDocuments as $Document)
        <label class="control-label">{{$Document->name}}</label>
        <input type="file" class="form-control" name="document[{{$Document->id}}]" required="">
        @endforeach
        <button type="submit" class="log-teal-btn">
            Submit
        </button>
    </form>
</div>

@endsection

@section('styles')
<link href="{{ asset('asset/css/jasny-bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<style type="text/css">
    .fileinput .btn-file {
        padding:0;
        background-color: #fff;
        border: 0;
        border-radius:0!important;
    }
    .fileinput .form-control {
        border: 0;
        box-shadow : none;
        border-left:0;
        border-right:5px;
    }
    .fileinput .upload-link {
        border:0;
        border-radius: 0;
        padding:0;
    }
    .input-group-addon.btn {
        background: #fff;
        border: 1px solid #37b38b;
        border-radius: 0; 
        padding: 10px;
        height: 40px;
        line-height: 20px;
    }
    .fileinput .fileinput-filename {
        font-size: 10px;
    }
    .fileinput .btn-submit {
        padding: 0;
    }
    .fileinput button {
        background-color: white;
        border: 0;
        padding: 10px;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('asset/js/jasny-bootstrap.min.js') }}"></script>
@endsection