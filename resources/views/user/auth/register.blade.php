@extends('user.layout.auth')

@section('content')

    <?php $login_user = asset('asset/img/login-user-bg.jpg'); ?>
    <div class="full-page-bg" style="background-image: url({{$login_user}});">
        <div class="log-overlay"></div>
        <div class="full-page-bg-inner">
            <div class="row no-margin">
                <div class="col-md-6 log-left">
                    <a href="{{url('/')}}"><span class="login-logo"><img src="{{ Setting::get('site_logo', asset('logo-black.png'))}}"></span></a>
                    <h2>Create your account and get moving in minutes</h2>
                    <p>Welcome to {{Setting::get('site_title','Uber')}}, the easiest way to get around at the tap of a button.</p>
                </div>
                <div class="col-md-6 log-right">
                    <div class="login-box-outer">
                        <div class="login-box row no-margin">
                            <div class="col-md-12">
                                <a class="log-blk-btn" href="{{url('login')}}">ALREADY HAVE AN ACCOUNT?</a>
                                <h3>Create a New Account</h3>
                            </div>
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">

                                <div id="first_step">
                                    <div class="col-md-4">
                                        <input value="+1" type="text" placeholder="+1" id="country_code" name="country_code" />
                                    </div>

                                    <div class="col-md-8">
                                        <input type="text" autofocus id="phone_number" class="form-control" placeholder="Enter Phone Number" name="phone_number" value="{{ old('phone_number') }}" />
                                    </div>

                                    <div class="col-md-8">
                                        @if ($errors->has('phone_number'))
                                            <span class="help-block">
                        <strong>{{ $errors->first('phone_number') }}</strong>
                    </span>
                                        @endif
                                    </div>
                                    <div class="col-md-12" id="mobile_verfication"></div>
                                    <div class="col-md-12" style="padding-bottom: 10px;">
                                        <input type="button" class="log-teal-btn small" id="verify_phone_number" onclick="submitPhoneNumberAuth()" value="Verify Phone Number"/>
                                    </div>
                                </div>

                                <div id="second_step" style="display: none;">
                                    <div class="col-md-4">
                                        <input value="+880" readonly type="text" placeholder="+880" id="country_code_readonly" name="country_code_readonly" />
                                    </div>

                                    <div class="col-md-8">
                                        <input type="text" readonly  id="phone_number_readonly" class="form-control" placeholder="Enter Phone Number" name="phone_number_readonly" />
                                    </div>
                                    <div class="col-md-12">
                                        <input type="number" autofocus id="auth_code" class="form-control" placeholder="Code" name="auth_code" value="{{ old('auth_code') }}"  required/>
                                    </div>

                                    <div class="col-md-12" style="padding-bottom: 10px;" id="mobile_verfication">
                                        <input type="button" class="log-teal-btn small" onclick="submitPhoneNumberAuthCode();" value="Submit Auth Code"/>
                                    </div>
                                </div>


                                {{ csrf_field() }}

                                <div id="third_step" style="display: none;">

                                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
                                    @endif

                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
                                    @endif

                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                                    @endif

                                    <label class="checkbox-inline"><input type="checkbox" name="gender" value="MALE">Male</label>
                                    <label class="checkbox-inline"><input type="checkbox" name="gender" value="FEMALE">Female</label>
                                    @if ($errors->has('gender'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('gender') }}</strong>
                </span>
                                    @endif

                                    <input id="password" type="password" class="form-control" name="password" placeholder="Password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                                    @endif

                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
                                    @endif

                                    <button type="submit" class="log-teal-btn">
                                        Register
                                    </button>

                                </div>
                            </form>

                            <div class="col-md-12">
                                <p class="helper">Or <a href="{{route('login')}}">Sign in</a> with your user account.</p>
                            </div>

                        </div>


                        <div class="log-copy"><p class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@section('scripts')
    <script type="text/javascript">
        $('.checkbox-inline').on('change', function() {
            $('.checkbox-inline').not(this).prop('checked', false);
        });
    </script>

    <!-- Add the latest firebase dependecies from CDN -->
    <script src="https://www.gstatic.com/firebasejs/6.3.3/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.3.3/firebase-auth.js"></script>
    <script>
      var firebaseConfig = {
    apiKey: "AIzaSyBRqgzacSHamogP1R3hkTaRXmntr2jgk2g",
    authDomain: "road-star-7e560.firebaseapp.com",
    databaseURL: "https://road-star-7e560.firebaseio.com",
    projectId: "road-star-7e560",
    storageBucket: "road-star-7e560.appspot.com",
    messagingSenderId: "480273579736",
    appId: "1:480273579736:web:5ef1d3259aec842a3be1ad",
    measurementId: "G-VK53E12RBD"
  };
        firebase.initializeApp(firebaseConfig);

        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('verify_phone_number', {
            'size': 'invisible',
            'callback': function(response) {
                // reCAPTCHA solved, allow signInWithPhoneNumber.
            }
        });

        // This function runs when the 'sign-in-button' is clicked
        // Takes the value from the 'phoneNumber' input and sends SMS to that phone number
        function submitPhoneNumberAuth() {
            var countryCode = document.getElementById("country_code").value;
            var phoneNumber = document.getElementById("phone_number").value;

            $('#first_step').fadeOut(500);
            $('#second_step').fadeIn(1000);

            document.getElementById("country_code_readonly").value = countryCode;
            document.getElementById("phone_number_readonly").value = phoneNumber;

            var appVerifier = window.recaptchaVerifier;

            firebase
                .auth()
                .signInWithPhoneNumber(countryCode + phoneNumber, appVerifier)
                .then(function(confirmationResult) {
                    window.confirmationResult = confirmationResult;
                })
                .catch(function(error) {
                    $('#mobile_verfication').html("<p class='helper'> Please try agian! </p>");
                    $('#second_step').fadeOut(500);
                    $('#first_step').fadeIn(1000);
                    console.log(error);
                });

        }
        // This function runs when the 'confirm-code' button is clicked
        // Takes the value from the 'code' input and submits the code to verify the phone number
        // Return a user object if the authentication was successful, and auth is complete
        function submitPhoneNumberAuthCode() {
            var code = document.getElementById("auth_code").value;
            confirmationResult
                .confirm(code)
                .then(function(result) {
                    var user = result.user;
                    console.log(user);
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
        //This function runs everytime the auth state changes. Use to verify if the user is logged in
        firebase.auth().onAuthStateChanged(function(user) {
            if (user) {

                firebase.auth().signOut()

                $('#mobile_verfication').html("<p class='helper'> * Phone Number Verified </p>");
                $('#second_step').fadeOut(300);
                $('#third_step').fadeIn(600);
            }
        });
    </script>


@endsection