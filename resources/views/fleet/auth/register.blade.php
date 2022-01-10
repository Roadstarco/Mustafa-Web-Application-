@extends('fleet.layout.auth')

@section('content')
<style>
.message{
     padding: 30px 15px;
}
    .message h5{
       font-size: 24px;
   text-align:center;
    color: #27823b;
    }
    .message p{
        padding-top:15px;
        font-size:15px;
        color:#333;
    } 
</style>
<div class="sign-form">
    <div class="row">
        <div class="col-md-4 offset-md-4 px-3">
            <div class="box b-a-0">
               
                @if (session()->has('message'))
<div class="message">
          <h5>Success</h5>
<p>{!! session('message') !!}</p>
</div>
@else
 <div class="p-2 text-xs-center">
                    <h5>Company Owner Register</h5>
                </div>
<form class="form-material mb-1" role="form" method="POST" action="{{ url('/fleet/register') }}" >
                {{ csrf_field() }}
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="Full Name">
                        @if ($errors->has('name'))
                            <span class="help-block" style="margin-left: 55px;color: red;">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('company') ? ' has-error' : '' }}">
                        <input class="form-control" type="text" value="{{ old('company') }}" name="company" required id="company" placeholder="Company Name">
                        @if ($errors->has('company'))
                            <span class="help-block" style="margin-left: 55px;color: red;">
                                <strong>{{ $errors->first('company') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input class="form-control" type="text" required name="email" value="{{old('email')}}" id="email" placeholder="Email">
                        @if ($errors->has('email'))
                            <span class="help-block" style="margin-left: 55px;color: red;">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input class="form-control" type="password" name="password" id="password" placeholder="Password">
                        @if ($errors->has('password'))
                            <span class="help-block" style="margin-left: 55px;color: red;">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-type Password">
                        @if ($errors->has('password'))
                            <span class="help-block" style="margin-left: 55px;color: red;">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                       <input class="form-control" type="number" value="{{ old('mobile') }}" name="mobile" required id="mobile" placeholder="Mobile">
                        @if ($errors->has('mobile'))
                            <span class="help-block" style="margin-left: 55px;color: red;">
                                <strong>{{ $errors->first('mobile') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="px-2 form-group mb-0">
                        <!--<input type="checkbox" name="remember"> Remember Me-->
                    </div>
                    <br>
                    <div class="px-2 form-group mb-0">
                        <button type="submit" class="btn btn-purple btn-block text-uppercase">Register</button>
                    </div>
                </form>
@endif
                
                <div class="p-2 text-xs-center text-muted">
                    <!--<a class="text-black" href="{{ url('/fleet/password/reset') }}"><span class="underline">Forgot Your Password?</span></a>-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
