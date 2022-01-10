<?php

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */
Route::post('/newadmin', 'AdminController@newadmin');
Route::post('/signup', 'UserApiController@signup');
Route::post('/logout', 'UserApiController@logout');
Route::post('/verify', 'UserApiController@verify');

Route::post('/auth/facebook', 'Auth\SocialLoginController@facebookViaAPI');
Route::post('/auth/google', 'Auth\SocialLoginController@googleViaAPI');
Route::post('/forgot/password', 'UserApiController@forgot_password');
Route::post('/reset/password', 'UserApiController@reset_password');

// Route::post('/send/request', 'UserApiController@send_request');
    
Route::group(['middleware' => ['auth:api']], function () {


Route::post('/user/international-trips', 'UserApiController@international_trips');
    
//Route::group(['middleware' => ['web']], function () {
 Route::post('/send/request', 'UserApiController@send_request');
 Route::post('/send/request1', 'UserApiController@send_request1');
  

// re_send_request1
Route::post('/resend/request1', 'UserApiController@re_send_request1');
//recreate_send_request1
Route::post('/recreatesend/request1', 'UserApiController@recreate_send_request1');


//send_support_message
Route::post('/send-support-message-user', 'UserApiController@send_support_message_user');

//Message Notification
  Route::post('/send-message-notification-to-provider', 'UserApiController@send_message_notification_to_provider');

//Track Trip New   track_trip_new
Route::post('/track-trip-new', 'UserApiController@track_trip_new');



    // user profile
    Route::post('/change/password', 'UserApiController@change_password');
    Route::post('/update/location', 'UserApiController@update_location');
    Route::get('/details', 'UserApiController@details');
    Route::post('/update/profile', 'UserApiController@update_profile');
    Route::post('/update/profile/fcm', 'UserApiController@update_fcm');
    // services
    Route::get('/services', 'UserApiController@services');
    // provider
    Route::post('/rate/provider', 'UserApiController@rate_provider');

    // request
    //Route::post('/send/request', 'UserApiController@send_request');
    Route::post('/cancel/request', 'UserApiController@cancel_request');
    Route::get('/request/check', 'UserApiController@request_status_check');
    Route::get('/show/providers', 'UserApiController@show_providers');
    Route::post('/update/request', 'UserApiController@modifiy_request');
    // history
    Route::get('/trips', 'UserApiController@trips');
    Route::get('upcoming/trips', 'UserApiController@upcoming_trips');
    Route::get('/trip/details', 'UserApiController@trip_details');
    Route::get('upcoming/trip/details', 'UserApiController@upcoming_trip_details');



    // payment
    Route::post('/payment', 'PaymentController@payment');
    Route::post('/add/money', 'PaymentController@add_money');
    // estimated
    Route::get('/estimated/fare', 'UserApiController@estimated_fare');
    // help
    Route::get('/help', 'UserApiController@help_details');
    // promocode
    Route::get('/promocodes', 'UserApiController@promocodes');
    Route::post('/promocode/add', 'UserApiController@add_promocode');

    Route::post('/support/message', 'UserApiController@support_message');

    // card payment
    Route::resource('card', 'Resource\CardResource');
    // card payment
    Route::resource('location', 'Resource\FavouriteLocationResource');
    // passbook
    Route::get('/wallet/passbook', 'UserApiController@wallet_passbook');
    Route::get('/promo/passbook', 'UserApiController@promo_passbook');

    Route::post('/apply/referral/code', 'UserApiController@apply_referral_code');
    Route::get('/documents/listing', 'UserApiController@documents_list');
    Route::get('/reward/listing', 'UserApiController@reward_list');
    Route::get('/reward/redeem', 'UserApiController@redeem_reward');
    Route::get('/reward/amount', 'UserApiController@reward_amount');

    //Provider Trips Apis
    Route::post('/travels', 'UserApiController@travels');
    Route::post('/travel-detail', 'UserApiController@travel_detail');

    Route::post('/request-trip', 'UserApiController@request_traveler');
    Route::post('/trip-payment', 'UserApiController@trip_payment');

    Route::post('/rate-trip-provider', 'UserApiController@rate_trip_provider');

//    User Trip Apis
    Route::post('/create-trip', 'UserApiController@create_trip');
    Route::post('/user-trips', 'UserApiController@user_trips');
    Route::post('/user-trips-id', 'UserApiController@user_trips_id');
    
    Route::post('/trip-bids', 'UserApiController@trip_bids');
    Route::post('/accept-bid', 'UserApiController@accept_bid');

    Route::post('/bid/counter-offer', 'UserApiController@bid_counter_offer');
    Route::post('/bid/counter-accept', 'UserApiController@accept_bid_counter');
    Route::post('/bid/counter-reject', 'UserApiController@reject_bid_counter');

 
    //Sea Trip track vessel
    Route::get('/track-vessel', 'Resource\VesselsResource@trackVessel');
    Route::get('/track-flight', 'Resource\AirportsResource@trackFlight');
    //test routes 
    Route::post('/port-call', 'VesselFinderApiTestController@portCall');
    Route::post('/flight-info', 'VesselFinderApiTestController@flightInfo');


});
