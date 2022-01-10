<!DOCTYPE html>
<html lang="en">
<head>
    <style>

        :root {
            --main-bg-color: {{ Setting::get('site_color','#37b38b') }};
            --hover-color: {{ Setting::get('site_hover_color','#278064') }};
        }
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ Setting::get('site_favicon', asset('favicon.ico')) }}" type="image/x-icon">
    <link rel="icon" href="{{ Setting::get('site_favicon', asset('favicon.ico')) }}" type="image/x-icon">

    <title>@yield('title'){{ Setting::get('site_title', 'Uber') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>
    

    <!-- Styles -->
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/rating.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/dashboard-style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/select2.min.css') }}" rel="stylesheet" type="text/css">

    @yield('styles')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <?php
$user_id =0;
//session()->put('provider_id',10);
        foreach (glob(base_path()."/storage/framework/sessions/*") as $filename) {
            foreach (unserialize( file_get_contents($filename) ) as $key => $value) {
                if(substr_count($key, 'login_web') > 0){
                   // echo "Session ID: " . basename($filename) . " - User ID: ".$value."<br>";
                    $user_id = $value;
                }
            }
        }
        // foreach (glob(base_path()."/storage/framework/sessions/*") as $filename) {
        //     foreach (unserialize( file_get_contents($filename) ) as $key => $value) {
        //         if(substr_count($key, 'login_provider') > 0){
        //            // echo "Session ID: " . basename($filename) . " - Provider ID: ".$value."<br>";
        //             $user_id = $value;
        //         }
        //     }
        // }

?> 
<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">Chat</h4>
            </div>
        </div>

        <div class="row no-margin ride-detail">
            <div class="col-md-12">


            </div>
        </div>

    </div>
</div>

<!------   ----------------------------------->
<div id="wrapper">
        <div class="overlay" id="overlayer" data-toggle="offcanvas"></div>
        @include('provider.layout.partials.nav')
        <div id="page-content-wrapper">
            @include('provider.layout.partials.header')
            <div class="page-content">
                <div class="pro-dashboard">
                    @yield('content')
                </div>
                @include('provider.layout.partials.footer')
            </div>
        </div>
    </div>

    <div id="modal-incoming"></div>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('asset/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/jquery.mousewheel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/jquery-migrate-1.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/rating.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/dashboard-scripts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/select2.min.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react-dom.js"></script>
    <script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>

    <script type="text/babel" src="{{ asset('asset/js/incoming.js') }}"></script>
    <script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase-app.js"></script>
      <script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-database.js"></script>
    <script type="text/javascript">
        // $.incoming({
        //     'url': '{{ route('provider.incoming') }}',
        //     'modal': '#modal-incoming'
        // });
        console.log("HI");


    
        
    var messages = [];
        var scroll_bottom = function() {
            var card_height = 0;
            $('.card-body .chat-item').each(function() {
                card_height += $(this).outerHeight();
            });
            $(".card-body").scrollTop(card_height);
        }

        var firebaseConfig = {
            apiKey: 'AIzaSyASUDdZRSypdUMs1F2O2qYmz3lmIL74N_Y',
            authDomain: 'lofty-cabinet-267422.firebaseapp.com',
            databaseURL: "https://lofty-cabinet-267422-default-rtdb.firebaseio.com/",
            projectId: "lofty-cabinet-267422",
            storageBucket: "lofty-cabinet-267422.appspot.com",
            messagingSenderId: "234677169665"
        };
const app = firebase.initializeApp(firebaseConfig);
const dbRef = app.database().ref();
//var senderId = <?php echo $user_id; ?>;
var senderId = 635;
var cha = '/Friends/' + senderId;
const ref = app.database().ref(cha); //db.ref('server/saving-data/fireblog/posts');
ref.on('value', (snapshot) => {
    console.log('i am here');
            snapshot.forEach(function (childSnapshot) {
                if(messages.includes(childSnapshot.key)){;}
                else{
                    messages.push(childSnapshot.key);
                    console.log(messages);
            var value = childSnapshot.val();
            console.log("Title is : " + value.text);
            if(value.idSender == senderId){
            }
            else
            {   
            }
                }
    myDiv.scrollTop = myDiv.scrollHeight;
        
        });
}, (errorObject) => {
  console.log('The read failed: ' + errorObject.name);
});







console.log(ref);

console.log(senderId);


    </script>

    @yield('scripts')
    @if(Setting::get('demo_mode', 0) == 1)
        <!-- Start of LiveChat (www.livechatinc.com) code -->
        <script type="text/javascript">
            window.__lc = window.__lc || {};
            window.__lc.license = 8256261;
            (function() {
                var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
                lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
            })();
        </script>
        <!-- End of LiveChat code -->
    @endif

</body>
</html>


