<?php

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/messages', function () {
//     return view('messages');
// });
Route::get('/', 'ProviderController@index')->name('index');
Route::get('/trips', 'ProviderResources\TripController@history')->name('trips');

Route::get('/incoming', 'ProviderResources\TripController@index')->name('incoming');
Route::post('/request/{id}', 'ProviderController@accept')->name('accept');
Route::patch('/request/{id}', 'ProviderController@update')->name('update');
Route::post('/request/{id}/rate', 'ProviderController@rating')->name('rating');
Route::delete('/request/{id}', 'ProviderController@reject')->name('reject');

Route::get('/earnings', 'ProviderController@earnings')->name('earnings');
Route::get('/upcoming', 'ProviderController@upcoming_trips')->name('upcoming');
Route::post('/cancel', 'ProviderController@cancel')->name('cancel');

Route::resource('documents', 'ProviderResources\DocumentController');

Route::get('/upload_documents', 'ProviderResources\DocumentController@upload_documents');
Route::post('/save_documents', 'ProviderResources\DocumentController@save_documents');

Route::get('/profile', 'ProviderResources\ProfileController@show')->name('profile.index');

Route::get('/message-provider', 'ProviderResources\ProfileController@message_provider')->name('message-provider');
Route::get('/chat-provider', 'ProviderResources\ProfileController@chat_provider')->name('chat-provider');


Route::get('/messages', 'ProviderResources\ProfileController@showMessages');//->middleware('web');


Route::post('/profile', 'ProviderResources\ProfileController@store')->name('profile.update');

Route::get('/location', 'ProviderController@location_edit')->name('location.index');
Route::post('/location', 'ProviderController@location_update')->name('location.update');

Route::get('/profile/password', 'ProviderController@change_password')->name('change.password');
Route::post('/change/password', 'ProviderController@update_password')->name('password.update');

Route::post('/profile/available', 'ProviderController@available')->name('available');


Route::get('/local-trips', 'ProviderResources\ProfileController@local_trips')->name('profile.local_trips');

Route::get('/international-trips', 'ProviderResources\ProfileController@int_trips')->name('profile.int_trips');
Route::get('/international-trips/add/{id}', 'ProviderResources\ProfileController@add_bid')->name('international-trip.bid.add');
Route::post('/international-trips/save-bid', 'ProviderResources\ProfileController@store_bid')->name('international-trip.bid.store');
Route::get('/international-trip/{status}/update-status', 'ProviderResources\ProfileController@update_trip_status')->name('international-trip.update-status');
//international trip of service type see  start
Route::post('/international-trip/service-type-see/start', 'ProviderResources\ProfileController@international_trip_service_type_see_start')->name('international-trip.service-type-see.start');

//international trip of service type air  start
Route::post('/international-trip/service-type-air/start', 'ProviderResources\ProfileController@international_trip_service_type_air_start')->name('international-trip.service-type-air.start');

Route::post('/international-trip/rate-user', 'ProviderResources\ProfileController@rate_trip_user')->name('international-trip.rate-user');

Route::get('/international-trip/{id}/bids', 'ProviderResources\ProfileController@trip_bids')->name('international-trip.bids');

Route::get('/international-trips-requests/{id}', 'ProviderResources\ProfileController@international_trips_request_detail')->name('profile.international-trips-requests.show');

Route::post('/bid/accept', 'ProviderResources\ProfileController@accept_bid');

// Route::post('/test', 'ProviderResources\ProfileController@international_trip_service_type_see_start')->name('test');


Route::post('/bid/counter-offer', 'ProviderResources\ProfileController@bid_counter_offer');
Route::get('/bid/counter-accept', 'ProviderResources\ProfileController@accept_bid_counter')->name('bid.counter.accept');
//  Route::get('/bid/counter-accept', function(){
//      dd("hello")
//  })->name('bid.counter.accept');

Route::get('/bid/counter-reject', 'ProviderResources\ProfileController@reject_bid_counter')->name('bid.counter.reject');

Route::resource('/vessels', 'Resource\VesselsResource');
Route::resource('/ports', 'Resource\PortsResource');
Route::resource('/airports', 'Resource\AirportsResource');

