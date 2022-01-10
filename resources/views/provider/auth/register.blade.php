@extends('provider.layout.auth')

@section('content')
    <div class="col-md-12">
        <a class="log-blk-btn" href="{{ url('/provider/login') }}">ALREADY REGISTERED?</a>
        <h3>Sign Up</h3>
    </div>

    <div class="col-md-12">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/provider/register') }}">

          
            {{ csrf_field() }}

            <div id="third_step">

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
            <div class="col-md-3" style="padding: unset">
                    <input value="+1"   type="text" placeholder="+1" id="country_code" name="country_code" />
                </div>

                <div class="col-md-9" style="padding-right: unset">
                    <input type="text" autofocus id="phone_number" class="form-control" placeholder="Enter Phone Number" name="phone_number" value="{{ old('phone_number') }}" />
                </div>
                <label class="checkbox-inline"><input type="checkbox" checked name="gender" value="MALE">Male</label>
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

                <select class="form-control" name="service_type" id="service_type">
                    <option value="">Select Service</option>
                    @foreach(get_all_service_types() as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                    @endforeach
                </select>

                @if ($errors->has('service_type'))
                    <span class="help-block">
                    <strong>{{ $errors->first('service_type') }}</strong>
                </span>
                @endif

                <input id="service-number" type="text" class="form-control" name="service_number" value="{{ old('service_number') }}" placeholder="Car Number">

                @if ($errors->has('service_number'))
                    <span class="help-block">
                    <strong>{{ $errors->first('service_number') }}</strong>
                </span>
                @endif

                <input id="service-model" type="text" class="form-control" name="service_model" value="{{ old('service_model') }}" placeholder="Car Model">

                @if ($errors->has('service_model'))
                    <span class="help-block">
                    <strong>{{ $errors->first('service_model') }}</strong>
                </span>
                @endif

                <button type="submit" class="log-teal-btn">
                    Register
                </button>

            </div>
        </form>
    </div>
@endsection


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
        // Paste the config your copied earlier
        $("input:checkbox").on('click', function() {
  // in the handler, 'this' refers to the box clicked on
  var $box = $(this);
  if ($box.is(":checked")) {
    // the name of the box is retrieved using the .attr() method
    // as it is assumed and expected to be immutable
    var group = "input:checkbox[name='" + $box.attr("name") + "']";
    // the checked state of the group/box on the other hand will change
    // and the current value is retrieved using .prop() method
    $(group).prop("checked", false);
    $box.prop("checked", true);
  } else {
    $box.prop("checked", false);
  }
});
        var firebaseConfig = {
    apiKey: "AIzaSyASUDdZRSypdUMs1F2O2qYmz3lmIL74N_Y",
    authDomain: "lofty-cabinet-267422.firebaseapp.com",
    databaseURL: "https://lofty-cabinet-267422-default-rtdb.firebaseio.com/",
    projectId: "lofty-cabinet-267422",
    storageBucket: "lofty-cabinet-267422.appspot.com",
    messagingSenderId: "234677169665",
    appId: "1:234677169665:web:fc3101057ca14f4e309a00",
    measurementId: "G-4BXSZ71P0N"
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
