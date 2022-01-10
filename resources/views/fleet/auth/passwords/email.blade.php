@extends('fleet.layout.auth')

<!-- Main Content -->
@section('content')
<div class="sign-form">
    <div class="row">
        <div class="col-md-4 offset-md-4 px-3">
            <div class="box b-a-0">
                <div class="p-2 text-xs-center">
                    <h5>Reset Password</h5>
                </div>
                @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                <form class="form-material mb-1" role="form" method="POST" action="{{ url('/fleet/password/email') }}" >
                {{ csrf_field() }}
                   <div class="col-md-12">
                            <input type="email" class="form-control" name="email" placeholder="Email Address" value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif                        
                        </div>

                    <div class="px-2 form-group mb-0">
                        <button type="submit" class="btn btn-purple btn-block text-uppercase">Send Password Reset Link</button>
                    </div>
                </form>
                <div class="p-2 text-xs-center text-muted">
                    <a class="text-black" href="{{ url('/fleet/login') }}"><span class="underline">Login Here!</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
