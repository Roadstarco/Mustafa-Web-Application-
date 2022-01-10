<!--<!DOCTYPE html>
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
-->
    <!-- CSRF Token -->
<!--    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ Setting::get('site_favicon', asset('favicon.ico')) }}" type="image/x-icon">
    <link rel="icon" href="{{ Setting::get('site_favicon', asset('favicon.ico')) }}" type="image/x-icon">

    <title>@yield('title'){{ Setting::get('site_title', 'Uber') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/> -->
    

    <!-- Styles -->
 <!--   <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/rating.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/dashboard-style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/select2.min.css') }}" rel="stylesheet" type="text/css">

    @yield('styles') -->

    <!-- Scripts -->
 <!--   <script>
        window.Laravel = <?php 
        //echo json_encode([
        //    'csrfToken' => csrf_token(),
       // ]);
         ?>
  </script>
</head>
<body> -->
    <?php
    // echo "<script>
    //     window.Laravel = <?php echo json_encode([
    //         'csrfToken' => csrf_token(),
    //     ]); 
    // </script>";
    // if(Auth::check())
    // {
    //     if(isset(Auth::user()->picture)){
    //     header("Location: https://myroadstar.org/provider/login");
    //     die();
    //     }
    //     else{
    //         $user_id = Auth::user()->id; 
    //     }
    // // var_dump(Auth::user());    
    // // die();
       
    // }
    // else
    // {
    //     header("Location: https://myroadstar.org/provider/login");
    //     die();
    // }
//$user_id =Auth::user()->id;
//session()->put('provider_id',10);
        



?> 



@extends('provider.layout.app')

@section('title', 'Provider Messages')

@section('content')


<!------   ----------------------------------->
<div id="wrapper">
        <div class="overlay" id="overlayer" data-toggle="offcanvas"></div>
        @include('provider.layout.partials.nav')
        <div id="page-content-wrapper">
            @include('provider.layout.partials.header')
            <div class="page-content">
                <div class="pro-dashboard">
                
            <div class="dash-content">
            <div class="row no-margin">
            <div class="col-md-12">
                <h3 class="page-title" style="text-align: center;">Messages</h3>
            </div>
            </div>
            <div class="row no-margin">
            <div class="col-md-8 col-md-offset-2">
            <div id="chat" style="min-height:80vh!important; max-height:50vh!important;overflow-x: hidden;overflow-y: auto;">
            
            
            
            </div>
            </div>
            

            </div>
            </div>
          
            </div>
            
                
            </div>
        </div>
    </div>

    <div id="modal-incoming"></div>

    <!-- Scripts -->
    <script src="/asset/js/jquery.min.js"></script>
<script src="/asset/js/bootstrap.min.js"></script>
<script type="text/javascript"></script>
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
var senderId = {{Auth::user()->id}};
//var senderId = 600;
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
            document.getElementById("nochat").style.display= "none";
            
            var avata = value.avata.replace("https://myroadstar.org","");
            if(avata == "")
            {
                avata = "https://myroadstar.org/public/asset/img/avatar.png";
            }
            console.log("avata");
            console.log(avata);
                    var x= '<div class="containerchat" style="border: 2px solid #dedede;background-color: #f1f1f1;border-radius: 5px;padding: 10px;margin: 10px 0; min-height: 80px;" onclick=window.location="https://myroadstar.org/provider/chat-provider?user='+value.id+'+&user_name='+value.name+'&user_email='+value.email+'&avata='+avata+'"><img src="'+avata+'"  style="width:100%;float: left;max-width: 60px;width: 100%;margin-right: 20px;border-radius: 50%; "><p>'+ value.message.text+ '</p></div>';
            document.getElementById("chat").innerHTML += x;
                }
    //myDiv.scrollTop = myDiv.scrollHeight;
        
        });
}, (errorObject) => {
  console.log('The read failed: ' + errorObject.name);
});

if(messages.length == 0)
        {
            var x= '<div class="containerchat" id="nochat" style="border:2x solid #dedede;background-color: #f1f1f1;border-radius: 5px;padding:10px;margin: 10px 0;"><p style="text-align:center;"> You have No Messages</p>';
            document.getElementById("chat").innerHTML += x;
        }







console.log(ref);

console.log(senderId);


    </script>

    

@endsection