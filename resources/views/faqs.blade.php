@extends('user.layout.app')

@section('styles')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
@endsection

@section('title', 'FAQs')
@section('description', 'The ultimate guide to becoming a driver or a passenger, answering all your queries about payment options, routes to choose from and more')

@section('content')

<div class="row gray-section no-margin" style="background-color: #a8a8c3  !important">
    <div class="container">
        <div class="content-block">
            <div class="panel-group" id="accordion">
                <h2>{{ $title }} by Driver</h2>
                <div class="title-divider"></div>
                <div id="faq" class="section"  style="padding-bottom: 30px !important;">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">How to become a driver?</a>
                            </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Download the driver app on the IOS or android play store or visit our website and upload all required documents and we will contact you within 48 hours</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">What are the required documents?</a>
                            </h4>
                        </div>
                        <div id="collapse2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Valid driver’s permit<br>
                                    Certificate of vehicle insurance<br>
                                    Certify copy of vehicle<br>
                                    Proof of Inspection for vehicle<br>
                                    Letter of authorization (If vehicle is not registered in your name) to be submitted in person at our office. Letter must be accompanied with copy of authorizer ID<br>
                                    Certificate of character no older than 6 months</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">How long does it take for my documents to be verified?</a>
                            </h4>
                        </div>
                        <div id="collapse3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Generally it takes no longer than 48 hours to have your documents verified</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Can I drive for another company?</a>
                            </h4>
                        </div>
                        <div id="collapse4" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Yes you can drive for another company {{Setting::get('site_title','Uber')}} doesn’t limit your ability to earn an extra income elsewhere</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">Can I drive whenever I want?</a>
                            </h4>
                        </div>
                        <div id="collapse5" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Yes you can with {{Setting::get('site_title','Uber')}} you choose your hours of work you are your own boss</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">Can I pickup other passengers while on a trip?</a>
                            </h4>
                        </div>
                        <div id="collapse6" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Under no circumstances this is permitted only booking passengers is allowed on accepted trips</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse7">How old do I have to be to drive?</a>
                            </h4>
                        </div>
                        <div id="collapse7" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>To be a driver you must be 21 years and older</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse8">Is there and age limit for my car</a>
                            </h4>
                        </div>
                        <div id="collapse8" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>We do not accept vehicles which manufacture date is older than 2014</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse9">Do I have to pay a commission?</a>
                            </h4>
                        </div>
                        <div id="collapse9" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Yes you do have to pay a commission presently the rate of commission that we charged is 25% of whatever the driver earns. Commissions can be paid to any FCB Bank via deposit to account number 2559217</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse10">When do i have to pay my Commission?</a>
                            </h4>
                        </div>
                        <div id="collapse10" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Commissions must be paid every Monday of each week, failure to do so may result in your account being deactivated if you have any problems please free to contact the office for any assistance</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse12">I was involve in an accident while on a trip what do I do?</a>
                            </h4>
                        </div>
                        <div id="collapse12" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Check and verify that all passengers are safe<br>
                                    Contact the emergency ambulance services if necessary<br>
                                    Contact {{Setting::get('site_title','Uber')}} operation centre</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse13">I found an item in my car what am I supposed to do</a>
                            </h4>
                        </div>
                        <div id="collapse13" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Please secure such item and inform {{Setting::get('site_title','Uber')}} dispatch operator we will make contact with passenger so that we can arrange a mutual meeting so that item can be handed over to passenger</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse14">My passenger failed to pay what can I do?</a>
                            </h4>
                        </div>
                        <div id="collapse14" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Do not engage or confront passenger for payment, any such behavior may inflame or escalate such situation into a violent confrontation instead contact {{Setting::get('site_title','Uber')}} dispatch centre and provide a report on the incident for investigation</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse15">My passenger cancel in the middle of the trip what do I do?</a>
                            </h4>
                        </div>
                        <div id="collapse15" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>{{Setting::get('site_title','Uber')}} allows every passenger the right to cancel a trip at any given time but if such cancellation is found to be mischievous in nature this may result in the immediate suspension or deletion of passenger account</p>
                            </div>
                        </div>
                    </div>
                </div>

                <h2>{{ $title }} by Passengers</h2>
                <div class="title-divider"></div>
                <div id="faq2" class="section">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse16">How do I book a ride?</a>
                            </h4>
                        </div>
                        <div id="collapse16" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Open the {{Setting::get('site_title','Uber')}} app<br>
                                    Enter your pickup destination in the “Pickup” box. The app may also use your phone’s GPS to automatically prefill your pick up location<br>
                                    Enter you desired destination in the “Drop off” box.<br>
                                    Choose your vehicle of choice<br>
                                    Press the confirm button.<br>
                                    Select number of passengers and include any notes to drivers<br>
                                    Your ride is booked your request is being sent to the nearest driver</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse17">What is your fare?</a>
                            </h4>
                        </div>
                        <div id="collapse17" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Fares may vary base on vehicle selection to get an exact break down of your fare please go to rate card within your app and choose your vehicle to view your fare</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse18">Is there a cancellation fee</a>
                            </h4>
                        </div>
                        <div id="collapse18" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Yes there is please refer to our cancellation policy to get full details</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse19">Can I pay with credit card?</a>
                            </h4>
                        </div>
                        <div id="collapse19" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Yes you can we accept both visa and MasterCard</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse20">How does the wallet feature work?</a>
                            </h4>
                        </div>
                        <div id="collapse20" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Our wallet feature allows you to safely transfer money from your credit card unto our wallet which allows for cashless payments no need for cash or credit card only you mobile phone is required</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse21">Can I book a ride for later or next week?</a>
                            </h4>
                        </div>
                        <div id="collapse21" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Yes you can {{Setting::get('site_title','Uber')}} allows you to book a ride for now, later in the day or at a future date</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse22">What is the SOS emergency button?</a>
                            </h4>
                        </div>
                        <div id="collapse22" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>When selected during an emergency an emergency alert is sent to our dispatch center for response. When this is done we shall immediately initiate contact with passenger if contact is not made the TTPS is contacted with relevant details</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse23">Can I tip my driver?</a>
                            </h4>
                        </div>
                        <div id="collapse23" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Yes you can but remember tipping is not mandatory it just shows appreciation for a job well done</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse24">Can I book a trip with multiple stops?</a>
                            </h4>
                        </div>
                        <div id="collapse24" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>No this option is presently unavailable but we intend to implement such at a later date</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse25">Can I request a specific driver?</a>
                            </h4>
                        </div>
                        <div id="collapse25" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>No our platform can only match you with the nearest driver to your location and not to a specific driver</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse26">What areas do {{Setting::get('site_title','Uber')}} operate?</a>
                            </h4>
                        </div>
                        <div id="collapse26" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>{{Setting::get('site_title','Uber')}} provide it services to all areas but this is base on driver availability and booking times</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse27">Why is it necessary to rate my driver</a>
                            </h4>
                        </div>
                        <div id="collapse27" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Feedback allows for better quality of service your feedback matters to us and we are constantly and consistently working to provide the best possible service available.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection