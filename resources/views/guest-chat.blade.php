@extends('user.layout.header')

@section('content')


<div class="chat" style="margin-left:20%;margin-right:20%;">
<p>
  <?php
    if(Auth::check())
    {
        if(isset(Auth::user()->avatar)){
        header("Location: https://myroadstar.org/login");
        die();
        }
        else{
            $user_id = Auth::user()->id; 
        }
    // var_dump(Auth::user());    
    // die();
       
    }
    else
    {
        header("Location: https://myroadstar.org/login");
        die();
    }
//$user_id =Auth::user()->id;
//session()->put('provider_id',10);
        



?> 
</p>
<h2 style="text-align:center;">CHAT</h2>
<div id="chat" style="min-height:80vh!important; max-height:80vh!important;overflow-x: hidden;overflow-y: auto;">
</div>


<p id="demo"></p>
<div class="panel-footer">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat" onclick="send_message()">
                                Send</button>
                        </span>
                    </div>
                </div>
</div>
	 <footer>
                <div class="color-part2"></div>
                <div class="color-part"></div>
                <div class="container-fluid">
                    <div class="row block-content">
                        <div class="col-sm-4 wow zoomIn" data-wow-delay="0.3s" style="text-align: center;">
                          <a href="{{url('/')}}"> <img src="{{asset('proassests/media/footerlogo.png')}}" alt="Footerlogo"></a>
                            <p>The advent of online service platforms and mobile smartphone apps have led to increased fast delivery tasks like the speed of light in real time.</p>
                            <div class="footer-icons">
                                <a href="https://www.facebook.com/Daily-Mcqs" target="_blank"><i class="fa fa-facebook-square fa-2x"></i></a>
                                <a href="instagram.com" target="_blank"><i class="fa fa-instagram fa-2x"></i></a>
                            </div>
                        </div>
                        <div class="col-sm-2 wow zoomIn" data-wow-delay="0.3s" style="text-align: center;">
                            <h4>MAIN LINKS</h4>
                            <nav>
                                <a herf="{{url('/faq')}}">FAQ</a>
                                <a herf="{{url('/aboutus')}}">About Us</a>
                                <a href="{{ url('privacy') }}">Privacy Policy</a>
                                <a herf="{{url('/terms-conditions')}}">Terms & Conditions</a>
                                <a href="{{url('/ride')}}">Signup for Delivery</a>
                                <a href="{{url('/drive')}}">Signup As Company</a>
                                
                         <!--       <a href="{{url('/ride')}}">Deliver Now</a>
                                <a href="{{url('/drive')}}">Company</a>
                                <a href="{{url('/ride')}}">Delivery</a> -->
                            </nav>
                        </div>
                        <div class="col-sm-2 wow zoomIn" data-wow-delay="0.3s" style="text-align: center;">
                            <h4>GET APP ON</h4>
                            <nav>
                               <a href="https://play.google.com/store/apps/details?id=com.suffescomroadstarcustomer" target="_blank"> <img src="{{asset('proassests/media/googlestore.png')}}" style="width: 150px; height: 44px;" alt="slider"></a>

                               <a href="https://apps.apple.com/us/app/roadstar-driver/id1507973452" target="_blank"> <img src="{{asset('proassests/media/Playstore.png')}}" style="width: 150px; height: 44px;" alt="slider"> </a>
                            </nav>
                        </div>
                        <div class="col-sm-4 wow zoomIn" data-wow-delay="0.3s" style="text-align: center;">
                            <h4>CONTACT INFO</h4>
                            Everyday is a new day for us and we work really hard to satisfy our customers everywhere.
                            <div class="contact-info" style="text-align: center;">
                                <span><i class="fa fa-location-arrow" style= "float:none;"><strong style="font-weight: 700;
    font-size: 12px;">
                                RoadStar Corporation. <br> </strong></i>13010 Morris Road, Ste. 648. Alpharetta, GA 30004</span>
                                <span><i class="fa fa-phone" style="float:none !important; "<a href="tel:+1 8006 727316"><font color="white">+1 8006 727316</font></a></i></span>
                                <span><i class="fa fa-envelope" style="float:none !important;"><a href="mailto:Support@myroadstar.org"><font color="white">Support@myroadstar.org</font></a>   |  <a href="mailto:Admin@myroadstar.org"> <font color="white">Admin@myroadstar.org</font></a></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="copy text-right"><a id="to-top" href="#this-is-top"><i class="fa fa-chevron-up"></i></a> &copy; 2021 MyRoadStar All rights reserved.</div>
                 </div>
            </footer>
</div>



<!-- <script src="{{asset('asset/js/jquery.min.js')}}"></script>
<script src="{{asset('asset/js/bootstrap.min.js')}}"></script>
<script src="{{asset('asset/js/scripts.js')}}"></script> -->
<!--Main-->   
        <script src="{{asset('proassests/js/jquery-1.11.3.min.js')}}"></script>
        <script src="{{asset('proassests/js/jquery-ui.min.js')}}"></script>
        <script src="{{asset('proassests/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('proassests/js/modernizr.custom.js')}}"></script>
        
        <script src="{{asset('proassests/assets/rendro-easy-pie-chart/dist/jquery.easypiechart.min.js')}}"></script>
        <script src="{{asset('proassests/js/waypoints.min.js')}}"></script>
        <script src="{{asset('proassests/js/jquery.easypiechart.min.js')}}"></script>
        <!-- Loader -->
        <script src="{{asset('proassests/assets/loader/js/classie.js')}}"></script>
        <script src="{{asset('proassests/assets/loader/js/pathLoader.js')}}"></script>
        <script src="{{asset('proassests/assets/loader/js/main.js')}}"></script>
        <script src="{{asset('proassests/js/classie.js')}}"></script>
        <!--Switcher-->
        <script src="{{asset('proassests/assets/switcher/js/switcher.js')}}"></script>
        <!--Owl Carousel-->
        <script src="{{asset('proassests/assets/owl-carousel/owl.carousel.min.js')}}"></script>
        <!-- SCRIPTS -->
        <script type="text/javascript" src="{{asset('proassests/assets/isotope/jquery.isotope.min.js')}}"></script>
        <!--Theme-->
        <script src="{{asset('proassests/js/jquery.smooth-scroll.js')}}"></script>
        <script src="{{asset('proassests/js/wow.min.js')}}"></script>
        <script src="{{asset('proassests/js/jquery.placeholder.min.js')}}"></script>
        <script src="{{asset('proassests/js/smoothscroll.min.js')}}"></script>
        <script src="{{asset('proassests/js/theme.js')}}"></script>
	<script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase-app.js"></script>
      <script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-database.js"></script>

	<script type="text/javascript">
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

// Get a reference to the database service

const dbRef = app.database().ref();


var senderId = {{Auth::user()->id}};
var receiverId = <?php echo $_GET["provider_id"]; ?>;

var avatar = <?php echo $_GET["user_image"]; ?>;


console.log(senderId);
console.log(receiverId);

var chat3 = senderId+'_'+receiverId;

var cha = "Messages/"+senderId+'_'+receiverId;

//const dbRef = firebase.database().ref();

//const dbb = app.database().ref("379_670");

const ref = app.database().ref(cha); //db.ref('server/saving-data/fireblog/posts');
console.log(ref);
// Attach an asynchronous callback to read the data at our posts reference
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
                var user_image= "{{Auth::user()->picture}}" ;
            if(user_image == "")
            {
                user_image = "https://myroadstar.org/public/asset/img/avatar.png";
            }
            var x= '<div class="containerchat" style="border: 2px solid #dedede;background-color: #f1f1f1;border-radius: 5px;padding: 10px;margin: 10px 0; min-height: 80px;"><img src="'+user_image+'"  style="width:100%;float: left;max-width: 60px;width: 100%;margin-right: 20px;border-radius: 50%; "><p>'+ value.text+ '</p></div>';
            document.getElementById("chat").innerHTML += x;
            }
            else
            {
                var x= '<div class="containerchatdarker" style="border-color: #ccc;background-color: #ddd;border-radius: 5px;padding: 10px;margin: 10px 0;min-height:80px;"><img src="'+avatar+'"  class="right" style="width:100%; float: right;max-width: 60px;width: 100%;margin-left: 20px;border-radius: 50%;"><p class="test">'+value.text +'</p></div>';
                document.getElementById("chat").innerHTML += x;
                
            }
                }
var myDiv = document.getElementById("chat");
    myDiv.scrollTop = myDiv.scrollHeight;
        
        });
}, (errorObject) => {
  console.log('The read failed: ' + errorObject.name);
}); 











        


        function send_message() {
//   		var x= '<div class="containerchat" style="border: 2px solid #dedede;background-color: #f1f1f1;border-radius: 5px;padding: 10px;margin: 10px 0;"><img src="https://myroadstar.org/public/uploads/user/profile/1636719236.jpeg" alt="Avatar" style="width:100%;float: left;max-width: 60px;width: 100%;margin-right: 20px;border-radius: 50%; "><p>Hello. How are you today  sam, djkasndlksmad asknalkdmla?</p><span class="time-right">11:00</span></div>';
//   		var y= '<div class="containerchatdarker" style="border-color: #ccc;background-color: #ddd;border-radius: 5px;padding: 10px;margin: 10px 0;"><img src="https://myroadstar.org/public/uploads/user/profile/1636719236.jpeg" alt="Avatar" class="right" style="width:100%; float: right;max-width: 60px;width: 100%;margin-left: 20px;border-radius: 50%;"><p class="test">Hey! I\'m fine. Thanks for asking!</p><span class="time-left">11:01</span></div>';
//  document.getElementById("chat").innerHTML += x;
//  document.getElementById("chat").innerHTML += y; 		
//   		document.getElementById("demo").value = "challo";

  		// }
          



//const database = getDatabase(app,"https://lofty-cabinet-267422-default-rtdb.firebaseio.com/");

//var rootRef = app.getDatabase().ref("/");


// console.log(app);

    const d = new Date();
    let ms = Date.now();

    var postData = {
    idSender: senderId.toString(),
    idReceiver: receiverId.toString(),
    text: document.getElementById("btn-input").value,
    type: "text",
    timestamp: ms,
    status: "1"
    
  };
//------------------------

  var mes={idSender: senderId.toString(),
    idReceiver: receiverId.toString(),
    text: document.getElementById("btn-input").value,
    type: "text",
    timestamp: ms,
    status: "1"
 };
 
    var user_image_sender= "{{Auth::user()->picture}}" ;
            if(user_image_sender == "")
            {
                user_image_sender = "https://myroadstar.org/public/asset/img/avatar.png";
            }
    var name_sender = "{{Auth::user()->first_name}}";
    var sender_email = "{{Auth::user()->email}}";
    var receiver_email = "<?php echo $_GET["user_email"]; ?>";
    var receiver_name =  <?php echo $_GET["user_name"]; ?>;
//  var avatar = "";
//  if(avatar != "")
//  {
//      avatar = "https://myroadstar.org";
//  }
  var postDataMessageSender = {
    avata: avatar ,
    email: receiver_email,
    id: receiverId.toString(),
    name: receiver_name,
    message: mes
    
  };

var postDataMessageReceiver = {
    avata: user_image_sender ,
    email: sender_email,
    id: senderId.toString(),
    name: name_sender,
    message: mes
    
  };
 //-----------------------
  //{{Auth::user()->first_name}}

  var ch1 ="/Friends/"+senderId+"/"+receiverId+"/";
  var ch2 ="/Friends/"+receiverId+"/"+senderId+"/";
  document.getElementById("btn-input").value = "";

  // Get a key for a new Post.
  var newPostKey = app.database().ref().child(senderId+'_'+receiverId).push().key;
    var newPostKey1 = app.database().ref().child(receiverId+'_'+senderId).push().key;
    var chat1 = "/Messages/"+senderId+'_'+receiverId;
    var chat2 = "/Messages/"+receiverId+'_'+senderId;
  // Write the new post's data simultaneously in the posts list and the user's post list.
  var updates = {};
    //   updates['/Chats/' + senderId + '/'  + newPostKey] = postData;
    //   updates['/Chats/' + receiverId + '/' + newPostKey] = postData;
  //updates['/Messages/' + senderId + '/' + newPostKey] = postData;
  updates[chat1 + '/' + newPostKey] = postData;
  updates[chat2 + '/' + newPostKey1] = postData;
  updates[ch1] = postDataMessageSender;
  updates[ch2] = postDataMessageReceiver;
  send_notification();
  return app.database().ref().update(updates);

}



function send_notification()
{
    // var data={'user_id' : receiverId };
    // ajax: {
    //      url: "send-message-notification-provider",
    //      type: "get",
    //      dataType: 'json',
    //      delay: 250,
    //      data: data,
    //      processResults: function (response) {
    //          console.log(response);
    //     //    return {
    //     //       results: $.map(response, function (obj) {
    //     //           console.log(obj);
    //     //         return {
    //     //             text: obj.name,
    //     //             id: obj.marine_traffic_id
    //     //        }
               
    //     //       }),
    //     //    }
    //      },
    //      cache: true
    //     },
//------------------------
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var data={'user_id' : receiverId };
    $.ajax({
            url: '/send-message-notification-provider',
            type: 'get',
            data: data ,
            success: (response) => {
                console.log(response);
            },
            error: (errorResponse) => {

                console.log(errorResponse);
                // console.log(errorResponse.responseJSON.errors);

            }
        });
//------------------------



}

		// chats
		
        
	</script>
@endsection
</body>
</html>