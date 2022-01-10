@extends('user.layout.base')

@section('title', 'Chats ')

@section('content')
  <?php
    if(Auth::check())
    {
        if(isset(Auth::user()->avatar)){
        header("Location: https://myroadstar.org/user/login");
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
        header("Location: https://myroadstar.org/provider/login");
        die();
    }
//$user_id =Auth::user()->id;
//session()->put('provider_id',10);
        

?>


<!------   ----------------------------------->
<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h3 class="page-title" style="text-align:center;"><?php echo strtoupper($_GET["user_name"]); ?></h3>
            </div>
        </div>
        <div class="row no-margin">
        <div class="col-md-8 col-md-offset-2">
        <div id="chat" style="min-height:80vh!important; max-height:50vh!important;overflow-x: hidden;overflow-y: auto;">
            </div>
            </div>
            </div>
                                <div class="row -no margin">
                        <div class=" col-md-8 col-md-offset-2 panel-footer">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat" onclick="send_message()">
                                Send</button>
                        </span>
                    </div>
                </div>
                </div>


                
        </div>
          
            </div>
    @endsection

    <!-- Scripts -->
@section('scripts') 
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
var senderId = <?php echo $user_id; ?>;
var receiverId =<?php echo $_GET["user"]; ?>;
var avatar = "<? echo $_GET["avata"]; ?>";
 if(avatar != "" )
 {
     if(avatar.includes("https://myroadstar.org"))
     {;}
     else{
     avatar= "https://myroadstar.org<? echo $_GET["avata"]; ?>" ;
 }
 }
 else
 {
     avatar = "https://myroadstar.org/public/asset/img/avatar.png";
 }
//var senderId = 600;
// var cha = 'Messages/' + senderId + "_"+ receiverId;
// const ref = app.database().ref(cha); //db.ref('server/saving-data/fireblog/posts');
// ref.on('value', (snapshot) => {
//     console.log('i am here');
//             snapshot.forEach(function (childSnapshot) {
//                 if(messages.includes(childSnapshot.key)){;}
//                 else{
//                     messages.push(childSnapshot.key);
//                     console.log(messages);
//             var value = childSnapshot.val();
//             console.log("Title is : " + value.text);
//             document.getElementById("nochat").style.display = "none";
//                     var x= '<div class="containerchat" style="border: 2px solid #dedede;background-color: #f1f1f1;border-radius: 5px;padding: 10px;margin: 10px 0;" ><img src="https://myroadstar.org/public/uploads/user/profile/1636719236.jpeg" alt="Avatar" style="width:100%;float: left;max-width: 60px;width: 100%;margin-right: 20px;border-radius: 50%; "><p>'+ value.text+ '</p></div>';
//             document.getElementById("chat").innerHTML += x;
//                 }
//     //myDiv.scrollTop = myDiv.scrollHeight;
        
//         });
// }, (errorObject) => {
//   console.log('The read failed: ' + errorObject.name);
// });

// if(messages.length == 0)
//         {
//             var x= '<div class="containerchat" id="nochat" style="border:2x solid #dedede;background-color: #f1f1f1;border-radius: 5px;padding:10px;margin: 10px 0;"><p style="text-align:center;"> You have No Messages</p>';
//             document.getElementById("chat").innerHTML += x;
//         }


//----------
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
            var user_image= "{{Auth::user()->picture}}" ;
            if(user_image == "")
            {
                user_image = "https://myroadstar.org/public/asset/img/avatar.png";
            }
            
            if(value.idSender == senderId){
            var x= '<div class="containerchat" style="border: 2px solid #dedede;background-color: #f1f1f1;border-radius: 5px;padding: 10px;margin: 10px 0;min-height:80px;"><img src="'+user_image+'"  style="width:100%;float: left;max-width: 60px;width: 100%;margin-right: 20px;border-radius: 50%; "><p>'+ value.text+ '</p></div>';
            document.getElementById("chat").innerHTML += x;
            }
            else
            {

                
                var x= '<div class="containerchatdarker" style="border-color: #ccc;background-color: #ddd;border-radius: 5px;padding: 10px;margin: 10px 0;  min-height:80px;"><img src="'+avatar+'"  class="right" style="width:100%; float: right;max-width: 60px;width: 100%;margin-left: 20px;border-radius: 50%;"><p class="test">'+value.text +'</p></div>';
                document.getElementById("chat").innerHTML += x;
                
            }
                }
var myDiv = document.getElementById("chat");
    myDiv.scrollTop = myDiv.scrollHeight;
        
        });
}, (errorObject) => {
  console.log('The read failed: ' + errorObject.name);
});
//----------







console.log(ref);

console.log(senderId);

        function send_message() {
    const d = new Date();
    var ms = Date.now();

    console.log("milli");
    console.log(ms);

    var postData = {
    idSender: senderId.toString(),
    idReceiver: receiverId.toString(),
    text: document.getElementById("btn-input").value,
    type: "text",
    timestamp: ms,
    status: "1"
    
  };

  var mes={idSender: senderId.toString(),
    idReceiver: receiverId.toString(),
    text: document.getElementById("btn-input").value,
    type: "text",
    timestamp: ms,
    status: "1"
 };

 
  var postDataMessage = {
    avata: avatar ,
    email: "<? echo $_GET["user_email"]; ?>",
    id: receiverId.toString(),
    name:"<? echo $_GET["user_name"]; ?>",
    message: mes
    
  };

  console.log('postDataMessage');
  console.log(postDataMessage);


  document.getElementById("btn-input").value = "";

  
  // Get a key for a new Post.
  var chat3 = "/Friends/"+senderId+"/"+receiverId+"/"+"message/";
var chat4 = "/Friends/"+receiverId+"/"+senderId+"/"+"message/";
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
  updates[chat3] = mes;
  updates[chat4] = mes;
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



    </script>
@endsection
