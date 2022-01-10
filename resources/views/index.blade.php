@extends('user.layout.app')

@section('title', 'Home | My Road Star')
@section('description', 'To provide transportation services to the public that is affordable, safe and consistent')

@section('content')
<div id="owl-main-slider" class="owl-carousel enable-owl-carousel" data-single-item="true" data-pagination="false" data-auto-play="true" data-main-slider="true" data-stop-on-hover="true">
                <div class="item">
                    <img src="{{asset('proassests/media/main-slider/1.jpg')}}" alt="slider">
                    <div class="container-fluid">
                        <div class="slider-content col-md-6 col-lg-6">
                            <div style="display:table;">
                           
                                <div style="display:table-cell;">
                                   <h1><i class="fa fa-money"></i> Earn a buck or two by posting your trip on our Dashboard.</h1>
                                </div>
                            </div>
                           <p>We will match you to Senders on our Crowdshipping platform. For Local and International Delivery.<br><a class="btn btn-success" href="{{url('/service')}}">LEARN MORE</a></p>
                        </div>             
                    </div>
                </div>
          </div>
          
            
                  <div class="container-fluid">
                <div class="row column-info block-content">
                   <div class="text-center hgroup wow zoomInUp" data-wow-delay="0.3s">
                    <h1>REGISTRATION</h1>
                   </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 wow fadeInLeft" data-wow-delay="3.3s">
                        <img src="{{asset('proassests/media/3-column-info/1.jpg')}}" alt="slider">
                        <span></span>
                        <h3>Register to Be a Rider</h3>
                        <a class="btn btn-default btn-sm" href="{{url('/register')}}">SIGNUP</a>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 wow fadeInUp" data-wow-delay="3.3s">
                        <img src="{{asset('proassests/media/3-column-info/2.jpg')}}" alt="Img">
                        <span></span>
                        <h3>Register to Be a Driver or Provider</h3>
                        <a class="btn btn-default btn-sm" href="{{url('/ride')}}">SIGNUP</a>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 wow fadeInRight" data-wow-delay="3.3s">
                        <img src="{{asset('proassests/media/3-column-info/3.jpg')}}" alt="Img">
                        <span></span>
                        <h3>Signup As Company</h3>
                        <a class="btn btn-default btn-sm" href="{{url('/fleet/register')}}">SIGNUP</a>
                    </div>
                </div>
            </div>
            
   <hr>
            <div class="big-hr color-1 wow zoomInUp" data-wow-delay="0.3s">
                <div class="text-left" style="margin-right:40px;">
                    <h2>OUR MISSION</h2>
                    <p>To provide transportation services to the public that is affordable, safe and consistent.</p>
                </div>
                <div><a class="btn btn-success btn-lg" href="{{url('/aboutus')}}">ABOUT US</a></div>
            </div>
<br><br>
           

<!-- Page Content -->
<div class="disable">
    <div class="container">

      <!-- Page Heading -->
       <div style="padding-top:20px;" class="text-center hgroup wow zoomInUp" data-wow-delay="0.3s">
                    <h1>WHO WE ARE</h1>
                    <p>MY ROAD STAR</p>
                    <hr style="margin-bottom:-20px;" width="5%">
                   </div>

      <!-- Project One -->
      <div class="row"  style="background-color: #f7f7f7">
        <div class="col-md-5">
          <a href="{{url('/trips')}}">
            <div align="center"><img src="{{asset('proassests/media/courier-man.png')}}" alt="Img"></div>
          </a>
        </div>
        <div style="padding-top:10px;" class="col-md-7">
          <h3>RoadStar</h3>
        <p>We deliver anywhere in the world.</p>
        <p>Under 1 hour local delivery, live route tracking, and real time customer interaction and updates.</p>
        <p>Long distances: The delivery times for long distances, for bulk and larger loads, will vary.</p>
          <a class="btn btn-primary" href="{{url('/trips')}}">Search for available delivery request by Sender</a>
        </div>
      </div>
      <!-- /.row -->

      <hr>

      <!-- Project Two -->
      <div class="row">
        <div style="padding-top:10px;" class="col-md-7">
          <h3>We deliver by road, by air and by waterways including containers on ocean freights over</h3>
          <p>My Road Star is a same-day delivery platform that connects people with stuff to send with drivers already heading in the right direction. And you can pay with either cash or card.</p>
          <a class="btn btn-primary" href="{{url('/')}}">MORE REASONS TO ChOOSE US</a>
        </div>
        <div class="col-md-5">
          <a href="{{url('/')}}">
            <div align="center"><img src="{{asset('proassests/media/plane.png')}}" alt="Img"></div>
          </a>
        </div>
      </div>
      <!-- /.row -->

      <hr>

      <!-- Project Three -->
      <div class="row"  style="background-color: #f7f7f7">
        <div class="col-md-5">
          <a href="{{url('/service')}}">
            <div align="center"><img src="{{asset('proassests/media/parcel.png')}}" alt="Img"></div>
          </a>
        </div>
        <div style="padding-top:30px;" class="col-md-7">
          <h3>Post your load, personal items or parcel for delivery</h3>
          <p>Daily commute. Errand across town. Early morning flight. Late night drinks. Wherever you’re headed, count on My Road Star for a delivery.</p>
          <a class="btn btn-primary" href="{{url('/service')}}">SEE WHAT WE DELIVERs</a>
        </div>
      </div>
      <!-- /.row -->

      <hr>

      <!-- Project Four -->
      <div class="row">
        <div style="padding-top:30px;" class="col-md-7">
          <h3>Post your trips for all travellers with dates</h3>
          <p>Forget expensive couriers and long shipping lines. Whether you’re sending something local or nationwide.My Road Star is an easy, inexpensive way for businesses and individuals to get stuff delivered. The choice is yours.</p>
          <a class="btn btn-primary" href="{{url('/')}}">REASONS TO SELECT US</a>
        </div>
        <div class="col-md-5">
          <a href="{{url('/')}}">
            <div align="center"><img src="{{asset('proassests/media/posttrip.png')}}" alt="Img"></div>
          </a>
        </div>
      </div>
      <!-- /.row -->

      <hr>

      <!-- Project Five -->
      <div class="row"  style="background-color: #f7f7f7">
        <div class="col-md-5">
          <a href="{{url('/')}}">
            <div align="center"><img src="{{asset('proassests/media/behindthewheel.png')}}" alt="Img"></div>
          </a>
        </div>
        <div style="padding-top:10px;" class="col-md-7">
          <h3>Behind the Wheel</h3>
          <h4>They’re people like you, going your way</h4>
          <p>What makes the My Road Star experience truly great are the people behind the wheel. They are Parents. Students and teachers. Veterans. Neighbours. Friends. Our partners drive their own cars—on their own schedule—in cities big and small. Which is why more than one million people worldwide have signed up as a partner.</p>
          <a class="btn btn-primary" href="{{url('/')}}">WHY DRIVE WITH My Road Star</a>
        </div>
      </div>
      <!-- /.row -->

       <hr>

      <!-- Project Six -->
      <div class="row">
        <div style="padding-top:30px;" class="col-md-7">
          <h3>Post your air travel with dates</h3>
          <p>A city with My Road Star has more economic opportunities for residents, fewer drunk drivers on the streets, and better access to transportation for those without it.</p>
          <a class="btn btn-primary" href="{{url('/')}}">OUR LOCAL IMPACT</a>
        </div>
        <div class="col-md-5">
          <a href="{{url('/')}}">
            <div align="center"><img src="{{asset('proassests/media/airtravel.png')}}" alt="Img"></div>
          </a>
        </div>
      </div>
      <!-- /.row -->

      <hr>

      <!-- Project Seven -->
      <div class="row"  style="background-color: #f7f7f7">
        <div class="col-md-5">
          <a href="{{url('/')}}">
            <div align="center"><img src="{{asset('proassests/media/safety.png')}}" alt="Img"></div>
          </a>
        </div>
        <div style="padding-top:30px;" class="col-md-7">
          <h3>Safety Putting First</h3>
          <p>Whether delivery is a parcel or box, every part of the My Road Star experience has been designed around your safety and security.</p>
          <a class="btn btn-primary" href="{{url('/')}}">HOW WE KEEP SAFETY</a>
        </div>
      </div>
      <!-- /.row -->

       <hr>

      <!-- Project Eight -->
      <div class="row">
        <div style="padding-top:30px;" class="col-md-7">
          <h3>Become a Road Star</h3>
          <p>As a delivery driver, you will make money as a full timer or a part timer. You can pick your hours as you like at anytime and at any place.</p>
          <a class="btn btn-primary" href="{{url('/drive')}}">BECOME A ROAD STAR</a>
        </div>
                <div class="col-md-5">
          <a href="{{url('/drive')}}">
            <div align="center"><img src="{{asset('proassests/media/partnership.png')}}" alt="Img"></div>
          </a>
        </div>
      </div>
      <!-- /.row -->
<hr>
    </div>
</div>

    <!-- /.container -->




            <div class="container-fluid block-content">
                <div class="text-center hgroup wow zoomInUp" data-wow-delay="0.3s">
                    <h1>OUR SERVICES</h1>
                    <h2>We have wide network of connections in all major locations to help you with <br> the services we offer</h2>
                </div>
                <div class="row our-services">
                    <div class="col-sm-6 col-md-4 col-lg-4 wow zoomInLeft" data-wow-delay="0.3s">
                        <a href="{{url('/service#sea-delivery-section')}}">
                            <span><i class="glyph-icon flaticon-boats4"></i></span>
                            <h4>SEA DELIVERY</h4>
                            <button type="button" class="btn btn-outline-secondary">Read more</button>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 wow zoomInUp" data-wow-delay="0.3s">
                        <a href="{{url('/service#air-delivery-section')}}">
                            <span><i class="glyph-icon flaticon-flying"></i></span>
                            <h4>AIR DELIVERY</h4>
                            <button type="button" class="btn btn-outline-secondary">Read more</button>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 wow zoomInRight" data-wow-delay="0.3s">
                        <a href="{{url('/service#package-delivery-section')}}">
                            <span><i class="glyph-icon flaticon-package7"></i></span>
                            <h4>PACKAGE DELIVERY</h4>
                           <button type="button" class="btn btn-outline-secondary">Read more</button>
                        </a>
                    </div>
                </div>
            </div>
            
      
           
            <div class="container-fluid block-content">
                <div class="row">
                   <div class="col-md-6 col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                        <div class="hgroup">
                            <h1>FAQS by Driver</h1>
                            <h2>My Road Star</h2>
                        </div>
                        <ul class="why-us">
                            <li>
                                How to become a driver?
                                <p>Download the driver app on the IOS or android play store or visit our website and upload all required documents and we will contact you within 48 hours</p>
                                <span>+</span>
                            </li>
                            <li>
                                What are the required documents?
                                <p>Valid driver’s permit.</p>
                                    <p>Certificate of vehicle insurance.</p>
                                   <p> Certify copy of vehicle.</p>
                                   <p> Proof of Inspection for vehicle.</p>
                                    <p>Letter of authorization (If vehicle is not registered in your name) to be submitted in person at our office. Letter must be accompanied with copy of authorizer ID.</p>
                                    <p>Certificate of character no older than 6 months.</p>
                                <span>+</span>
                            </li>
                            <li>
                                How long does it take for my documents to be verified?
                                <p>Generally it takes no longer than 48 hours to have your documents verified</p>
                                <span>+</span>
                            </li>
                            <li>
                                Can I drive for another company?
                                <p>Yes you can drive for another company My Road Star doesn’t limit your ability to earn an extra income elsewhere</p>
                                <span>+</span>
                            </li>
                            <li>
                                Can I drive whenever I want?
                                <p>Yes you can with My Road Star you choose your hours of work you are your own boss</p>
                                <span>+</span>
                            </li>
                            <li>
                                Can I drive for another company?
                                <p>Yes you can drive for another company My Road Star doesn’t limit your ability to earn an extra income elsewhere</p>
                                <span>+</span>
                            </li>
                            <li>
                                Can I pickup other passengers while on a trip?
                                <p>Under no circumstances this is permitted only booking passengers is allowed on accepted trips</p>
                                <span>+</span>
                            </li>
                            <li>
                                How old do I have to be to drive?
                                <p>To be a driver you must be 21 years and older</p>
                                <span>+</span>
                            </li>
                            <li>
                                Is there and age limit for my car?
                                <p>We do not accept vehicles which manufacture date is older than 2014</p>
                                <span>+</span>
                            </li>
                            <li>
                                Do I have to pay a commission?
                                <p>Yes you do have to pay a commission presently the rate of commission that we charged is 25% of whatever the driver earns. Commissions can be paid to any FCB Bank via deposit to account number 2559217</p>
                                <span>+</span>
                            </li>
                            <li>
                                When do i have to pay my Commission?
                                <p>Commissions must be paid every Monday of each week, failure to do so may result in your account being deactivated if you have any problems please free to contact the office for any assistance</p>
                                <span>+</span>
                            </li>
                            <li>
                                I was involve in an accident while on a trip what do I do?
                                <p>Check and verify that all passengers are safe</p>
                                    <p>Contact the emergency ambulance services if necessary</p>
                                    <p>Contact My Road Star operation centre</p>
                                <span>+</span>
                            </li>
                            <li>
                                I found an item in my car what am I supposed to do
                                <p>Please secure such item and inform My Road Star dispatch operator we will make contact with passenger so that we can arrange a mutual meeting so that item can be handed over to passenger</p>
                                <span>+</span>
                            </li>
                            <li>
                                My passenger failed to pay what can I do?
                                <p>Do not engage or confront passenger for payment, any such behavior may inflame or escalate such situation into a violent confrontation instead contact My Road Star dispatch centre and provide a report on the incident for investigation</p>
                                <span>+</span>
                            </li>
                             <li>
                                My passenger cancel in the middle of the trip what do I do?
                                <p>My Road Star allows every passenger the right to cancel a trip at any given time but if such cancellation is found to be mischievous in nature this may result in the immediate suspension or deletion of passenger account</p>
                                <span>+</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                        <div class="hgroup">
                            <h1>FAQS by Passengers</h1>
                            <h2>My Road Star</h2>
                        </div>
                        <ul class="why-us">
                            <li>
                                How do I book a ride?
                                <p>Open the My Road Star app
                                    <p>Enter your pickup destination in the “Pickup” box. The app may also use your phone’s GPS to automatically prefill your pick up location</p>
                                    <p>Enter you desired destination in the “Drop off” box.</p>
                                    <p>Choose your vehicle of choice</p>
                                    <p>Press the confirm button.</p>
                                    <p>Select number of passengers and include any notes to drivers</p>
                                    <p>Your ride is booked your request is being sent to the nearest driver</p>
                                <span>+</span>
                            </li>
                            <li>
                                What is your fare?
                                <p>Fares may vary base on vehicle selection to get an exact break down of your fare please go to rate card within your app and choose your vehicle to view your fare</p>
                                <span>+</span>
                            </li>
                            <li>
                                Is there a cancellation fee
                                <p>Yes there is please refer to our cancellation policy to get full details

                                </p>
                                <span>+</span>
                            </li>
                            <li>
                                Can I pay with credit card?
                                <p>Yes you can we accept both visa and MasterCard</p>
                                <span>+</span>
                            </li>
                            <li>
                                How does the wallet feature work?
                                <p>Our wallet feature allows you to safely transfer money from your credit card unto our wallet which allows for cashless payments no need for cash or credit card only you mobile phone is required</p>
                                <span>+</span>
                            </li>
                            <li>
                                Can I book a ride for later or next week?
                                <p>Yes you can My Road Star allows you to book a ride for now, later in the day or at a future date</p>
                                <span>+</span>
                            </li>
                            <li>
                                What is the SOS emergency button?
                                <p>When selected during an emergency an emergency alert is sent to our dispatch center for response. When this is done we shall immediately initiate contact with passenger if contact is not made the TTPS is contacted with relevant details</p>
                                <span>+</span>
                            </li>
                            <li>
                                Can I tip my driver?
                                <p>Yes you can but remember tipping is not mandatory it just shows appreciation for a job well done</p>
                                <span>+</span>
                            </li>
                            <li>
                                Can I book a trip with multiple stops?
                                <p>No this option is presently unavailable but we intend to implement such at a later date</p>
                                <span>+</span>
                            </li>
                            <li>
                                Can I request a specific driver?
                                <p>No our platform can only match you with the nearest driver to your location and not to a specific driver</p>
                                <span>+</span>
                            </li>
                            <li>
                                What areas do My Road Star operate?
                                <p>My Road Star provide it services to all areas but this is base on driver availability and booking times</p>
                                <span>+</span>
                            </li>
                            <li>
                                Why is it necessary to rate my driver
                                <p>Feedback allows for better quality of service your feedback matters to us and we are constantly and consistently working to provide the best possible service available.</p>
                                <span>+</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <hr>
            
            
            <div class="block-content bg-image blog-section inner-offset">
                <div class="container-fluid">
                    <div class="hgroup wow fadeInLeft" data-wow-delay="0.3s">
                        <h1 align="center">By thee Power of the Crowd.</h1>
                        <h2 align="center">My Road Star</h2>             
                    </div>
                    <div class="row" align="center">
                    <div class="col-sm-12 col-md-12 col-lg-12 one-news wow fadeInLeft" data-wow-delay="0.3s">
                        <style type="text/css">
                            .input-group .form-control{
                                border-top-left-radius: 6px;
                                border-bottom-left-radius: 6px;
                            }
                        </style>
                            <form action="{{url('trips')}}">
            <div class="input-group find-form">
                <input type="text" name="search" class="form-control" placeholder="Search" />
                <span class="input-group-addon">
                    <button type="submit">
                        <i class="fa fa-arrow-right"></i>
                    </button>  
                </span>
            </div>
        </form>
                        </div>
                    
                    </div>
                </div>
            </div>


<div style="position: fixed; left: 0; bottom: 0; width: 100%; text-align: right; z-index:10" class="chatDiv">
        </div>

<div id="chatHeaderDiv" style="border-radius:10px 10px 0px 0px; position: fixed; right: 0; bottom: 0; margin-right:5px; z-index:10; height:50px;">
          
            <iframe id="chatHeaderIframe" style="border-radius: 10px 10px 0px 0px;" src="https://devsupport.zuptu.systems/ClientSupport/CheckChatSupport?company=Dummy%20Company&type=1" scrolling="no"></iframe>
          
        </div>


        <div id="chatHeaderToggle" style="position:fixed; bottom:12px; right:25px; z-index:11;">
            <i class="icon-arrow-up12" style="font-weight:bold; color:white;" onclick="openChat()" id="iframeToggleIcon">Open</i>
            <i class="icon-spinner spinner" style="font-weight: bold; color: white; display: none;" id="iframeSpinner">Close</i>
        </div>






<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

        function openChat()
        {
            $('#iframeToggleIcon').hide()
            $('#iframeSpinner').show()
            $('.chatDiv').append('<div>'+
                '<i class="icon-cross icon-2x text-bold text-danger" id="chatCloseIcon" onclick="closeChat()">X</i>'+
            '</div>'+
            //'<iframe id="chatIframe" src="https://localhost:44387/ClientSupport/CheckChatSupport?company=Dummy%20Company&type=2" height="550" scrolling="no"></iframe>');
            '<iframe id="chatIframe" src="https://devsupport.zuptu.systems/ClientSupport/CheckChatSupport?company=Dummy%20Company&type=2" height="550" scrolling="no"></iframe>');
            //'<iframe id="chatIframe" src="http://nodlayslahore-001-site3.btempurl.com/ClientSupport/CheckChatSupport?company=Nodlays&type=2" height="550" scrolling="no"></iframe>');

            $('#chatIframe').on('load', function ()
            {
                $("#chatCloseIcon").show();
                $('.chatDiv').collapse('show');
                $("#chatHeaderDiv").hide();
                $("#chatHeaderToggle").hide();

                $('#iframeToggleIcon').show()
                $('#iframeSpinner').hide()

                $('#chatHeaderDiv').empty();
            });


        }


        function closeChat()
        {
            $("#chatCloseIcon").hide();

            //$('#chatHeaderDiv').append('<iframe id="chatHeaderIframe" style="border-radius: 10px 10px 0px 0px;" src="https://localhost:44387/ClientSupport/CheckChatSupport?company=Dummy%20Company&type=1" scrolling="no"></iframe>');
            $('#chatHeaderDiv').append('<iframe id="chatHeaderIframe" style="border-radius: 10px 10px 0px 0px;" src="https://devsupport.zuptu.systems/ClientSupport/CheckChatSupport?company=Dummy%20Company&type=1" scrolling="no"></iframe>');
            //$('#chatHeaderDiv').append('<iframe id="chatHeaderIframe" style="border-radius: 10px 10px 0px 0px;" src="http://nodlayslahore-001-site3.btempurl.com/ClientSupport/CheckChatSupport?company=Nodlays&type=1" scrolling="no"></iframe>');

            $('#chatHeaderIframe').on('load', function ()
            {
                $('.chatDiv').collapse('toggle');
                $('.chatDiv').empty();
                $("#chatHeaderDiv").show();
                $("#chatHeaderToggle").show();
            });

        }

    </script>

           <!--  <style>

    .bs-example{
        margin: 20px;        
    }
</style>
<div class="bs-example">
    <div class="card" style="max-width:100%">
        <div class="row no-gutters">
            <div class="col-sm-7">
            <img src="{{asset('proassests/media/roadstar.jpg')}}" class="card-img-top h-100" alt="img">
            </div>
            <div class="col-sm-5">
                <div class="card-body">
                    <h4 class="card-title">RoadStar</h4>
                    <p class="card-text">We deliver anywhere in the world.</p>

<p class="card-text">Under 1 hour local delivery, live route tracking, and real time customer interaction and updates.</p>

<p class="card-text">Long distances: The delivery times for long distances, for bulk and larger loads, will vary.</p>
                    <a href="#" class="btn btn-primary stretched-link">View Profile</a>
                </div>
            </div>
        </div>
    </div>
</div> -->


           
@endsection