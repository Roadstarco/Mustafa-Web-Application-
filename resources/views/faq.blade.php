@extends('user.layout.app')

@section('title', 'FAQs')
@section('description', 'The ultimate guide to becoming a driver or a passenger, answering all your queries about payment options, routes to choose from and more')

@section('content')

<div class="bg-image page-title">
				<div class="container-fluid">
					<a href="#"><h1>FAQS</h1></a>
					<div class="pull-right">
						<a href="{{url('/')}}"><i class="fa fa-home fa-lg"></i></a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="#">FAQS</a>
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

@endsection