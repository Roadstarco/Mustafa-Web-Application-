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

// Authentication
Route::post('/register', 'ProviderAuth\TokenController@register');
Route::post('/register-new', 'ProviderAuth\TokenController@register_new');
Route::post('/oauth/token', 'ProviderAuth\TokenController@authenticate');
Route::post('/logout', 'ProviderAuth\TokenController@logout');
Route::post('/verify', 'ProviderAuth\TokenController@verify');

Route::post('/trip-details-new','ProviderResources\TripController@trip_details_new');

Route::post('/sendnotificationtouser', 'UserApiController@send_notification_to_user');

// Route::post('/auth/facebook', 'ProviderAuth\TokenController@facebookViaAPI');
// Route::post('/auth/google', 'ProviderAuth\TokenController@googleViaAPI');

Route::post('/forgot/password', 'ProviderAuth\TokenController@forgot_password');
Route::post('/reset/password', 'ProviderAuth\TokenController@reset_password');



Route::group(['middleware' => ['provider.api']], function () {

    Route::post('/refresh/token', 'ProviderAuth\TokenController@refresh_token');

    Route::group(['prefix' => 'profile'], function () {

Route::post('/update/profile/fcm', 'ProviderResources\ProfileController@update_fcm');
        Route::get('/', 'ProviderResources\ProfileController@index');
        Route::post('/', 'ProviderResources\ProfileController@update');
        Route::post('/password', 'ProviderResources\ProfileController@password');
        Route::post('/location', 'ProviderResources\ProfileController@location');
        Route::post('/available', 'ProviderResources\ProfileController@available');
    });


    Route::get('/target', 'ProviderResources\ProfileController@target');
    Route::resource('trip', 'ProviderResources\TripController');
    Route::post('cancel', 'ProviderResources\TripController@cancel');
    Route::post('summary', 'ProviderResources\TripController@summary');
    Route::get('help', 'ProviderResources\TripController@help_details');
    Route::get('/documents/listing', 'ProviderResources\DocumentController@documents_listing');
    Route::post('/documents/upload', 'ProviderResources\DocumentController@documents_upload');
    Route::get('/documents/display-provider-documents', 'ProviderResources\DocumentController@documents_listing_with_name');

    // Route::post('/support/message', 'ProviderController@support_message');

        Route::post('/support/message', 'ProviderResources\TripController@support_message');
        //send-message-notification-to-user
        Route::post('/send-message-notification-to-user', 'ProviderResources\TripController@send_message_notification_to_user');


        //send-support-message-provider
Route::post('/send-support-message-provider', 'ProviderResources\TripController@send_support_message_provider');





    Route::group(['prefix' => 'trip'], function () {

        Route::post('{id}', 'ProviderResources\TripController@accept');
        Route::post('{id}/rate', 'ProviderResources\TripController@rate');
        Route::post('{id}/message', 'ProviderResources\TripController@message');
        Route::post('{id}/calculate', 'ProviderResources\TripController@calculate_distance');
    });

    Route::group(['prefix' => 'requests'], function () {

        Route::get('/upcoming', 'ProviderResources\TripController@scheduled');
        Route::get('/history', 'ProviderResources\TripController@history');
        Route::get('/history/details', 'ProviderResources\TripController@history_details');
        Route::get('/upcoming/details', 'ProviderResources\TripController@upcoming_details');
    });

    Route::post('/post-trip', 'ProviderResources\TripController@post_trip');
    Route::post('/provider-trips', 'ProviderResources\TripController@provider_trips');
    Route::post('/trip-bids', 'ProviderResources\TripController@trip_bids');
    Route::post('/update-bid', 'ProviderResources\TripController@update_bid');

    Route::post('/rate-trip-user', 'ProviderResources\TripController@rate_trip_user');

    Route::post('/update-trip', 'ProviderResources\TripController@update_trip');
    Route::post('/bid-user-trip', 'ProviderResources\TripController@bid_user_trip');

    Route::post('/bid/counter-offer', 'ProviderResources\TripController@bid_counter_offer');
    Route::post('/bid/counter-accept', 'ProviderResources\TripController@accept_bid_counter');
    Route::post('/bid/counter-reject', 'ProviderResources\TripController@reject_bid_counter');

    Route::resource('/vessels', 'Resource\VesselsResource');
    Route::resource('/ports', 'Resource\PortsResource');
    Route::resource('/airports/select2', 'Resource\AirportsResource@airportsSelect2');
    Route::resource('/airports', 'Resource\AirportsResource');

});
