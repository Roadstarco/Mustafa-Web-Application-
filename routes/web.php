<?php

/*
  |--------------------------------------------------------------------------
  | User Authentication Routes
  |--------------------------------------------------------------------------
 */
// Route::post('chat', 'ChatController@store')->name('chat.store');
// Route::post('chat/join', 'ChatController@join')->name('chat.join');

Auth::routes();

Route::get('auth/facebook', 'Auth\SocialLoginController@redirectToFaceBook');
Route::get('auth/facebook/callback', 'Auth\SocialLoginController@handleFacebookCallback');
Route::get('auth/google', 'Auth\SocialLoginController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\SocialLoginController@handleGoogleCallback');
Route::post('account/kit', 'Auth\SocialLoginController@account_kit')->name('account.kit');

/*
  |--------------------------------------------------------------------------
  | Provider Authentication Routes
  |--------------------------------------------------------------------------
 */

Route::group(['prefix' => 'provider'], function () {

    Route::get('auth/facebook', 'Auth\SocialLoginController@providerToFaceBook');
    Route::get('auth/google', 'Auth\SocialLoginController@providerToGoogle');

    Route::get('/login', 'ProviderAuth\LoginController@showLoginForm');
    Route::post('/login', 'ProviderAuth\LoginController@login');
    Route::post('/logout', 'ProviderAuth\LoginController@logout');

    Route::get('/register', 'ProviderAuth\RegisterController@showRegistrationForm');
    Route::post('/register', 'ProviderAuth\RegisterController@register');

    Route::post('/password/email', 'ProviderAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'ProviderAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'ProviderAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'ProviderAuth\ResetPasswordController@showResetForm');
    

});

/*
  |--------------------------------------------------------------------------
  | Admin Authentication Routes
  |--------------------------------------------------------------------------
 */

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout');
    Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm');
    Route::post('/register', 'AdminAuth\RegisterController@register');

    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

/*
  |--------------------------------------------------------------------------
  | Dispatcher Authentication Routes
  |--------------------------------------------------------------------------
 */

Route::group(['prefix' => 'dispatcher'], function () {
    Route::get('/login', 'DispatcherAuth\LoginController@showLoginForm');
    Route::post('/login', 'DispatcherAuth\LoginController@login');
    Route::post('/logout', 'DispatcherAuth\LoginController@logout');

    Route::post('/password/email', 'DispatcherAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'DispatcherAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'DispatcherAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'DispatcherAuth\ResetPasswordController@showResetForm');
});

/*
  |--------------------------------------------------------------------------
  | Fleet Authentication Routes
  |--------------------------------------------------------------------------
 */


Route::group(['prefix' => 'fleet'], function () {
    Route::get('/login', 'FleetAuth\LoginController@showLoginForm');
    Route::post('/login', 'FleetAuth\LoginController@login');
    Route::post('/logout', 'FleetAuth\LoginController@logout');
    Route::get('/register', 'FleetAuth\RegisterController@showRegistrationForm');
    Route::post('/register', 'FleetAuth\RegisterController@register');

    Route::post('/password/email', 'FleetAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'FleetAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'FleetAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'FleetAuth\ResetPasswordController@showResetForm');
});

/*
  |--------------------------------------------------------------------------
  | Account Authentication Routes
  |--------------------------------------------------------------------------
 */


Route::group(['prefix' => 'account'], function () {
    Route::get('/login', 'AccountAuth\LoginController@showLoginForm');
    Route::post('/login', 'AccountAuth\LoginController@login');
    Route::post('/logout', 'AccountAuth\LoginController@logout');

    Route::get('/register', 'AccountAuth\RegisterController@showRegistrationForm');
    Route::post('/register', 'AccountAuth\RegisterController@register');

    Route::post('/password/email', 'AccountAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'AccountAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'AccountAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'AccountAuth\ResetPasswordController@showResetForm');
});

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
 */

Route::get('/', function () {
    // return view("index2");
    return view('index');
});

Route::get('/initsetup', function () {
    return Setting::all();
});

// Route::get('/ride', function () {
//     return view('ride');
// });

Route::get('/ride', 'PagesController@ride');

Route::get('/drive', function () {
    return view('drive');
});

Route::get('privacy', function () {
    $page = 'page_privacy';
    $title = 'Privacy Policy';
    return view('static', compact('page', 'title'));
});

//old
// Route::get('terms_and_conditions', function () {
//     $page = 'terms_and_conditions';
//     $title = 'Terms and Conditions';
//     return view('static', compact('page', 'title'));
// });


Route::get('/terms-conditions', function () {
    return view('terms-conditions');
});

Route::get('contact-us', function () {
    $page = 'contact_us';
    $title = 'Contact Us';
    return view('contact-us', compact('page', 'title'));
});

//old

// Route::get('about-us', function () {
//     $page = 'about_us';
//     $title = 'About Us';
//     return view('about-us', compact('page', 'title'));
// });

Route::get('/aboutus', function () {
    return view('aboutus');
});

// Route::get('/guest-chat', function () {
//     return view('guest-chat');
// });

// Route::get('/message-provider', function () {
//     return view('message-provider');
// });
// Route::get('/message-user', function () {
//     return view('message-user');
// });
// Route::get('/chat-provider', function () {
//     return view('chat-provider');
// });
Route::get('/chat-user', function () {
    return view('chat-user');
});

Route::get('/aboutus', function () {
    return view('aboutus');
});



//faq old

// Route::get('FAQS', function () {
//     $page = 'faqs';
//     $title = 'FAQS';
//     return view('faqs', compact('page', 'title'));
// });

Route::get('/faq', function () {
    return view('faq');
});

/*
  |--------------------------------------------------------------------------
  | User Routes
  |--------------------------------------------------------------------------
 */

Route::get('/dashboard', 'HomeController@index');

// user profiles
Route::get('/profile', 'HomeController@profile');
Route::get('/edit/profile', 'HomeController@edit_profile');
Route::post('/profile', 'HomeController@update_profile');

Route::get('/message-user', 'HomeController@message_user');

Route::get('/guest-chat', 'HomeController@guest_chat');

// update password
Route::get('/change/password', 'HomeController@change_password');
Route::post('/change/password', 'HomeController@update_password');

// ride
//Route::get('/confirm/ride', 'RideController@confirm_ride');
// added by nabeel hassan
Route::get('/confirm/ride', 'RideController@confirm_ride');
// added by nabeel hassan end

Route::post('/create/ride', 'RideController@create_ride');
Route::post('/recreate/ride', 'RideController@recreate_ride');
Route::post('/cancel/ride', 'RideController@cancel_ride');
Route::get('/onride', 'RideController@onride');
Route::post('/payment', 'PaymentController@payment');
Route::post('/rate', 'RideController@rate');

// status check
Route::get('/status', 'RideController@status');

// trips
Route::get('/mytrips', 'HomeController@trips');
Route::get('/upcoming/trips', 'HomeController@upcoming_trips');

// wallet
Route::get('/wallet', 'HomeController@wallet');
Route::post('/add/money', 'PaymentController@add_money');

// payment
Route::get('/payment', 'HomeController@payment');

// card
Route::resource('card', 'Resource\CardResource');

// promotions
Route::get('/promotions', 'HomeController@promotions_index')->name('promocodes.index');
Route::post('/promotions', 'HomeController@promotions_store')->name('promocodes.store');

// promotions
Route::get('/trips', 'TripsController@index');
Route::get('/trip-detail/{id}', 'TripsController@detail');
Route::get('/post-trip/', 'TripsController@trip_form');
Route::post('/save-trip/', 'TripsController@save');
Route::post('/search-trips/', 'TripsController@search_trips');



Route::get('/international-trips', 'HomeController@user_trips');
Route::get('/international-trip/create', 'HomeController@create_trip');
Route::post('/international-trip/store', 'HomeController@store_trip');

Route::get('/international-trip/{id}/bids', 'HomeController@trip_bids')->name('international-trip.bids');
Route::post('/bid/accept', 'HomeController@accept_bid');

Route::post('/international-trip/rate-user', 'HomeController@rate_trip_provider')->name('international-trip.rate-user');

// User Trips
Route::get('/provider-trips', 'HomeController@travels')->name('international-trips.provider');
Route::get('/provider-trip//bid/create/{id}', 'HomeController@create_bid')->name('international-trip.bid.add');
Route::post('/provider-trip/bid/store/', 'HomeController@store_bid')->name('international-trip.bid.store');

Route::post('/bid/counter', 'HomeController@bid_counter_offer');
Route::get('/bid/offer-accept', 'HomeController@accept_bid_counter')->name('bid.counter.accept');
Route::get('/bid/offer-reject', 'HomeController@reject_bid_counter')->name('bid.counter.reject');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');

    Artisan::call('config:cache');

    return "Cache is cleared";
});

//old

// Route::get('/services', function () {
//     return view('services');
// });

Route::get('/service', function () {
    return view('service');
});
Route::get('/travel_form', function () {
    return view('travel_form');
});


Route::get('/send_invoice_email', 'ProviderResources\TripController@send_invoice_email');

//Sea Trip track vessel
Route::get('/track-vessel1', 'Resource\VesselsResource@trackVessel1');
Route::get('/send-message-notification-user','Resource\VesselsResource@send_message_notification_user');
Route::get('/send-message-notification-provider','Resource\VesselsResource@send_message_notification_provider');
Route::get('/track-vessel', 'Resource\VesselsResource@trackVessel');
Route::get('/track-flight', 'Resource\AirportsResource@trackFlight');

