<?php

namespace App\Http\Controllers;

use App\Document;
use App\Http\Controllers\SendPushNotification;
use App\ReferralCodeAmount;
use App\RewardAmount;
use App\SupportMessage;
//use Request;
//use App\UserRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use App\Mail\TripInvoice;
//use app\Mail\forgotpasswordmail;
use Log;
use Auth;
use Hash;
use Storage;
use Setting;
use Exception;
use Notification;
use Carbon\Carbon;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;
use App\Card;
use App\User;
use App\Provider;
use App\Settings;
use App\Promocode;
use App\ServiceType;
use App\UserRequests;
use App\RequestFilter;
use App\PromocodeUsage;
use App\WalletPassbook;
use App\PromocodePassbook;
use App\ProviderService;
use App\UserRequestRating;
use App\Http\Controllers\ProviderResources\TripController;
use Illuminate\Http\Request;
use App\Trip;
use App\TripRequests;
use App\TripPayments;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeInvalidRequestError;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;



class UserApiController extends Controller {


    public function login(Request $request)
    {
        $email = $request->email;
        $pass = $request->password;
        $user = DB::table('users')->where('email',$email)->first();
        if($user->login_by == 'facebook')
        {
            if($pas == $user->social_unique_id)
            {
                return response()->json($User);
            }
        }
        else if ($user->login_by == 'google')
        {
            if($pas == $user->social_unique_id)
            {
                return response()->json($User);
            }
        } 
        else
        {
            if(!Hash::check($pass, $user->password)){
            return response()->json(['success'=>false, 'message' => 'Login Fail, pls check password']);
            }
        }
    }




    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request) {

        // $this->validate($request, [
        //     'social_unique_id' => ['required_if:login_by,facebook,google', 'unique:users'],
        //     'device_type' => 'required|in:android,ios',
        //     'device_token' => 'required',
        //     'device_id' => 'required',
        //     'login_by' => 'required|in:manual,facebook,google',
        //     'first_name' => 'required|max:255',
        //     'last_name' => 'required|max:255',
        //     'email' => 'required|email|max:255|unique:users',
        //     'mobile' => 'required',
        //     'password' => 'required|min:6',
        //     'country_name' => 'required',
        //     'address' => 'required',
        //         // 'picture' => 'mimes:jpeg,bmp,png',
        // ]);

        try {

            $User = new User();

            $User->email = $request->email;

            $User->mobile = $request->mobile;
            $User->last_name = $request->last_name;
            $User->first_name = $request->first_name;
            $User->login_by = $request->login_by;
            $User->device_id = $request->device_id;
            $User->device_token = $request->device_token;
            $User->device_type = $request->device_type;
            $User->social_unique_id = $request->social_unique_id;
            $User->address = $request->address;
            $User->country_name = $request->country_name;

            if ($request->picture != "") {

                $fileName = time() . '.' . $request->picture->extension();
                $request->picture->move(public_path('uploads/user/profile'), $fileName);

                $User->picture = url('public/uploads/user/profile/' . $fileName);
            }

            $User->payment_mode = 'CASH';
            $User->referral_code = rand();
            $User->password = bcrypt($request->password);
            $User->save();

            return response()->json($User);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }



    public function international_trips(){
        $user_trips = Trip::with('sea_trip_estimated_arrival')->with('air_trip_flight_info')->where('user_id', Auth::user()->id)
                        ->orderBy('id', 'DESC')->get();
        
        foreach($user_trips as $user_trip)
        {
            if($user_trip->provider_id!=0)
            {
                $provider=Provider::find($user_trip->provider_id);

                $user_trip["avatar"]=$provider->avatar;
            }

            else
            {
                $user_trip["avatar"]=null;
            }
        }
        if (!$user_trips->isEmpty()) {
            return response()->json($user_trips);
        } else {
            return response()->json(['error' => "No trips available to show."], 500);
        }
    }


    public function update_fcm(Request $request){
        try {

            $user = DB::table('users')->where('id', Auth::user()->id)->update(['device_token' => $request->fcm]);
            return response()->json(['message' => 'Token Updated successfully!']);
        } catch (Exception $e) {

            return $e->getMessage();
        }
}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        try {
            User::where('id', $request->id)->update(['device_id' => '', 'device_token' => '']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function change_password(Request $request) {

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'old_password' => 'required',
        ]);

        $User = Auth::user();

        if (Hash::check($request->old_password, $User->password)) {
            $User->password = bcrypt($request->password);
            $User->save();

            if ($request->ajax()) {
                return response()->json(['message' => trans('api.user.password_updated')]);
            } else {
                return back()->with('flash_success', 'Password Updated');
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.user.change_password')], 500);
            } else {
                return back()->with('flash_error', trans('api.user.change_password'));
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_location(Request $request) {

        $this->validate($request, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($user = User::find(Auth::user()->id)) {

            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->save();

            return response()->json(['message' => trans('api.user.location_updated')]);
        } else {

            return response()->json(['error' => trans('api.user.user_not_found')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request) {

        $this->validate($request, [
            'device_type' => 'in:android,ios',
        ]);

        try {

            if ($user = User::find(Auth::user()->id)) {

                if ($request->has('device_token')) {
                    $user->device_token = $request->device_token;
                }

                if ($request->has('device_type')) {
                    $user->device_type = $request->device_type;
                }

                if ($request->has('device_id')) {
                    $user->device_id = $request->device_id;
                }

                $user->save();

                $user->currency = Setting::get('currency');
                $user->sos = Setting::get('sos_number', '911');
                $user->marine_traffic_api_key=Setting::get('marine_traffic_api_key', '1ffd1c15b665ea045e4f9884b230dc70d892c74f');
                return $user;
            } else {
                return response()->json(['error' => trans('api.user.user_not_found')], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request) {

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'email' => 'email|unique:users,email,' . Auth::user()->id,
            'mobile' => 'required',
            // 'picture' => 'mimes:jpeg,bmp,png',
            'country_name' => 'required',
            'address' => 'required'
        ]);

        try {

            $user = User::findOrFail(Auth::user()->id);

            if ($request->has('first_name')) {
                $user->first_name = $request->first_name;
            }

            if ($request->has('last_name')) {
                $user->last_name = $request->last_name;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('mobile')) {
                $user->mobile = $request->mobile;
            }

            if ($request->has('country_name')) {
                $user->country_name = $request->country_name;
            }

            if ($request->has('address')) {
                $user->address = $request->address;
            }

            if ($request->picture != "") {
                Storage::delete($user->picture);

                $fileName = time() . '.' . $request->picture->extension();
                $request->picture->move(public_path('uploads/user/profile'), $fileName);

                $user->picture = url('public/uploads/user/profile/' . $fileName);
            }

            $user->save();

            if ($request->ajax()) {
                return response()->json($user);
            } else {
                return back()->with('flash_success', trans('api.user.profile_updated'));
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans('api.user.user_not_found')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function services() {

        if ($serviceList = ServiceType::where('id','<>',14)->get()) {
            return $serviceList;
        } else {
            return response()->json(['error' => trans('api.services_not_found')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_request1(Request $request) {
//var_dump($request);
     /*   $this->validate($request, [
            's_latitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
            'promo_code' => 'exists:promocodes,promo_code',
            'distance' => 'required|numeric',
            'use_wallet' => 'numeric',
            'payment_mode' => 'required|in:CASH,CARD,PAYPAL',
            'card_id' => ['required_if:payment_mode,CARD', 'exists:cards,card_id,user_id,' . Auth::user()->id],
        ]);
*/
    //    var_dump(Auth::user()->id);
       // var_dump($request->all());
        //Log::info('New Request from User: ' . Auth::user()->id);
        //Log::info('Request Details:', $request->all());

        $uid = Auth::user()->id;
         $ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();
//  var_dump("Active Requests");
//  var_dump($ActiveRequests);
//  var_dump(Auth::user()->id);
        //dd($ActiveRequests);
    
         if ($ActiveRequests > 0) {   
    //         if ($request->ajax()) {
        if($request->has('from_web'))
        {
         return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later');   
        }
        else{
                 return response()->json(['error' => trans('api.ride.request_inprogress')]);
                 }
    //         } else {
    //              return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later');
    //         }
        }

        //DB::table('user_requests')->where('user_id',$uid)->where()

    

        if ($request->has('schedule_date') && $request->has('schedule_time')) {
            $beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subHour(1);
            $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addHour(1);

            $CheckScheduling = UserRequests::where('status', 'SCHEDULED')
                    ->where('user_id', $uid)  //web
                    ->whereBetween('schedule_at', [$beforeschedule_time, $afterschedule_time])
                    ->count();
// var_dump("CheckScheduling");
// var_dump($CheckScheduling);
        

            if ($CheckScheduling > 0) {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                } else {
                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                }
            }
        }
        //var_dump($request->has('schedule_date') && $request->has('schedule_time'));
        

        $distance = Setting::get('provider_search_radius', '10');
        $latitude = $request->s_latitude;
        $longitude = $request->s_longitude;
        $service_type = $request->service_type;
        //  var_dump("distance");
        // var_dump($distance);
        // var_dump($service_type);
// var_dump((6371 * acos( cos( radians($latitude) ) * cos( radians($latitude) ) * cos( radians($longitude) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians($latitude) ) ) ));
        $Providers = Provider::with('service')
                ->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"), 'id')
                ->where('status', 'approved')
                 ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->whereHas('service', function($query) use ($service_type) {
               //     var_dump("iamhere");
//                    $query->where('status', 'active');
                    $query->where('service_type_id', $service_type);
                })
                ->orderBy('distance', 'asc')
                //->take(10)
                ->get();
                // var_dump("providers");
                // var_dump(sizeof($Providers));
                for($u=0;$u<sizeof($Providers);$u++)
                {
       //         var_dump($Providers[$u]->id);
                }
                
     //           var_dump($Providers[0]->id);
       //         var_dump(count($Providers));
        // List Providers who are currently busy and add them to the filter list.


       Log::info("available providers list is");
       Log::info($Providers);
//var_dump($Providers);
        if (count($Providers) == 0) {
           // if ($request->ajax()) {
                // Push Notification to User
            //     var_dump("no providers");
               if($request->has('from_web'))
               {
                //   $input = request()->all();

                  //return back()->with('flash_success_recreate', 'No Providers Found! Please try again.');
                  return back()->with('flash_success_recreate', 'No Providers Found! Please try again.'); 
               }
               else{
               
               return response()->json(['message' => trans('api.ride.no_providers_found')]);
               }
            // } else {
            //     // var_dump("no providers");

            //     return back()->with('flash_success', 'No Providers Found! Please try again.');
            // }
        }
        
        try {
            $route_key = "asdasd";
            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $request->s_latitude . "," . $request->s_longitude . "&destination=" . $request->d_latitude . "," . $request->d_longitude . "&mode=driving&key=" . Setting::get('map_key');

           $json = curl($details);
                
           $details = json_decode($json, TRUE);

           $route_key = $details['routes'][0]['overview_polyline']['points'];
        //     var_dump($route_key);
        //    die('here');
            $UserRequest = new UserRequests;

            $UserRequest->category = $request->category;
            $UserRequest->product_type = $request->product_type;
            $UserRequest->product_weight = $request->product_weight;

             

            if ($request->attachment1 != "") {
                $fileName = time() . '1.' . $request->attachment1->extension();
                $request->attachment1->move(public_path('uploads/user/request/attachments'), $fileName);

                $UserRequest->attachment1 = url('public/uploads/user/request/attachments/' . $fileName);
            }
            if ($request->attachment2 != "") {
                $fileName = time() . '2.' . $request->attachment2->extension();
                $request->attachment2->move(public_path('uploads/user/request/attachments'), $fileName);

                $UserRequest->attachment2 = url('public/uploads/user/request/attachments/' . $fileName);
            }
            if ($request->attachment3 != "") {
                $fileName = time() . '3.' . $request->attachment3->extension();
                $request->attachment3->move(public_path('uploads/user/request/attachments'), $fileName);

                $UserRequest->attachment3 = url('public/uploads/user/request/attachments/' . $fileName);
            }

            


            $UserRequest->instruction = $request->instruction;
            $UserRequest->receiver_name = $request->receiver_name;
            $UserRequest->receiver_phone = $request->receiver_phone;

            $UserRequest->booking_id = Helper::generate_booking_id();
            $UserRequest->user_id = $uid; //Auth::user()->id;  //here web

            //*************Updates by nabeel 27-01-21***************


            if ((Setting::get('manual_request', 0) == 0) && (Setting::get('broadcast_request', 0) == 0)) {
                // if (Setting::get('broadcast_request', 0) == 1) {
                Log::info("broadcast_request is equal to 1 ");

                $UserRequest->current_provider_id = $Providers[0]->id;
                $test['current_provider_id'] = $Providers[0]->id;
            } else {
                $UserRequest->current_provider_id = 0;
                $test['current_provider_id'] = 0;
                $test['provider_id'] = 0;
            }

            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->payment_mode = $request->payment_mode;
            $test['service_type_id'] = $request->service_type;
            $test['payment_mode'] = $request->payment_mode;
            $UserRequest->status = 'SEARCHING';
            $test['status'] = 'SEARCHING';
            $UserRequest->s_address = $request->s_address ?: "";
            $UserRequest->d_address = $request->d_address ?: "";

            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;

            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->distance = $request->distance;


            $UserRequest->product_distribution = $request->product_distribution;
            $UserRequest->product_width = $request->product_width;
            $UserRequest->product_height = $request->product_height;
            $UserRequest->weight_unit = $request->weight_unit;

                        $test['s_address'] = $request->s_address ?: "";
            $test['d_address'] = $request->d_address ?: "";

            $test['s_latitude'] = $request->s_latitude;
            $test['s_longitude'] = $request->s_longitude;

            $test['d_latitude'] = $request->d_latitude;
            $test['d_longitude'] = $request->d_longitude;
            $test['distance'] = $request->distance;


            $test['product_distribution'] = $request->product_distribution;
            $test['product_width'] = $request->product_width;
            $test['product_height'] = $request->product_height;
            $test['weight_unit'] = $request->weight_unit;


            // if (Auth::user()->wallet_balance > 0) {
            //     $UserRequest->use_wallet = $request->use_wallet ?: 0;
            //     $test['use_wallet'] = $request->use_wallet ?: 0;
            // }
            //remove this 
            $test['use_wallet'] = 0;

            if (Setting::get('track_distance', 0) == 1) {
                $UserRequest->is_track = "YES";
                 $test['is_track'] = "YES";
            }

            $UserRequest->assigned_at = Carbon::now();
            $test['assigned_at'] = Carbon::now();
           // $UserRequest->route_key = $route_key;

            if ($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                $UserRequest->surge = 1;
                $test['surge'] = 1;
            }

            if ($request->has('schedule_date') && $request->has('schedule_time')) {
                $UserRequest->schedule_at = date("Y-m-d H:i:s", strtotime("$request->schedule_date $request->schedule_time"));
                $test['schedule_at'] = $UserRequest->schedule_at;
            }

            //*************Updates by nabeel 27-01-21***************

            if ((Setting::get('manual_request', 0) == 0) && (Setting::get('broadcast_request', 0) == 0)) {
                // if (Setting::get('broadcast_request', 0) == 1) {
                    Log::info("broadcast_request is equal to 1 2");

                Log::info('New Request id : ' . $UserRequest->id . ' Assigned to provider : ' . $UserRequest->current_provider_id);
                
                (new SendPushNotification)->IncomingRequest($Providers[0]->id);
            }

            //$UserRequest->save();
            //UserRequests::($UserRequest);

            
            $test['category'] = $request->category;
            $test['product_type'] = $request->product_type;
            $test['product_weight'] = $request->product_weight;
            $test['instruction'] = $request->instruction;
            $test['receiver_name'] = $request->receiver_name;
            $test['receiver_phone'] = $request->receiver_phone;

            $test['booking_id'] = Helper::generate_booking_id();
            
            $test['user_id'] = $uid;//. here web.   Auth::user()->id;

            $test['service_type_id'] = $request->service_type;
            $test['payment_mode'] = $request->payment_mode;

            $test['status'] = 'SEARCHING';

            $test['s_address'] = $request->s_address ?: "";
            $test['d_address'] = $request->d_address ?: "";

            $test['s_latitude'] = $request->s_latitude;
            $test['s_longitude'] = $request->s_longitude;

            $test['d_latitude'] = $request->d_latitude;
            $test['d_longitude'] = $request->d_longitude;
            $test['distance'] = $request->distance;


          //  $test['product_distribution'] = $request->product_distribution;
            $test['product_width'] = $request->product_width;
            $test['product_height'] = $request->product_height;
            $test['weight_unit'] = $request->weight_unit;
            //die('here');
            //var_dump($test);
            //var_dump(UserRequests::create($test));
           // $check= new UserRequests($test);
           // $check->insert($test);
       //     $UserRequest->save();
            //return back()->with('success_msg', 'Trip Details Saved Successfully');
//var_dump("User Request");            
//$check =
//$check =  DB::table('user_requests')->where('user_id',615)->first();
//$check =  DB::table('provider_devices')->where('provider_id', 371)->first();

//$c=DB::table('user_requests')->insert($test);
//var_dump($c);
//var_dump($test);
//die('here');
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
//die($test['provider_id']);
$a1 = "";
$a2 = "";
$a3 = "";
if($request->attachment1 != "")
{
    $a1 = $UserRequest->attachment1;
}
else
{
    $a1=Null;
}
if($request->attachment2 != "")
{
    $a2 = $UserRequest->attachment2;
}
else{
    $a2=Null;
}
if($request->attachment3 != "")
{
    $a3 = $UserRequest->attachment3;
}
else
{
    $a3=Null;
}

$check = DB::table('user_requests')->insertGetId(['provider_id' => 0,
'booking_id' => $test['booking_id'],
        'user_id' => $uid,
        'current_provider_id'=> 0,
        'service_type_id'=> $request->service_type,
        'status'=> "SEARCHING",
        'payment_mode'=> $request->payment_mode,
        'is_track'=> "YES",
        'distance'=> $request->distance,
        's_latitude'=> $request->s_latitude, 
        'd_latitude'=> $request->d_latitude,
        's_longitude'=> $request->s_longitude,
        'd_longitude'=> $request->d_longitude,
        's_address'=>$request->s_address,
        'd_address'=>$request->d_address,
        'use_wallet'=> $test['use_wallet'],
        'attachment1'=>$a1,
        'attachment2'=>$a2,
        'attachment3'=>$a3,
        'surge'=> 1,
        'category' => $request->category,
         'product_type'=> $request->product_type, 
         'product_width'=> $request->product_width,
          'product_height'=> $request->product_height,
          'weight_unit'=> $request->weight_unit,
          'product_distribution'=> $request->product_distribution,
           'product_weight'=> $request->product_weight,
            'instruction'=> $request->instruction,
             'receiver_name'=> $request->receiver_name,
              'receiver_phone'=> $request->receiver_phone,
              'route_key'=>$route_key]);
       //       die('here');

//die('User sdsdsd');
//var_dump("PROVIDER");
//var_dump($Providers[0]->id);
//die('here');
for($i=0;$i<count($Providers);$i++){
            $ttt = $Providers[$i]->id;
            //$ProviderDevice = DB::table( DB::raw("SELECT * FROM provider_devices WHERE provider_id = '$ttt'") );
            //$ProviderDevice = DB::table('provider_devices')->where('provider_id', $ttt)->first();
            
           // Log::info("*******************Fine here before creating request filter***********************************");
           // Log::info($ttt);
//            $aa = $this->push_fcm($ProviderDevice->token, 'New Booking Request', 'Please open the app to accept booking');
            $aa = $this->send_notification_to_provider_request($ttt,'New Booking Request', 'Please open the app to accept booking');
           // die('here');
}



            // update payment mode



            User::where('id', $uid)->update(['payment_mode' => $request->payment_mode]);

            if ($request->has('card_id')) {


        //  $tc =      Card::where('user_id', Auth::user()->id)->update(['is_default' => 0]);
          $tcc =     Card::where('card_id', $request->card_id)->update(['is_default' => 1]);
            }



            
            //*************Updates by nabeel 27-01-21***************

            if (Setting::get('manual_request', 0) == 0) {
                // if (Setting::get('broadcast_request', 0) == 1) {
//var_dump('manual');
                    Log::info("broadcast_request is equal to 1 3");
                foreach ($Providers as $key => $Provider) {

                    // if (Setting::get('broadcast_request', 0) == 1) {
                    //     (new SendPushNotification)->IncomingRequest($Provider->id);
                    // }

                    $Filter = new RequestFilter;
                    // Send push notifications to the first provider
                    // incoming request push to provider

                    $Filter->request_id = $check;
                    $Filter->provider_id = $Provider->id;
//   var_dump($check);
//   var_dump($Provider->id);                  
                    $Filter->save();
                    // var_dump("providerid");
                    // var_dump($check);
                    // var_dump($Provider->id);
                    // var_dump($Filter->id);
                    
             //       die('filter');
                }
            }
            // die()
//die($request->ajax());
        //  if ($request->wantsJson()) {
            if($request->has('from_web'))
            {
             return redirect('dashboard');   
            }
            else{
                return response()->json([
                            'message' => 'New request Created!',
                            'request_id' => $check,
                            'current_provider' => 0
                ]);}
        //   } else {


        //       return redirect('dashboard');
        //   }
            
        } catch (Exception $e) {
            if ($request->ajax()) {

                return response()->json(['error' => trans('api.something_went_wrong') . $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.' . $e->getMessage());
            }
        // } 
       // var_dump("EXCEPTION");
        //var_dump($e);
        }
        
    }

 /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_request(Request $request) {
//var_dump($request);
     /*   $this->validate($request, [
            's_latitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
            'promo_code' => 'exists:promocodes,promo_code',
            'distance' => 'required|numeric',
            'use_wallet' => 'numeric',
            'payment_mode' => 'required|in:CASH,CARD,PAYPAL',
            'card_id' => ['required_if:payment_mode,CARD', 'exists:cards,card_id,user_id,' . Auth::user()->id],
        ]);
*/
    //    var_dump(Auth::user()->id);
       // var_dump($request->all());
        //Log::info('New Request from User: ' . Auth::user()->id);
        //Log::info('Request Details:', $request->all());

        $uid = Auth::user()->id;
         $ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();
//  var_dump("Active Requests");
//  var_dump($ActiveRequests);
//  var_dump(Auth::user()->id);
        //dd($ActiveRequests);
    
    //     if ($ActiveRequests > 0) {   
    //         if ($request->ajax()) {
    //             return response()->json(['error' => trans('api.ride.request_inprogress')],500);
    //         } else {
    //              return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later');
    //         }
    //    }

        if ($request->has('schedule_date') && $request->has('schedule_time')) {
            $beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subHour(1);
            $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addHour(1);

            $CheckScheduling = UserRequests::where('status', 'SCHEDULED')
                    ->where('user_id', $uid)  //web
                    ->whereBetween('schedule_at', [$beforeschedule_time, $afterschedule_time])
                    ->count();
// var_dump("CheckScheduling");
// var_dump($CheckScheduling);
        

            if ($CheckScheduling > 0) {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                } else {
                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                }
            }
        }
        //var_dump($request->has('schedule_date') && $request->has('schedule_time'));
        

        $distance = Setting::get('provider_search_radius', '10');
        $latitude = $request->s_latitude;
        $longitude = $request->s_longitude;
        $service_type = $request->service_type;
        //  var_dump("distance");
        // var_dump($distance);
        // var_dump($service_type);
// var_dump((6371 * acos( cos( radians($latitude) ) * cos( radians($latitude) ) * cos( radians($longitude) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians($latitude) ) ) ));
        $Providers = Provider::with('service')
                ->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"), 'id')
                ->where('status', 'approved')
                // ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->whereHas('service', function($query) use ($service_type) {
               //     var_dump("iamhere");
                    $query->where('status', 'active');
                    $query->where('service_type_id', $service_type);
                })
                ->orderBy('distance', 'asc')
                //->take(10)
                ->get();
                // var_dump("providers");
                // var_dump(sizeof($Providers));
                for($u=0;$u<sizeof($Providers);$u++)
                {
       //         var_dump($Providers[$u]->id);
                }
                
     //           var_dump($Providers[0]->id);
       //         var_dump(count($Providers));
        // List Providers who are currently busy and add them to the filter list.


       Log::info("available providers list is");
       Log::info($Providers);
//var_dump($Providers);
        if (count($Providers) == 0) {
           // if ($request->ajax()) {
                // Push Notification to User
            //     var_dump("no providers");
               return response()->json(['message' => trans('api.ride.no_providers_found')]);
            // } else {
            //     // var_dump("no providers");
            //     return back()->with('flash_success', 'No Providers Found! Please try again.');
            // }
        }
        
        try {
            $route_key = "asdasd";
            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $request->s_latitude . "," . $request->s_longitude . "&destination=" . $request->d_latitude . "," . $request->d_longitude . "&mode=driving&key=" . Setting::get('map_key');

           $json = curl($details);
                
           $details = json_decode($json, TRUE);

           $route_key = $details['routes'][0]['overview_polyline']['points'];
        //     var_dump($route_key);
        //    die('here');
            $UserRequest = new UserRequests;

            $UserRequest->category = $request->category;
            $UserRequest->product_type = $request->product_type;
            $UserRequest->product_weight = $request->product_weight;

             

            if ($request->attachment1 != "") {
                $fileName = time() . '1.' . $request->attachment1->extension();
                $request->attachment1->move(public_path('uploads/user/request/attachments'), $fileName);

                $UserRequest->attachment1 = url('public/uploads/user/request/attachments/' . $fileName);
            }
            if ($request->attachment2 != "") {
                $fileName = time() . '2.' . $request->attachment2->extension();
                $request->attachment2->move(public_path('uploads/user/request/attachments'), $fileName);

                $UserRequest->attachment2 = url('public/uploads/user/request/attachments/' . $fileName);
            }
            if ($request->attachment3 != "") {
                $fileName = time() . '3.' . $request->attachment3->extension();
                $request->attachment3->move(public_path('uploads/user/request/attachments'), $fileName);

                $UserRequest->attachment3 = url('public/uploads/user/request/attachments/' . $fileName);
            }

            


            $UserRequest->instruction = $request->instruction;
            $UserRequest->receiver_name = $request->receiver_name;
            $UserRequest->receiver_phone = $request->receiver_phone;

            $UserRequest->booking_id = Helper::generate_booking_id();
            $UserRequest->user_id = $uid; //Auth::user()->id;  //here web

            //*************Updates by nabeel 27-01-21***************


            if ((Setting::get('manual_request', 0) == 0) && (Setting::get('broadcast_request', 0) == 0)) {
                // if (Setting::get('broadcast_request', 0) == 1) {
                Log::info("broadcast_request is equal to 1 ");

                $UserRequest->current_provider_id = $Providers[0]->id;
                $test['current_provider_id'] = $Providers[0]->id;
            } else {
                $UserRequest->current_provider_id = 0;
                $test['current_provider_id'] = 0;
                $test['provider_id'] = 0;
            }

            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->payment_mode = $request->payment_mode;
            $test['service_type_id'] = $request->service_type;
            $test['payment_mode'] = $request->payment_mode;
            $UserRequest->status = 'SEARCHING';
            $test['status'] = 'SEARCHING';
            $UserRequest->s_address = $request->s_address ?: "";
            $UserRequest->d_address = $request->d_address ?: "";

            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;

            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->distance = $request->distance;


            $UserRequest->product_distribution = $request->product_distribution;
            $UserRequest->product_width = $request->product_width;
            $UserRequest->product_height = $request->product_height;
            $UserRequest->weight_unit = $request->weight_unit;

                        $test['s_address'] = $request->s_address ?: "";
            $test['d_address'] = $request->d_address ?: "";

            $test['s_latitude'] = $request->s_latitude;
            $test['s_longitude'] = $request->s_longitude;

            $test['d_latitude'] = $request->d_latitude;
            $test['d_longitude'] = $request->d_longitude;
            $test['distance'] = $request->distance;


            $test['product_distribution'] = $request->product_distribution;
            $test['product_width'] = $request->product_width;
            $test['product_height'] = $request->product_height;
            $test['weight_unit'] = $request->weight_unit;


            // if (Auth::user()->wallet_balance > 0) {
            //     $UserRequest->use_wallet = $request->use_wallet ?: 0;
            //     $test['use_wallet'] = $request->use_wallet ?: 0;
            // }
            //remove this 
            $test['use_wallet'] = 0;

            if (Setting::get('track_distance', 0) == 1) {
                $UserRequest->is_track = "YES";
                 $test['is_track'] = "YES";
            }

            $UserRequest->assigned_at = Carbon::now();
            $test['assigned_at'] = Carbon::now();
           // $UserRequest->route_key = $route_key;

            if ($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                $UserRequest->surge = 1;
                $test['surge'] = 1;
            }

            if ($request->has('schedule_date') && $request->has('schedule_time')) {
                $UserRequest->schedule_at = date("Y-m-d H:i:s", strtotime("$request->schedule_date $request->schedule_time"));
                $test['schedule_at'] = $UserRequest->schedule_at;
            }

            //*************Updates by nabeel 27-01-21***************

            if ((Setting::get('manual_request', 0) == 0) && (Setting::get('broadcast_request', 0) == 0)) {
                // if (Setting::get('broadcast_request', 0) == 1) {
                    Log::info("broadcast_request is equal to 1 2");

                Log::info('New Request id : ' . $UserRequest->id . ' Assigned to provider : ' . $UserRequest->current_provider_id);
                
                (new SendPushNotification)->IncomingRequest($Providers[0]->id);
            }

            //$UserRequest->save();
            //UserRequests::($UserRequest);

            
            $test['category'] = $request->category;
            $test['product_type'] = $request->product_type;
            $test['product_weight'] = $request->product_weight;
            $test['instruction'] = $request->instruction;
            $test['receiver_name'] = $request->receiver_name;
            $test['receiver_phone'] = $request->receiver_phone;

            $test['booking_id'] = Helper::generate_booking_id();
            
            $test['user_id'] = $uid;//. here web.   Auth::user()->id;

$test['service_type_id'] = $request->service_type;
            $test['payment_mode'] = $request->payment_mode;

            $test['status'] = 'SEARCHING';

            $test['s_address'] = $request->s_address ?: "";
            $test['d_address'] = $request->d_address ?: "";

            $test['s_latitude'] = $request->s_latitude;
            $test['s_longitude'] = $request->s_longitude;

            $test['d_latitude'] = $request->d_latitude;
            $test['d_longitude'] = $request->d_longitude;
            $test['distance'] = $request->distance;


          //  $test['product_distribution'] = $request->product_distribution;
            $test['product_width'] = $request->product_width;
            $test['product_height'] = $request->product_height;
            $test['weight_unit'] = $request->weight_unit;
            //die('here');
            //var_dump($test);
            //var_dump(UserRequests::create($test));
           // $check= new UserRequests($test);
           // $check->insert($test);
       //     $UserRequest->save();
            //return back()->with('success_msg', 'Trip Details Saved Successfully');
//var_dump("User Request");            
//$check =
//$check =  DB::table('user_requests')->where('user_id',615)->first();
//$check =  DB::table('provider_devices')->where('provider_id', 371)->first();

//$c=DB::table('user_requests')->insert($test);
//var_dump($c);
//var_dump($test);
//die('here');
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
//die($test['provider_id']);
$a1 = "";
$a2 = "";
$a3 = "";
if($request->attachment1 != "")
{
    $a1 = $UserRequest->attachment1;
}
else
{
    $a1=Null;
}
if($request->attachment2 != "")
{
    $a2 = $UserRequest->attachment2;
}
else{
    $a2=Null;
}
if($request->attachment3 != "")
{
    $a3 = $UserRequest->attachment3;
}
else
{
    $a3=Null;
}

$check = DB::table('user_requests')->insertGetId(['provider_id' => 0,
'booking_id' => $test['booking_id'],
        'user_id' => $uid,
        'current_provider_id'=> 0,
        'service_type_id'=> $request->service_type,
        'status'=> "SEARCHING",
        'payment_mode'=> $request->payment_mode,
        'is_track'=> "YES",
        'distance'=> $request->distance,
        's_latitude'=> $request->s_latitude, 
        'd_latitude'=> $request->d_latitude,
        's_longitude'=> $request->s_longitude,
        'd_longitude'=> $request->s_latitude,
        'use_wallet'=> $test['use_wallet'],
        'attachment1'=>$a1,
        'attachment2'=>$a2,
        'attachment3'=>$a3,
        'surge'=> 1,
        'category' => $request->category,
         'product_type'=> $request->product_type, 
         'product_width'=> $request->product_width,
          'product_height'=> $request->product_height,
          'weight_unit'=> $request->weight_unit,
          'product_distribution'=> $request->product_distribution,
           'product_weight'=> $request->product_weight,
            'instruction'=> $request->instruction,
             'receiver_name'=> $request->receiver_name,
              'receiver_phone'=> $request->receiver_phone,
              'route_key'=>$route_key]);
       //       die('here');

//die('User sdsdsd');
//var_dump("PROVIDER");
//var_dump($Providers[0]->id);
//die('here');
for($i=0;$i<count($Providers);$i++){
            $ttt = $Providers[$i]->id;
            //$ProviderDevice = DB::table( DB::raw("SELECT * FROM provider_devices WHERE provider_id = '$ttt'") );
            //$ProviderDevice = DB::table('provider_devices')->where('provider_id', $ttt)->first();
            
           // Log::info("*******************Fine here before creating request filter***********************************");
           // Log::info($ttt);
//            $aa = $this->push_fcm($ProviderDevice->token, 'New Booking Request', 'Please open the app to accept booking');
            $aa = $this->send_notification_to_provider_request($ttt,'New Booking Request', 'Please open the app to accept booking');
           // die('here');
}



            // update payment mode



          //  User::where('id', Auth::user()->id)->update(['payment_mode' => $request->payment_mode]);

            if ($request->has('card_id')) {

        //  $tc =      Card::where('user_id', Auth::user()->id)->update(['is_default' => 0]);
          $tcc =     Card::where('card_id', $request->card_id)->update(['is_default' => 1]);
            }



            
            //*************Updates by nabeel 27-01-21***************

            if (Setting::get('manual_request', 0) == 0) {
                // if (Setting::get('broadcast_request', 0) == 1) {
//var_dump('manual');
                    Log::info("broadcast_request is equal to 1 3");
                foreach ($Providers as $key => $Provider) {

                    // if (Setting::get('broadcast_request', 0) == 1) {
                    //     (new SendPushNotification)->IncomingRequest($Provider->id);
                    // }

                    $Filter = new RequestFilter;
                    // Send push notifications to the first provider
                    // incoming request push to provider

                    $Filter->request_id = $check;
                    $Filter->provider_id = $Provider->id;
//   var_dump($check);
//   var_dump($Provider->id);                  
                    $Filter->save();
                    // var_dump("providerid");
                    // var_dump($check);
                    // var_dump($Provider->id);
                    // var_dump($Filter->id);
                    
             //       die('filter');
                }
            }
            // die()
//die($request->ajax());
        //  if ($request->wantsJson()) {
            if($request->has('web'))
            {
             return redirect('dashboard');   
            }
            else{
                return response()->json([
                            'message' => 'New request Created!',
                            'request_id' => $check,
                            'current_provider' => 0
                ]);}
        //   } else {


        //       return redirect('dashboard');
        //   }
            
        } catch (Exception $e) {
            if ($request->ajax()) {

                return response()->json(['error' => trans('api.something_went_wrong') . $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.' . $e->getMessage());
            }
        // } 
       // var_dump("EXCEPTION");
        //var_dump($e);
        }
        
    }


public function send_request2(Request $request) {
//var_dump("i am here");
     /*   $this->validate($request, [
            's_latitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
            'promo_code' => 'exists:promocodes,promo_code',
            'distance' => 'required|numeric',
            'use_wallet' => 'numeric',
            'payment_mode' => 'required|in:CASH,CARD,PAYPAL',
            'card_id' => ['required_if:payment_mode,CARD', 'exists:cards,card_id,user_id,' . Auth::user()->id],
        ]);
*/
       // var_dump(Auth::user()->id);
       // var_dump($request->all());
        //Log::info('New Request from User: ' . Auth::user()->id);
        //Log::info('Request Details:', $request->all());


        $ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();
// var_dump("Active Requests");
// var_dump($ActiveRequests);
        //dd($ActiveRequests);
    
        if ($ActiveRequests > 0) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.ride.request_inprogress')], 500);
            } else {
                return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later');
            }
        }

        if ($request->has('schedule_date') && $request->has('schedule_time')) {
            $beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subHour(1);
            $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addHour(1);

            $CheckScheduling = UserRequests::where('status', 'SCHEDULED')
                    ->where('user_id', Auth::user()->id)
                    ->whereBetween('schedule_at', [$beforeschedule_time, $afterschedule_time])
                    ->count();
// var_dump("CheckScheduling");
// var_dump($CheckScheduling);
        

            if ($CheckScheduling > 0) {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                } else {
                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                }
            }
        }
        var_dump($request->has('schedule_date') && $request->has('schedule_time'));
        

        $distance = Setting::get('provider_search_radius', '10');
        $latitude = $request->s_latitude;
        $longitude = $request->s_longitude;
        $service_type = $request->service_type;
        //  var_dump("distance");
        // var_dump($distance);
        // var_dump($service_type);
// var_dump((6371 * acos( cos( radians($latitude) ) * cos( radians($latitude) ) * cos( radians($longitude) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians($latitude) ) ) ));
        $Providers = Provider::with('service')
                ->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"), 'id')
                ->where('status', 'approved')
                // ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->whereHas('service', function($query) use ($service_type) {
               //     var_dump("iamhere");
                    $query->where('status', 'active');
                    $query->where('service_type_id', $service_type);
                })
                ->orderBy('distance', 'asc')
                //->take(10)
                ->get();
                // var_dump("providers");
                // var_dump(sizeof($Providers));
                for($u=0;$u<sizeof($Providers);$u++)
                {
                var_dump($Providers[$u]->id);
                }
     //           var_dump($Providers[0]->id);
       //         var_dump(count($Providers));
        // List Providers who are currently busy and add them to the filter list.


       Log::info("available providers list is");
       Log::info($Providers);
//var_dump($Providers);
        if (count($Providers) == 0) {
            if ($request->ajax()) {
                // Push Notification to User
                var_dump("no providers");
              //  return response()->json(['message' => trans('api.ride.no_providers_found')]);
            } else {
                var_dump("no providers");
                //return back()->with('flash_success', 'No Providers Found! Please try again.');
            }
        }

        try {

        //     $details = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $request->s_latitude . "," . $request->s_longitude . "&destination=" . $request->d_latitude . "," . $request->d_longitude . "&mode=driving&key=" . Setting::get('map_key');

        //    $json = curl($details);

        //    $details = json_decode($json, TRUE);

        //    $route_key = $details['routes'][0]['overview_polyline']['points'];

            $UserRequest = new UserRequests;

            $UserRequest->category = $request->category;
            $UserRequest->product_type = $request->product_type;
            $UserRequest->product_weight = $request->product_weight;

             

            // if ($request->attachment1 != "") {
            //     $fileName = time() . '1.' . $request->attachment1->extension();
            //     $request->attachment1->move(public_path('uploads/user/request/attachments'), $fileName);

            //     $UserRequest->attachment1 = url('public/uploads/user/request/attachments/' . $fileName);
            // }
            // if ($request->attachment2 != "") {
            //     $fileName = time() . '2.' . $request->attachment2->extension();
            //     $request->attachment2->move(public_path('uploads/user/request/attachments'), $fileName);

            //     $UserRequest->attachment2 = url('public/uploads/user/request/attachments/' . $fileName);
            // }
            // if ($request->attachment3 != "") {
            //     $fileName = time() . '3.' . $request->attachment3->extension();
            //     $request->attachment3->move(public_path('uploads/user/request/attachments'), $fileName);

            //     $UserRequest->attachment3 = url('public/uploads/user/request/attachments/' . $fileName);
            // }

            


            $UserRequest->instruction = $request->instruction;
            $UserRequest->receiver_name = $request->receiver_name;
            $UserRequest->receiver_phone = $request->receiver_phone;

            $UserRequest->booking_id = Helper::generate_booking_id();
            $UserRequest->user_id = Auth::user()->id;

            //*************Updates by nabeel 27-01-21***************


            if ((Setting::get('manual_request', 0) == 0) && (Setting::get('broadcast_request', 0) == 0)) {
                // if (Setting::get('broadcast_request', 0) == 1) {
                Log::info("broadcast_request is equal to 1 ");

                $UserRequest->current_provider_id = $Providers[0]->id;
            } else {
                $UserRequest->current_provider_id = 0;
            }

            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->payment_mode = $request->payment_mode;

            $UserRequest->status = 'SEARCHING';

            $UserRequest->s_address = $request->s_address ?: "";
            $UserRequest->d_address = $request->d_address ?: "";

            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;

            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->distance = $request->distance;


            $UserRequest->product_distribution = $request->product_distribution;
            $UserRequest->product_width = $request->product_width;
            $UserRequest->product_height = $request->product_height;
            $UserRequest->weight_unit = $request->weight_unit;




            if (Auth::user()->wallet_balance > 0) {
                $UserRequest->use_wallet = $request->use_wallet ?: 0;
            }

            if (Setting::get('track_distance', 0) == 1) {
                $UserRequest->is_track = "YES";
            }

            $UserRequest->assigned_at = Carbon::now();
           // $UserRequest->route_key = $route_key;

            if ($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                $UserRequest->surge = 1;
            }

            if ($request->has('schedule_date') && $request->has('schedule_time')) {
                $UserRequest->schedule_at = date("Y-m-d H:i:s", strtotime("$request->schedule_date $request->schedule_time"));
            }

            //*************Updates by nabeel 27-01-21***************

            if ((Setting::get('manual_request', 0) == 0) && (Setting::get('broadcast_request', 0) == 0)) {
                // if (Setting::get('broadcast_request', 0) == 1) {
                    Log::info("broadcast_request is equal to 1 2");

                Log::info('New Request id : ' . $UserRequest->id . ' Assigned to provider : ' . $UserRequest->current_provider_id);
                (new SendPushNotification)->IncomingRequest($Providers[0]->id);
            }

          //  $UserRequest->save();

/*
//var_dump($Providers[0]->id);
            $ttt = $Providers[0]->id;
            //$ProviderDevice = DB::table( DB::raw("SELECT * FROM provider_devices WHERE provider_id = '$ttt'") );
            $ProviderDevice = DB::table('provider_devices')->where('provider_id', $ttt)->first();
            Log::info("*******************Fine here before creating request filter***********************************");
            Log::info($ttt);
            $aa = $this->push_fcm($ProviderDevice->token, 'New Booking Request', 'Please open the app to accept booking');


            // update payment mode



            User::where('id', Auth::user()->id)->update(['payment_mode' => $request->payment_mode]);

            if ($request->has('card_id')) {

                Card::where('user_id', Auth::user()->id)->update(['is_default' => 0]);
                Card::where('card_id', $request->card_id)->update(['is_default' => 1]);
            }



            
            //*************Updates by nabeel 27-01-21***************

            if (Setting::get('manual_request', 0) == 0) {
                // if (Setting::get('broadcast_request', 0) == 1) {

                    Log::info("broadcast_request is equal to 1 3");
                foreach ($Providers as $key => $Provider) {

                    if (Setting::get('broadcast_request', 0) == 1) {
                        (new SendPushNotification)->IncomingRequest($Provider->id);
                    }

                    $Filter = new RequestFilter;
                    // Send push notifications to the first provider
                    // incoming request push to provider

                    $Filter->request_id = $UserRequest->id;
                    $Filter->provider_id = $Provider->id;
                    $Filter->save();
                }
            }

            if ($request->ajax()) {
                return response()->json([
                            'message' => 'New request Created!',
                            'request_id' => $UserRequest->id,
                            'current_provider' => $UserRequest->current_provider_id,
                ]);
            } else {


             //   return redirect('dashboard');
            }*/
            
        } catch (Exception $e) {
            if ($request->ajax()) {

                return response()->json(['error' => trans('api.something_went_wrong') . $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.' . $e->getMessage());
            }
        } 
        
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel_request(Request $request) {

        $this->validate($request, [
            'request_id' => 'required|numeric|exists:user_requests,id,user_id,' . Auth::user()->id,
        ]);

        try {

            $UserRequest = UserRequests::findOrFail($request->request_id);

            if ($UserRequest->status == 'CANCELLED') {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_cancelled')], 500);
                } else {
                    return back()->with('flash_error', 'Request is Already Cancelled!');
                }
            }

            if (in_array($UserRequest->status, ['SEARCHING', 'STARTED', 'ARRIVED', 'SCHEDULED'])) {

                if ($UserRequest->status != 'SEARCHING') {
                    $this->validate($request, [
                        'cancel_reason' => 'max:255',
                    ]);
                }

                $UserRequest->status = 'CANCELLED';
                $UserRequest->cancel_reason = $request->cancel_reason;
                $UserRequest->cancelled_by = 'USER';
                $UserRequest->save();

                RequestFilter::where('request_id', $UserRequest->id)->delete();

                if ($UserRequest->status != 'SCHEDULED') {

                    if ($UserRequest->provider_id != 0) {

                        ProviderService::where('provider_id', $UserRequest->provider_id)->update(['status' => 'active']);
                    }
                }

                // Send Push Notification to User
                (new SendPushNotification)->UserCancellRide($UserRequest);

                if ($request->ajax()) {
                    return response()->json(['message' => trans('api.ride.ride_cancelled')]);
                } else {
                    return redirect('dashboard')->with('flash_success', 'Request Cancelled Successfully');
                }
            } else {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_onride')], 500);
                } else {
                    return back()->with('flash_error', 'Service Already Started!');
                }
            }
        } catch (ModelNotFoundException $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            } else {
                return back()->with('flash_error', 'No Request Found!');
            }
        }
    }

    /**
     * Show the request status check.
     *
     * @return \Illuminate\Http\Response
     */
    public function request_status_check() {

        try {
            $check_status = ['CANCELLED', 'SCHEDULED'];

            $UserRequests = UserRequests::UserRequestStatusCheck(Auth::user()->id, $check_status)
                    ->get()
                    ->toArray();

            for($x=0; $x<sizeof($UserRequests);$x++)
            {
                if($UserRequests[$x]['provider_id'] != 0){
                $provider = DB::table('providers')->where('id',$UserRequests[$x]['provider_id'])->first();
                //var_dump($provider->longitude);
                 $UserRequests[$x]['provider_longitude'] = $provider->longitude;
                 $UserRequests[$x]['provider_latitude'] = $provider->latitude;

                }
                else{
                $UserRequests[$x]['provider_longitude'] = 0.0;
                 $UserRequests[$x]['provider_latitude'] = 0.0;
                 }
            }


            $search_status = ['SEARCHING', 'SCHEDULED'];
            $UserRequestsFilter = UserRequests::UserRequestAssignProvider(Auth::user()->id, $search_status)->get();

            

            //Log::info($UserRequestsFilter);

            $Timeout = Setting::get('provider_select_timeout', 180);

            if (!empty($UserRequestsFilter)) {
                for ($i = 0; $i < sizeof($UserRequestsFilter); $i++) {    
                    $ExpiredTime = $Timeout - (time() - strtotime($UserRequestsFilter[$i]->assigned_at));
                    if ($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime < 0) {
                        $Providertrip = new TripController();
                   //     $Providertrip->assign_next_provider($UserRequestsFilter[$i]->id);
                    } else if ($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime > 0) {
                        break;
                    }
                }
            }

            return response()->json(['data' => $UserRequests]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function rate_provider(Request $request) {

        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id,user_id,' . Auth::user()->id,
            'rating' => 'required|integer|in:1,2,3,4,5',
            'comment' => 'max:255',
        ]);

        $UserRequests = UserRequests::where('id', $request->request_id)
                ->where('status', 'COMPLETED')
                ->where('paid', 0)
                ->first();

        if ($UserRequests) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.user.not_paid')], 500);
            } else {
                return back()->with('flash_error', 'Service Already Started!');
            }
        }

        try {

            $UserRequest = UserRequests::findOrFail($request->request_id);

            if ($UserRequest->rating == null) {
                UserRequestRating::create([
                    'provider_id' => $UserRequest->provider_id,
                    'user_id' => $UserRequest->user_id,
                    'request_id' => $UserRequest->id,
                    'user_rating' => $request->rating,
                    'user_comment' => $request->comment,
                ]);
            } else {
                $UserRequest->rating->update([
                    'user_rating' => $request->rating,
                    'user_comment' => $request->comment,
                ]);
            }

            $UserRequest->user_rated = 1;
            $UserRequest->save();

            $average = UserRequestRating::where('provider_id', $UserRequest->provider_id)->avg('user_rating');

            Provider::where('id', $UserRequest->provider_id)->update(['rating' => $average]);

            // Send Push Notification to Provider
            if ($request->ajax()) {
                return response()->json(['message' => trans('api.ride.provider_rated')]);
            } else {
                return redirect('dashboard')->with('flash_success', 'Driver Rated Successfully!');
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong');
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function modifiy_request(Request $request) {

        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id,user_id,' . Auth::user()->id,
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required'
        ]);

        try {

            $UserRequest = UserRequests::findOrFail($request->request_id);
            $UserRequest->d_latitude = $request->latitude ?: $UserRequest->d_latitude;
            $UserRequest->d_longitude = $request->longitude ?: $UserRequest->d_longitude;
            $UserRequest->d_address = $request->address ?: $UserRequest->d_address;
            $UserRequest->save();

            // Send Push Notification to Provider
            if ($request->ajax()) {
                return response()->json(['message' => trans('api.ride.request_modify_location')]);
            } else {
                return redirect('dashboard')->with('flash_success', 'User Changed Destination Address Successfully!');
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong');
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function trips() {

        try {
            // $UserRequests = UserRequests::UserTrips(Auth::user()->id)->get();
            // if (!empty($UserRequests)) {
            //     $map_icon = asset('asset/img/marker-start.png');
            //     foreach ($UserRequests as $key => $value) {
            //         $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?" .
            //                 "autoscale=1" .
            //                 "&size=320x130" .
            //                 "&maptype=terrian" .
            //                 "&format=png" .
            //                 "&visual_refresh=true" .
            //                 "&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude .
            //                 "&markers=icon:" . $map_icon . "%7C" . $value->d_latitude . "," . $value->d_longitude .
            //                 "&path=color:0x191919|weight:3|enc:" . $value->route_key .
            //                 "&key=" . Setting::get('map_key');
            //     }
            // }
            // return $UserRequests;

             //*******Update by nabeel hassan start*********
             $Jobs=UserRequests::where('user_id', Auth::user()->id)
                    ->where('status', 'COMPLETED')
                    ->orderBy('created_at', 'desc')
                    ->with('payment')
                    ->get();
                    
                //   return $Jobs; 
                   
            //*******Update by nabeel hassan start*********

            if (!empty($Jobs)) {
                $map_icon = asset('asset/marker.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?" .
                            "autoscale=1" .
                            "&size=320x130" .
                            "&maptype=terrian" .
                            "&format=png" .
                            "&visual_refresh=true" .
                            "&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude .
                            "&markers=icon:" . $map_icon . "%7C" . $value->d_latitude . "," . $value->d_longitude .
                            "&path=color:0x000000|weight:3|enc:" . $value->route_key .
                            "&key=" . Setting::get('map_key');
                }
            }
            
          
                    $localJobs= $Jobs;
                    
             $internationalJobs=Trip::where('user_id', Auth::user()->id)
                    ->where('trip_status', 'COMPLETED')
                    ->orderBy('created_at', 'desc')
                     ->with('payment','tripRequest')
                    ->get();
                    
                   
                return response()->json(['localJobs' => $localJobs, 'internationalJobs' => $internationalJobs]) ;


        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function estimated_fare(Request $request) {

        $this->validate($request, [
            's_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
        ]);

        try {

            $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $request->s_latitude . "," . $request->s_longitude . "&destinations=" . $request->d_latitude . "," . $request->d_longitude . "&mode=driving&sensor=false&key=" . Setting::get('map_key');

            $json = curl($details);

            $details = json_decode($json, TRUE);

            $meter = $details['rows'][0]['elements'][0]['distance']['value'];
            $time = $details['rows'][0]['elements'][0]['duration']['text'];
            $seconds = $details['rows'][0]['elements'][0]['duration']['value'];

            $kilometer = round($meter / 1000);
            $minutes = round($seconds / 60);

            $tax_percentage = Setting::get('tax_percentage');
            $commission_percentage = Setting::get('commission_percentage');
            $service_type = ServiceType::findOrFail($request->service_type);

            $price = $service_type->fixed;

            if ($service_type->calculator == 'MIN') {
                $price += $service_type->minute * $minutes;
            } else if ($service_type->calculator == 'HOUR') {
                $price += $service_type->minute * 60;
            } else if ($service_type->calculator == 'DISTANCE') {
                $price += ($kilometer * $service_type->price);
            } else if ($service_type->calculator == 'DISTANCEMIN') {
                $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes);
            } else if ($service_type->calculator == 'DISTANCEHOUR') {
                $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes * 60);
            } else {
                $price += ($kilometer * $service_type->price);
            }

            $tax_price = ( $tax_percentage / 100 ) * $price;
            $total = $price + $tax_price;

            $ActiveProviders = ProviderService::AvailableServiceProvider($request->service_type)->get()->pluck('provider_id');

            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $request->s_latitude;
            $longitude = $request->s_longitude;

            $Providers = Provider::whereIn('id', $ActiveProviders)
                    ->where('status', 'approved')
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get();

            $surge = 0;

            if ($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                $surge_price = (Setting::get('surge_percentage') / 100) * $total;
                $total += $surge_price;
                $surge = 1;
            }

            /*
             * Reported by Jeya, previously it was hardcoded. we have changed as based on surge percentage.
             */
            $surge_percentage = 1 + (Setting::get('surge_percentage') / 100) . "X";

            return response()->json([
                        'estimated_fare' => round($total, 2),
                        'distance' => $kilometer,
                        'time' => $time,
                        'surge' => $surge,
                        'surge_value' => $surge_percentage,
                        'tax_price' => $tax_price,
                        'base_price' => $service_type->fixed,
                        'wallet_balance' => Auth::user()->wallet_balance
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function trip_details(Request $request) {

        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);

        try {
            $UserRequests = UserRequests::UserTripDetails(Auth::user()->id, $request->request_id)->get();
            if (!empty($UserRequests)) {
                $map_icon = asset('asset/img/marker-start.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?" .
                            "autoscale=1" .
                            "&size=320x130" .
                            "&maptype=terrian" .
                            "&format=png" .
                            "&visual_refresh=true" .
                            "&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude .
                            "&markers=icon:" . $map_icon . "%7C" . $value->d_latitude . "," . $value->d_longitude .
                            "&path=color:0x191919|weight:3|enc:" . $value->route_key .
                            "&key=" . Setting::get('map_key');
                }
            }
            return $UserRequests;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * get all promo code.
     *
     * @return \Illuminate\Http\Response
     */
    public function promocodes() {
        try {
            $this->check_expiry();

            return PromocodeUsage::Active()
                            ->where('user_id', Auth::user()->id)
                            ->with('promocode')
                            ->get();
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function check_expiry() {
        try {
            $Promocode = Promocode::all();
            foreach ($Promocode as $index => $promo) {
                if (date("Y-m-d") > $promo->expiration) {
                    $promo->status = 'EXPIRED';
                    $promo->save();
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'EXPIRED']);
                } else {
                    PromocodeUsage::where('promocode_id', $promo->id)
                            ->where('status', '<>', 'USED')
                            ->update(['status' => 'ADDED']);

                    PromocodePassbook::create([
                        'user_id' => Auth::user()->id,
                        'status' => 'ADDED',
                        'promocode_id' => $promo->id
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * add promo code.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_promocode(Request $request) {

        $this->validate($request, [
            'promocode' => 'required|exists:promocodes,promo_code',
        ]);

        try {

            $find_promo = Promocode::where('promo_code', $request->promocode)->first();
            $max_usage = $find_promo->max_usage_by_user;

            if ($find_promo->status == 'EXPIRED' || (date("Y-m-d") > $find_promo->expiration)) {

                if ($request->ajax()) {

                    return response()->json([
                                'message' => trans('api.promocode_expired'),
                                'code' => 'promocode_expired'
                    ]);
                } else {
                    return back()->with('flash_error', trans('api.promocode_expired'));
                }
            } elseif (PromocodeUsage::where('promocode_id', $find_promo->id)->where('user_id', Auth::user()->id)->whereIN('status', ['ADDED', 'USED'])->count() > 0) {

                if ($request->ajax()) {

                    return response()->json([
                                'message' => trans('api.promocode_already_in_use'),
                                'code' => 'promocode_already_in_use'
                    ]);
                } else {
                    return back()->with('flash_error', 'Promocode Already in use');
                }
            } else {

                $promo = new PromocodeUsage;
                $promo->promocode_id = $find_promo->id;
                $promo->user_id = Auth::user()->id;
                $promo->status = 'ADDED';
                $promo->max_usage = $max_usage;
                $promo->save();

                PromocodePassbook::create([
                    'user_id' => Auth::user()->id,
                    'status' => 'ADDED',
                    'promocode_id' => $find_promo->id
                ]);

                if ($request->ajax()) {

                    return response()->json([
                                'message' => trans('api.promocode_applied'),
                                'code' => 'promocode_applied'
                    ]);
                } else {
                    return back()->with('flash_success', trans('api.promocode_applied'));
                }
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            } else {
                return back()->with('flash_error', 'Something Went Wrong');
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_trips() {

        try {
            $UserRequests = UserRequests::UserUpcomingTrips(Auth::user()->id)->get();
            if (!empty($UserRequests)) {
                $map_icon = asset('asset/img/marker-start.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?" .
                            "autoscale=1" .
                            "&size=320x130" .
                            "&maptype=terrian" .
                            "&format=png" .
                            "&visual_refresh=true" .
                            "&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude .
                            "&markers=icon:" . $map_icon . "%7C" . $value->d_latitude . "," . $value->d_longitude .
                            "&path=color:0x000000|weight:3|enc:" . $value->route_key .
                            "&key=" . Setting::get('map_key');
                }
            }
            return $UserRequests;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_trip_details(Request $request) {

        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);

        try {
            $UserRequests = UserRequests::UserUpcomingTripDetails(Auth::user()->id, $request->request_id)->get();
            if (!empty($UserRequests)) {
                $map_icon = asset('asset/img/marker-start.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?" .
                            "autoscale=1" .
                            "&size=320x130" .
                            "&maptype=terrian" .
                            "&format=png" .
                            "&visual_refresh=true" .
                            "&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude .
                            "&markers=icon:" . $map_icon . "%7C" . $value->d_latitude . "," . $value->d_longitude .
                            "&path=color:0x000000|weight:3|enc:" . $value->route_key .
                            "&key=" . Setting::get('map_key');
                }
            }
            return $UserRequests;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the nearby providers.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_providers(Request $request) {

        $this->validate($request, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'service' => 'numeric|exists:service_types,id',
        ]);

        try {

            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            if ($request->has('service')) {

                $ActiveProviders = ProviderService::AvailableServiceProvider($request->service)
                                ->get()->pluck('provider_id');

                $Providers = Provider::with('service')->whereIn('id', $ActiveProviders)
                        ->where('status', 'approved')
                        ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                        ->get();
            } else {

                $ActiveProviders = ProviderService::where('status', 'active')
                                ->get()->pluck('provider_id');

                $Providers = Provider::with('service')->whereIn('id', $ActiveProviders)
                        ->where('status', 'approved')
                        ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                        ->get();
            }


            return $Providers;
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }

    /**
     * Forgot Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function forgot_password(Request $request) {

       
        // $this->validate($request, [
        //     'email' => 'required|email|exists:users,email',
        // ]);
      
    
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {

            $user = User::where('email', $request->email)->first();
           
                    $otp = mt_rand(100000, 999999);

                    $user->otp = $otp;
                    $user->save();
 
                    Log::info("fine here forgot password");
 
                    Notification::send($user, new ResetPasswordOTP($otp));
                    $to = "safeeramian@gmail.com";
                    $subject = "Forgot Password";
                    $txt = "Your OTP is : ". $otp;
                    $email = "support@myroadstar.org";
          //          var_dump($user->email);
                    $this->send_email_with_message($txt,$subject,$email,$user->email);
 
                    return response()->json([
                                'message' => 'OTP sent to your email!',
                                'user' => $user
                    ]);

        
           
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Send Email.
     *
     * Genaric Email sender. Sends email to the user from approprite account 
     */



    public function send_email_with_message($message,$subject,$email,$sendto)
    {
        //var_dump("i am here");
        $text = $message;
        $sub = $subject;
        $to = $sendto;
        $sender = $email;
        $headers = "From: " .$sender;
        mail($to,$subject,$text,$headers);
        //return;
    }

    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function reset_password(Request $request) {

            Log::info($request->all());
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'id' => 'required|numeric|exists:users,id'
        ]);
                    Log::info("data is valid");

        try {

            $User = User::findOrFail($request->id);
            // $UpdatedAt = date_create($User->updated_at);
            // $CurrentAt = date_create(date('Y-m-d H:i:s'));
            // $ExpiredAt = date_diff($UpdatedAt,$CurrentAt);
            // $ExpiredMin = $ExpiredAt->i;
            $User->password = bcrypt($request->password);
            $User->save();
            if ($request->ajax()) {
                return response()->json(['message' => 'Password Updated']);
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    /**
     * help Details.
     *
     * @return \Illuminate\Http\Response
     */
    public function help_details(Request $request) {

        try {

            if ($request->ajax()) {
                return response()->json([
                            'contact_number' => Setting::get('contact_number', ''),
                            'contact_email' => Setting::get('contact_email', '')
                ]);
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    /**
     * Show the email availability.
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users',
        ]);

        try {

            return response()->json(['message' => trans('api.email_available')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the wallet usage.
     *
     * @return \Illuminate\Http\Response
     */
    public function wallet_passbook(Request $request) {
        try {

            return WalletPassbook::where('user_id', Auth::user()->id)->get();
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the promo usage.
     *
     * @return \Illuminate\Http\Response
     */
    public function promo_passbook(Request $request) {
        try {

            return PromocodePassbook::where('user_id', Auth::user()->id)->with('promocode')->get();
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Respeonse
     */
    public function apply_referral_code(Request $request) {

        try {

            $referralAmount = ReferralCodeAmount::findOrFail(1);


            $referralToUser = User::where('id', $request->user_id)->first();
            if ($referralToUser->is_referral_used == 1) {
                return response()->json(['message' => trans('Referral Already Applied')]);
            }
            $referralToUser->wallet_balance = $referralToUser->wallet_balance + $referralAmount->referral_code_amount_to_user;
            $referralToUser->is_referral_used = 1;
            $referralToUser->save();


            $referralByUser = User::where('referral_code', $request->referral_code)->first();
            $referralByUser->wallet_balance = $referralByUser->wallet_balance + $referralAmount->referral_code_amount_by_user;
            $referralByUser->save();

            return response()->json(['message' => trans('Referral Applied Successfully')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function push_fcm($token, $title, $message) {
        

        $serverKey = 'AAAA9P8P04Q:APA91bFvb9hnuwIpscd65Q4XDxCVyodTFBgPaUmZaphM0oUQUmA6BZUhrQkW4quAACHBDMPa5p6MGgyYTw9OGPqqkhXoghxGVJN7uWD6PX-bNk_HcUWibtu4iEBiJEULBW89qRva-3Ta';


        $body = array(
            "to" => $token,
            "notification" => array(
                "title" => $title,
                "body" => $message,
                "sound" => 'alert_tone.mp3',
                "android_channel_id" => '12345678'
            ),
            "data" => array("targetScreen" => "SplashScreen"),
            "priority" => 1
        );


        $headers = array(
            'Content-Type: application/json',
            'Authorization: key=' . $serverKey
        );

        if ($ch = curl_init()) {
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            $result = curl_exec($ch);
            curl_close($ch);
            //echo $result . "\n\n";
        }

        $aa = $this->sendNotificationToIos($token, $title, $message);
        return 'not';
    }

    public function sendNotificationToIos($token, $title, $message) {

        $serverKey = 'AAAA9P8P04Q:APA91bFvb9hnuwIpscd65Q4XDxCVyodTFBgPaUmZaphM0oUQUmA6BZUhrQkW4quAACHBDMPa5p6MGgyYTw9OGPqqkhXoghxGVJN7uWD6PX-bNk_HcUWibtu4iEBiJEULBW89qRva-3Ta';

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $title,
            'body' => $message,
            'icon' => 'myIcon',
            'sound' => 'alert_tone.mp3'
        ];
        $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to' => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function redeem_reward(Request $request) {

        try {
            $RewardPoints = RewardAmount::where('id', 1)->first();

            $user_id = Auth::user()->id;
            $user = User::findOrFail($user_id);

            if ($RewardPoints->redeemable_points <= $user->points) {
                return response()->json(['error' => "User don't have enough points to Redeem."], 200);
            } else {
                $user->points = $user->points - $RewardPoints->redeemable_points;
                $user->wallet_balance = $user->wallet_balance + $RewardPoints->reward_money;
                $user->save();
                return response()->json(['error' => "Points Redeemed Successfully. Amount is added to your Wallet."], 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function reward_list(Request $request) {

        try {
            $reward = Reward::orderBy('created_at', 'desc')->get();

            return $reward;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function reward_amount(Request $request) {

        try {
            $rewardAmount = RewardAmount::orderBy('created_at', 'desc')->get();

            return $rewardAmount;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function support_message(Request $request) {

        Log::info($request->all());

        try {

            $user_id = Auth::user()->id;

            Log::info("user id is:::");
            Log::info( $user_id );


            $support_message = new SupportMessage();
            $support_message->user_id = $user_id;
            $support_message->user_type = "USER";
            $support_message->subject = $request->subject;
            $support_message->message = $request->message;
            $support_message->status = "NEW";

            $support_message->save();
            Log::info("record save into db successfully");
            return response()->json(['success' => "Message Send Successfully. We'll check and get back to you soon."], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show All Travel Trips 
     *
     */
    public function travels(Request $request) {
        $trips = DB::table('trips')
                ->join('providers', 'trips.provider_id', '=', 'providers.id')
                ->select('trips.*', 'providers.first_name', 'providers.last_name', 'providers.email', 'providers.avatar', 'providers.mobile')
                ->where(['providers.status' => 'approved', 'trip_status' => 'PENDING'])
                ->where('arrival_date', '>=', date('Y-m-d'))
                ->orderBy('trips.id', 'DESC')
                ->get();
        $_trips = array();
        foreach ($trips as $trip) {

            $bid = TripRequests::
                    where(['trip_id' => $trip->id, 'user_id' => Auth::user()->id])->get()->first();

            if ($bid && $bid->status == 'Rejected') {
                
            } else {
                $trip->bid_details = $bid ? $bid : (object) [];

                $_trips[] = $trip;
            }
        }

        if ($_trips) {
            return response()->json($_trips);
        } else {
            return response()->json(['error' => "No schedule trip(s) available at this time"], 500);
        }
    }

    /**
     * Show Travel Trip Details 
     *
     */
    public function travel_detail(Request $request) {

        if ($request->has('trip_id')) {
            $trip_id = $request->trip_id;
        }

        $trip = DB::table('trips')
                ->join('providers', 'trips.provider_id', '=', 'providers.id')
                ->select('trips.*', 'providers.first_name', 'providers.last_name', 'providers.email', 'providers.avatar', 'providers.mobile')
                ->where(['trips.status' => 1, 'providers.status' => 'approved', 'trips.id' => $trip_id])
                ->first();
        if ($trip) {
            return response()->json($trip, 200);
        } else {
            return response()->json(['error' => "Trips not available"], 500);
        }
    }

    /**
     * Show Create New Trip
     *
     */
    public function request_traveler(Request $request) {
        
                Log::info("**************request travler****************");

                Log::info($request->all());
                

        $trip_request = new TripRequests();
        $trip_request->user_id = Auth::user()->id;
        if (isset($request->trip_id)) {
            $trip_request->trip_id = $request->trip_id;
            $trip = DB::table('trips')->where('id',$request->trip_id)->first();
            if($trip->service_type != null)
            {
            $trip_request->service_type = $trip->service_type;
            }
        }
         
        $trip_request->item = $request->item;
        
//        $trip_request->send_from = $request->send_from;
//        $trip_request->send_to = $request->send_to;
        $trip_request->item_type = $request->item_type;
        $trip_request->item_size = $request->item_size;
        $trip_request->description = $request->description;
        $trip_request->amount = $request->amount;

        $trip_request->receiver_name = $request->receiver_name;
        $trip_request->receiver_phone = $request->receiver_phone;

        if ($request->picture1 != "") {
            $fileName = Str::random(30) . '.' . $request->picture1->extension();
            $request->picture1->move(public_path('uploads/'), $fileName);
            $trip_request->picture1 = url('public/uploads/' . $fileName);
        } else {
            $trip_request->picture1 = "";
        }

        if ($request->picture2 != "") {
            $fileName = Str::random(30) . '.' . $request->picture2->extension();
            $request->picture2->move(public_path('uploads/'), $fileName);
            $trip_request->picture2 = url('public/uploads/' . $fileName);
        } else {
            $trip_request->picture2 = "";
        }
        if ($request->picture3 != "") {
            $fileName = Str::random(30) . '.'  . $request->picture3->extension();
            $request->picture3->move(public_path('uploads/'), $fileName);
            $trip_request->picture3 = url('public/uploads/' . $fileName);
        } else {
            $trip_request->picture3 = "";
        }
        $trip_request->save();
        
        Log::info($trip_request);

        
        if ($trip_request) {
            if (isset($request->trip_id)) {
                    $trip1 = DB::table('trips')->where('id',$request->trip_id)->first();
                    $this->send_notification_to_provider_request($trip1->provider_id,"RaodStar", "You have a new  Bid");
            }
            return response()->json($trip_request);
        } else {
            return response()->json(['error' => "Something went wrong"], 500);
        }
    }

    /**
     * Tip payment from user.
     *
     * @return \Illuminate\Http\Response
     */
    public function trip_payment(Request $request) {

        if (Auth::user()->stripe_cust_id) {

            $tripRequest = TripRequests::find($request->bid_id);

            $trip = Trip::find($tripRequest->trip_id);

            $tax_percentage = Setting::get('tax_percentage', 10);

            $commission_percentage = Setting::get('commission_percentage', 10);

            $payment = new TripPayments();
            $payment->bid_id = $request->bid_id;
            $payment->trip_id = $tripRequest->trip_id;
            $payment->provider_id = $trip->provider_id;
            $payment->user_id = Auth::user()->id;
            $payment->fixed = $tripRequest->amount;

            $Commision = ($tripRequest->amount * $commission_percentage / 100);

            $Tax = ($tripRequest->amount * $tax_percentage / 100);

            $ProviderPay = $tripRequest->amount - $Commision;

            $Total = $tripRequest->amount + $Tax;

            $payment->commision = $Commision;
            $payment->tax = $Tax;
            $payment->provider_pay = $ProviderPay;
            $payment->total = $Total;


            $StripeCharge = $Total * 100;

            try {

                $Card = Card::where('user_id', Auth::user()->id)->where('is_default', 1)->first();

                Stripe::setApiKey(Setting::get('stripe_secret_key'));

                $Charge = Charge::create(array(
                            "amount" => $StripeCharge,
                            "currency" => "usd",
                            "customer" => Auth::user()->stripe_cust_id,
                            "card" => $Card->card_id,
                            "description" => "Payment Charge for " . Auth::user()->email,
                            "receipt_email" => Auth::user()->email
                ));

                $payment->payment_id = $Charge["id"];
                $payment->payment_mode = 'CARD';
                $payment->card_id = $Card->id;
                $payment->save();

                $tripRequest->status = 'Paid';
                $tripRequest->save();
                $Trip = Trip::find($tripRequest->trip_id)->first();

                $Trip->trip_status = 'PAID';

                $Trip->save();
                return response()->json(['message' => trans('api.paid')]);
            } catch (StripeInvalidRequestError $e) {

                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'Please add credit card details first'], 500);
        }
    }

    /**
     *  Rate Provider Trip
     */
    public function rate_trip_provider(Request $request) {


        $UserRequest = Trip::where('id', $request->trip_id)
                ->where('trip_status', 'COMPLETED')
                ->first();
        if (!$UserRequest) {
            return response()->json(['error' => "Trip not completed"], 500);
        }

        try {
            $trip_rating = UserRequestRating::where('trip_id', $request->trip_id)->first();
            if (!$trip_rating) {
                $bid = TripRequests:: where('trip_id', $request->trip_id)->first();
                $addrating = new UserRequestRating();
                $addrating['provider_id'] = $UserRequest->provider_id ? $UserRequest->provider_id : $bid->provider_id;
                $addrating['user_id'] = $bid->user_id ? $bid->user_id : $UserRequest->user_id;
                $addrating['trip_id'] = $request->trip_id;
                $addrating['user_rating'] = $request->rating;
                $addrating['user_comment'] = $request->comment;
                $addrating->save();
            } else {

                $trip_rating->user_rating = $request->rating;
                $trip_rating->user_comment = $request->comment;
                $trip_rating->save();
            }

            $average = UserRequestRating::where('provider_id', $UserRequest->provider_id)->avg('user_rating');

            Provider::where('id', $UserRequest->provider_id)->update(['rating' => $average]);
            Trip::where('id', $request->trip_id)->update(['user_rated' => 1]);

            return response()->json(['message' => 'Rated Successfully!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Request not yet completed!'], 500);
        }
    }

    /**
     * Trip Created By User
     */
    public function create_trip(Request $request) {
        $trip = new Trip();

        $trip->booking_id = Helper::generate_booking_id();
        $trip->user_id = Auth::user()->id;
        $trip->tripfrom = $request->tripfrom;
        $trip->tripto = $request->tripto;
        $trip->item = $request->item;
        $trip->item_type = $request->item_type;
        $trip->item_size = $request->item_size;
        $trip->other_information = $request->other_information;
        $trip->trip_amount = $request->trip_amount;
        $trip->trip_status = "PENDING";
        $trip->created_by = "user";
        $trip->tripfrom_lat = $request->tripto_lat;
                    $trip->tripfrom_lng = $request->tripto_lng;
                    $trip->tripto_lat = $request->tripfrom_lat;
                    $trip->tripto_lng = $request->tripfrom_lng;
        // New Fields
        $trip->arrival_date = date('Y-m-d', strtotime($request->arrival_date));
        $trip->receiver_name = $request->receiver_name;
        $trip->receiver_phone = $request->receiver_phone;

        if ($request->picture1 != "") {
            
            Log::info("picture1 found");

            $fileName = Str::random(30) . '.' . $request->picture1->extension();

            $request->picture1->move(public_path('uploads/'), $fileName);

            $trip->picture1 = url('public/uploads/' . $fileName);
        } else {
            $trip->picture1 = "";
        }
        if ($request->picture2 != "") {
                        Log::info("picture2 found");

            $fileName = Str::random(30) . '.' . $request->picture2->extension();
            $request->picture2->move(public_path('uploads/'), $fileName);

            $trip->picture2 = url('public/uploads/' . $fileName);
        } else {
            $trip->picture2 = "";
        }
        if ($request->picture3 != "") {
                        Log::info("picture1 found");

            $fileName = Str::random(30) . '.' . $request->picture3->extension();
            $request->picture3->move(public_path('uploads/'), $fileName);

            $trip->picture3 = url('public/uploads/' . $fileName);
        } else {
            $trip->picture3 = "";
        }

        $trip->save();
        //var_dump($trip->save());
        if ($trip) {
            return response()->json($trip);
        } else {
            return response()->json(['error' => "Something went wrong"], 500);
        }
    }


    

    /*
     * User Trips
     */

    public function user_trips(Request $request) {
        $user_trips = Trip::with('sea_trip_estimated_arrival')->with('air_trip_flight_info')->where('user_id', Auth::user()->id)
                        ->orderBy('id', 'DESC')->get();
        
        foreach($user_trips as $user_trip)
        {
            if($user_trip->provider_id!=0)
            {
                $provider=Provider::find($user_trip->provider_id);

                $user_trip["avatar"]=$provider->avatar;
                $user_trip["email"]=$provider->email;
                $user_trip["first_name"]=$provider->first_name;
                $user_trip["last_name"]=$provider->last_name;


            }

            else
            {
                $user_trip["avatar"]=null;
            }
        }
        if (!$user_trips->isEmpty()) {
            return response()->json($user_trips);
        } else {
            return response()->json(['error' => "No trips available to show."], 500);
        }
    }



    public function user_trips_id(Request $request) {
        $user_trips = Trip::with('sea_trip_estimated_arrival')->with('air_trip_flight_info')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->where('id',$request->trip_id)->get();

        //var_dump($user_trips);
        //die('here');
         foreach($user_trips as $user_trip)
        {
        
            if($user_trip->provider_id!=0)
            {
                $provider=Provider::find($user_trip->provider_id);

                $user_trip["avatar"]=$provider->avatar;
                $user_trip["email"]=$provider->email;
                $user_trip["first_name"]=$provider->first_name;
                $user_trip["last_name"]=$provider->last_name;


            }

            else
            {
                $user_trip["avatar"]=null;
            }
        }
        if (!$user_trips->isEmpty()) {
            return response()->json($user_trips);
        } else {
            return response()->json(['error' => "No trips available to show."], 500);
        }
    }
    /*
     * User Trip Bids
     */

    public function trip_bids(Request $request) {

        if ($request->has('trip_id')) {
            $trip_id = $request->trip_id;
        }

        $bids = DB::table('trip_requests')
                        ->join('providers', 'trip_requests.provider_id', '=', 'providers.id')
                        ->select('trip_requests.*', 'providers.first_name', 'providers.last_name', 'providers.avatar')
                        ->where(['providers.status' => 'approved', 'trip_requests.trip_id' => $trip_id])
                        ->where('trip_requests.status', '!=', 'Rejected')
                        ->orderBy('id', 'DESC')->get();
        if (!$bids->isEmpty()) {
            return response()->json($bids);
        } else {
            return response()->json(['error' => "No bids available to show for this trip."], 500);
        }
    }

    public function accept_bid(Request $request) {

        

        try {

            TripRequests:: where('trip_id', $request->trip_id)
                    ->where('id', '!=', $request->bid_id)
                    ->update(['status' => 'Rejected']);

               /*************Updates By nabeel hassan start*********************/  


               $trip=Trip::find($request->trip_id);
//die(TripRequests:: where('id',$request->bid_id));
               //$tripreq =   TripRequests::where('id',$request->bid_id)->fetch();
             //  $tripreq = TripRequests::find($request->bid_id);
//$tripreq = DB::table('trip_requests')->where('id','=',$request->bid_id)->fetch();
            // TripRequests::
            //         where('id', $request->bid_id)
            //         ->update(['status' => 'Approved']);


             TripRequests::
                    where('id', $request->bid_id)
                    ->update(['status' => 'Approved',
                            'user_id' => $trip->user_id,
                            // 'amount' => $trip->trip_amount,
                            'item' => $trip->item,
                            'item_type' => $trip->item_type,
                            'receiver_name' => $trip->receiver_name,
                            'receiver_phone' => $trip->receiver_phone,
                            'picture1' => $trip->picture1,
                            'picture2' => $trip->picture2,
                            'picture3' => $trip->picture3]);

            

           $bid_details = TripRequests::
                    where('id', $request->bid_id)->first();

            // Log::info("**************bid_details*****************");

            Log::info($bid_details);


           $trip= Trip::where('id', $request->trip_id)
                    ->update([
                        'provider_id' => $bid_details->provider_id,
                        'trip_status' => 'SCHEDULED',
                        'service_type' => $bid_details->service_type,
                        'trip_amount' => $bid_details->amount
            ]);

            $tripreq =   TripRequests:: where('id',$request->bid_id)->first();
             $trp = Trip:: where('id',$tripreq->trip_id)->first();
            $pid=0;
            if(is_null($bid_details->provider_id))
            {
                $pid=     $trp->provider_id;
               
            }
            else
            {
               $pid=      $bid_details->provider_id;
            }
            //$this->send_notification_to_provider_request($pid,"RaodStar", "Your Bid has a counter offer");

             $this->send_notification_to_provider_request($pid,"RaodStar", "Your Bid has been accepted");
            

            // Log::info("**************Trip Details*****************");
            Log::info($trip);

            return response()->json(['message' => "Bid has been accepted successfully."], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e],500);//trans('api.something_went_wrong')], 500);
        }
    }

    public function bid_counter_offer(Request $request) {

        try {
            TripRequests:: where('id', $request->bid_id)
                    ->update(['counter_amount' => $request->counter_amount, 'is_counter' => 1]);
           //$tripreq =   TripRequests:: find($request->bid_id);

            $tripreq =   TripRequests:: where('id',$request->bid_id)->first();
            
             $trp = Trip:: where('id',$tripreq->trip_id)->first();
           // die($trp);
            $pid=0;
            if(is_null($tripreq->provider_id))
            {
                $pid=     $trp->provider_id;
               
            }
            else
            {
               $pid=      $tripreq->provider_id;
            }
            // var_dump($pid);
            // die('here');
            $this->send_notification_to_provider_request($pid,"RaodStar", "Your Bid has a counter offer");
            return response()->json(['message' => "Counter Offer has been posted successfully."], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function accept_bid_counter(Request $request) {

        try {
            TripRequests:: where('trip_id', $request->trip_id)
                    ->where('id', '!=', $request->bid_id)
                    ->update(['status' => 'Rejected']);

            TripRequests::
                    where('id', $request->bid_id)
                    ->update(['status' => 'Approved']);
            $bid_details = TripRequests::
                    where('id',$request->bid_id)->first();

            Trip::where('id', $request->trip_id)
                    ->update([
                        'user_id' => $bid_details->user_id,
                        'trip_status' => 'SCHEDULED',
                        'service_type' => $bid_details->service_type,
                        'trip_amount' => $bid_details->counter_amount,
                        'item' => $bid_details->item,
                        'item_type' => $bid_details->item_type,
                        'service_type' => $bid_details->service_type,
                        'receiver_name' => $bid_details->receiver_name,

            ]);
            //die('here');
             $tripreq =   TripRequests:: where('id',$request->bid_id)->first();
             $trp = Trip:: where('id',$request->trip_id)->first();
            $pid=0;
            if(is_null($bid_details->provider_id))
            {
                $pid=     $trp->provider_id;
               
            }
            else
            {
               $pid=      $bid_details->provider_id;
            }
            // var_dump($pid);
            // die('here');
            $this->send_notification_to_provider_request($pid,"RaodStar", "Your counter offer has been accepted");
            return response()->json(['message' => "Bid has been accepted successfully."], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function sendsinglenotification($title,$body,$data,$token)
    {
        $serverKey= "AAAANqPZbgE:APA91bG2mqZVYse-mtvgGgU_nja4D_q4vNa1vlFb5zFJFoscQPr8EnC34XSbDfWG2Qlr6gT0VPfIJjrSaoCeZ_RL2tKM-g9-hsVmsf6OgYySqUJzA4gXl1VFIORZ0AJ7-Kme4FLw3vVy";

$fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $title,
            'body' => $message
        ];
        

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to' => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
    
    }
    public function sendmultinotification($title,$body,$data,$tokens)
    {
        $serverKey= "AAAANqPZbgE:APA91bG2mqZVYse-mtvgGgU_nja4D_q4vNa1vlFb5zFJFoscQPr8EnC34XSbDfWG2Qlr6gT0VPfIJjrSaoCeZ_RL2tKM-g9-hsVmsf6OgYySqUJzA4gXl1VFIORZ0AJ7-Kme4FLw3vVy";

$fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $title,
            'body' => $message
        ];
        

        $fcmNotification = [
            'registration_ids' => $tokens, //multple token array
            //'to' => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
    
    }

    public function send_notification_to_user(Request $request)
    {
        $user_id = $request->uid;
        var_dump($user_id);
        //die('here');

        $user = User::where('id',$user_id)->get();
        var_dump($user[0]->device_token);
        //die('user');
        $serverKey = "AAAANqPZbgE:APA91bG2mqZVYse-mtvgGgU_nja4D_q4vNa1vlFb5zFJFoscQPr8EnC34XSbDfWG2Qlr6gT0VPfIJjrSaoCeZ_RL2tKM-g9-hsVmsf6OgYySqUJzA4gXl1VFIORZ0AJ7-Kme4FLw3vVy";
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => '$title',
            'body' => '$message'
        ];
        

        $fcmNotification = [
            //'registration_ids' => $tokens, //multple token array
            'to' => $user[0]->device_token, //single token
            'notification' => $notification
            //'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($result);


    }

    




    public function send_notification_to_provider(Request $request)
    {
        $user_id = $request->uid;
        var_dump($user_id);
        //die('here');

        $user = Provider::where('id',$user_id)->get();
        var_dump($user[0]->device_token);
        //die('user');
        $serverKey = "AAAANqPZbgE:APA91bG2mqZVYse-mtvgGgU_nja4D_q4vNa1vlFb5zFJFoscQPr8EnC34XSbDfWG2Qlr6gT0VPfIJjrSaoCeZ_RL2tKM-g9-hsVmsf6OgYySqUJzA4gXl1VFIORZ0AJ7-Kme4FLw3vVy";
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => '$title',
            'body' => '$message'
        ];
        

        $fcmNotification = [
            //'registration_ids' => $tokens, //multple token array
            'to' => $user[0]->device_token, //single token
            'notification' => $notification
            //'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($result);


    }

    public function send_notification_to_provider_request($user_id,$title,$message)
    {
//        $user_id = $request->uid;
  //      var_dump($user_id);
        //die('here');
//DB::table('provider_devices')->where('provider_id', $ttt)->first();

        $user = DB::table('provider_devices')->where('provider_id', $user_id)->first();
// var_dump($user_id);
// var_dump($user);
       // $user = ProviderDevice::where('provider_id',$user_id)->get();
    //    var_dump('notification');
    //     var_dump($user->provider_id);
    //     var_dump($user->token);
        //die('user');
        $serverKey = "AAAANqPZbgE:APA91bG2mqZVYse-mtvgGgU_nja4D_q4vNa1vlFb5zFJFoscQPr8EnC34XSbDfWG2Qlr6gT0VPfIJjrSaoCeZ_RL2tKM-g9-hsVmsf6OgYySqUJzA4gXl1VFIORZ0AJ7-Kme4FLw3vVy";
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $title,
            'body' => $message
        ];
        

        $fcmNotification = [
            //'registration_ids' => $tokens, //multple token array
            'to' => $user->token, //single token
            'notification' => $notification
            //'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        // var_dump($result);


    }

    public function send_message_notification_to_provider(Request $request)
    {

        $user = DB::table('provider_devices')->where('provider_id', $request->user_id)->first();
        $serverKey = "AAAANqPZbgE:APA91bG2mqZVYse-mtvgGgU_nja4D_q4vNa1vlFb5zFJFoscQPr8EnC34XSbDfWG2Qlr6gT0VPfIJjrSaoCeZ_RL2tKM-g9-hsVmsf6OgYySqUJzA4gXl1VFIORZ0AJ7-Kme4FLw3vVy";
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => "RoadStar",
            'body' => "You have a new message"
        ];
        

        $fcmNotification = [
            //'registration_ids' => $tokens, //multple token array
            'to' => $user->token, //single token
            'notification' => $notification
            //'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        // var_dump($result);

        return response()->json(['message' => "Notification Sent"], 200);


    }





    public function reject_bid_counter(Request $request) {

        try {

            TripRequests:: where('trip_id', $request->trip_id)
                    ->where('id', '=', $request->bid_id)
                    ->update(['status' => 'Rejected']);
            $tripreq =   TripRequests:: where('id',$request->bid_id)->fetch();
            $this->send_notification_to_provider_request($tripreq->provider_id,"RaodStar", "Your counter offer has been rejected");
            return response()->json(['message' => "Your Bid has been cancelled."], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }



    public function track_trip_new(Request $request)
{
	$trip = Trip::where('id',$request->trip_id)->first();
    //var_dump($trip);
//    $last = DB::table('last_location')->('trip_id',$request->trip_id)->first();
//     DB::table('users')->insert([
//     'email' => 'kayla@example.com',
//     'votes' => 0
// ]);
	$service_type = $trip->service_type;
	$package_longitude = 0.0;
	$package_latitude = 0.0;
	if($trip->service_type == 'By Road')
	{
		$provider = Provider::find($trip->provider_id);
		$package_latitude = $provider->latitude;
		$package_longitude = $provider->longitude;
		$response= array('service_type' =>  'By Road','current_longitude' => $package_longitude,'current_latitude'=> $package_latitude,'timeout' => "ok",
                                             'arrivalTime' => 0,
                                             'departureTime' => 0);
		return response()->json($response);
	}
	else if($trip->service_type == 'By Air')
	{
        //var_dump($trip->flight_no);
		$client = new \GuzzleHttp\Client();
		$res = $client->request('GET', 'http://IsaacImasuagbon:'.Setting::get('flightaware_api_key', '').'@flightxml.flightaware.com/json/FlightXML2/InFlightInfo?ident='.$trip->flight_no);
        
		if($res->getStatusCode()=='200')
                        { 
                            $body= $res->getBody();

                            $InFlightInfoResult = json_decode($body, true);
                            $inFlightInfoResult= $InFlightInfoResult["InFlightInfoResult"];
                            //var_dump($inFlightInfoResult);
                            // if($last ==null)
                            // {
                            //     $last = 
                            // }
                            $response= array('service_type' => $trip->service_type,
                                             'current_longitude' => $InFlightInfoResult["InFlightInfoResult"]["longitude"],
                                             'current_latitude' => $InFlightInfoResult["InFlightInfoResult"]["latitude"],
                                             'timeout' => $InFlightInfoResult["InFlightInfoResult"]["timeout"],
                                             'arrivalTime' => $InFlightInfoResult["InFlightInfoResult"]["arrivalTime"],
                                             'departureTime' => $InFlightInfoResult["InFlightInfoResult"]["departureTime"]);
                            return response()->json($response);

                        }
	}
	else if($trip->service_type == 'By Sea')
	{
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET','https://services.marinetraffic.com/api/exportvessel/3b5750a049049272ac57167877c5585a38f70c37?v=5&shipid='.$trip->vessel_id.'&timespan=2&msgtype=simple&protocol=json');
        if($res->getStatusCode()=='200')
                        { 
                            $body= $res->getBody();
                            $InFlightInfoResult = json_decode($body, true);
                            if($InFlightInfoResult == [])
                            {
                                $response = array('service_type' => $trip->service_type,
                                'current_longitude' => 0.0,
                                'current_latitude' => 0.0,
                                'timeout' => "ok",
                          'arrivalTime' => 0,
                          'departureTime' => 0);
                            return response()->json($response);                        
                            }
                            else{
                                // var_dump($InFlightInfoResult);
                                // die();
                                $response = array('service_type' => $trip->service_type,
                                'current_longitude' => $InFlightInfoResult[0][2],
                                'current_latitude' => $InFlightInfoResult[0][1],
                                'timeout' => "ok",
                          'arrivalTime' => 0,
                          'departureTime' => 0);
                            return response()->json($response);
                            }
                        }
		$response = array('service_type' => $trip->service_type,
                          'current_longitude' => 0.0,
                          'current_latitude' => 0.0,
                          'timeout' => "ok",
                          'arrivalTime' => 0,
                          'departureTime' => 0);
                            return response()->json($response);

	}
	else
		{return response()->json(['error' => trans('api.something_went_wrong')], 500);}
}


public function re_send_request1(Request $request) {
//var_dump($request);
     /*   $this->validate($request, [
            's_latitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
            'promo_code' => 'exists:promocodes,promo_code',
            'distance' => 'required|numeric',
            'use_wallet' => 'numeric',
            'payment_mode' => 'required|in:CASH,CARD,PAYPAL',
            'card_id' => ['required_if:payment_mode,CARD', 'exists:cards,card_id,user_id,' . Auth::user()->id],
        ]);
*/
    //    var_dump(Auth::user()->id);
       // var_dump($request->all());
        //Log::info('New Request from User: ' . Auth::user()->id);
        //Log::info('Request Details:', $request->all());

        $uid = Auth::user()->id;
         $ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();
//  var_dump("Active Requests");
//  var_dump($ActiveRequests);
//  var_dump(Auth::user()->id);
        //dd($ActiveRequests);
    
         if ($ActiveRequests > 0) {   
    //         if ($request->ajax()) {
        if($request->has('from_web'))
        {
         return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later');   
        }
        else{
                 return response()->json(['error' => trans('api.ride.request_inprogress')]);
        }
    //         } else {
    //              return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later');
    //         }
        }

        //DB::table('user_requests')->where('user_id',$uid)->where()

    

        if ($request->has('schedule_date') && $request->has('schedule_time')) {
            $beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subHour(1);
            $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addHour(1);

            $CheckScheduling = UserRequests::where('status', 'SCHEDULED')
                    ->where('user_id', $uid)  //web
                    ->whereBetween('schedule_at', [$beforeschedule_time, $afterschedule_time])
                    ->count();
// var_dump("CheckScheduling");
// var_dump($CheckScheduling);
        

            if ($CheckScheduling > 0) {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                } else {
                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                }
            }
        }
        //var_dump($request->has('schedule_date') && $request->has('schedule_time'));
        

        $distance = Setting::get('provider_search_radius', '10');
        $latitude = $request->s_latitude;
        $longitude = $request->s_longitude;
        $service_type = 14;
        //  var_dump("distance");
        // var_dump($distance);
        // var_dump($service_type);
// var_dump((6371 * acos( cos( radians($latitude) ) * cos( radians($latitude) ) * cos( radians($longitude) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians($latitude) ) ) ));
        $Providers = Provider::with('service')
                ->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"), 'id')
                ->where('status', 'approved')
                 ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->whereHas('service', function($query) use ($service_type) {
               //     var_dump("iamhere");
            //        $query->where('status', 'active');
                    //$query->where('service_type_id', $service_type);
                })
                ->orderBy('distance', 'asc')
               // ->take(10)
                ->get();
                // var_dump("providers");
                // var_dump(sizeof($Providers));
                for($u=0;$u<sizeof($Providers);$u++)
                {
       //         var_dump($Providers[$u]->id);
                }
                
     //           var_dump($Providers[0]->id);
       //         var_dump(count($Providers));
        // List Providers who are currently busy and add them to the filter list.


       Log::info("available providers list is");
       Log::info($Providers);
//var_dump($Providers);
        if (count($Providers) == 0) {
           // if ($request->ajax()) {
                // Push Notification to User
            //     var_dump("no providers");
            if($request->has('from_web'))
               {
                //   $input = request()->all();

                  //return back()->with('flash_success_recreate', 'No Providers Found! Please try again.');
                  return back()->with('flash_success_recreate', 'No Providers Found! Please try again.'); 
               }

         else{   
               return response()->json(['message' => trans('api.ride.no_providers_found')]);
        }
            // } else {
            //     // var_dump("no providers");
            //     return back()->with('flash_success', 'No Providers Found! Please try again.');
            // }
        }
        
        try {
            $route_key = "asdasd";
            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $request->s_latitude . "," . $request->s_longitude . "&destination=" . $request->d_latitude . "," . $request->d_longitude . "&mode=driving&key=" . Setting::get('map_key');

           $json = curl($details);
                
           $details = json_decode($json, TRUE);

           $route_key = $details['routes'][0]['overview_polyline']['points'];
        //     var_dump($route_key);
        //    die('here');
            $UserRequest = new UserRequests;

            $UserRequest->category = $request->category;
            $UserRequest->product_type = $request->product_type;
            $UserRequest->product_weight = $request->product_weight;

             

            if ($request->attachment1 != "") {
                $fileName = time() . '1.' . $request->attachment1->extension();
                $request->attachment1->move(public_path('uploads/user/request/attachments'), $fileName);

                $UserRequest->attachment1 = url('public/uploads/user/request/attachments/' . $fileName);
            }
            if ($request->attachment2 != "") {
                $fileName = time() . '2.' . $request->attachment2->extension();
                $request->attachment2->move(public_path('uploads/user/request/attachments'), $fileName);

                $UserRequest->attachment2 = url('public/uploads/user/request/attachments/' . $fileName);
            }
            if ($request->attachment3 != "") {
                $fileName = time() . '3.' . $request->attachment3->extension();
                $request->attachment3->move(public_path('uploads/user/request/attachments'), $fileName);

                $UserRequest->attachment3 = url('public/uploads/user/request/attachments/' . $fileName);
            }

            


            $UserRequest->instruction = $request->instruction;
            $UserRequest->receiver_name = $request->receiver_name;
            $UserRequest->receiver_phone = $request->receiver_phone;

            $UserRequest->booking_id = Helper::generate_booking_id();
            $UserRequest->user_id = $uid; //Auth::user()->id;  //here web

            //*************Updates by nabeel 27-01-21***************


            if ((Setting::get('manual_request', 0) == 0) && (Setting::get('broadcast_request', 0) == 0)) {
                // if (Setting::get('broadcast_request', 0) == 1) {
                Log::info("broadcast_request is equal to 1 ");

                $UserRequest->current_provider_id = $Providers[0]->id;
                $test['current_provider_id'] = $Providers[0]->id;
            } else {
                $UserRequest->current_provider_id = 0;
                $test['current_provider_id'] = 0;
                $test['provider_id'] = 0;
            }

            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->payment_mode = $request->payment_mode;
            $test['service_type_id'] = $request->service_type;
            $test['payment_mode'] = $request->payment_mode;
            $UserRequest->status = 'SEARCHING';
            $test['status'] = 'SEARCHING';
            $UserRequest->s_address = $request->s_address ?: "";
            $UserRequest->d_address = $request->d_address ?: "";

            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;

            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->distance = $request->distance;


            $UserRequest->product_distribution = $request->product_distribution;
            $UserRequest->product_width = $request->product_width;
            $UserRequest->product_height = $request->product_height;
            $UserRequest->weight_unit = $request->weight_unit;

                        $test['s_address'] = $request->s_address ?: "";
            $test['d_address'] = $request->d_address ?: "";

            $test['s_latitude'] = $request->s_latitude;
            $test['s_longitude'] = $request->s_longitude;

            $test['d_latitude'] = $request->d_latitude;
            $test['d_longitude'] = $request->d_longitude;
            $test['distance'] = $request->distance;


            $test['product_distribution'] = $request->product_distribution;
            $test['product_width'] = $request->product_width;
            $test['product_height'] = $request->product_height;
            $test['weight_unit'] = $request->weight_unit;


            // if (Auth::user()->wallet_balance > 0) {
            //     $UserRequest->use_wallet = $request->use_wallet ?: 0;
            //     $test['use_wallet'] = $request->use_wallet ?: 0;
            // }
            //remove this 
            $test['use_wallet'] = 0;

            if (Setting::get('track_distance', 0) == 1) {
                $UserRequest->is_track = "YES";
                 $test['is_track'] = "YES";
            }

            $UserRequest->assigned_at = Carbon::now();
            $test['assigned_at'] = Carbon::now();
           // $UserRequest->route_key = $route_key;

            if ($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                $UserRequest->surge = 1;
                $test['surge'] = 1;
            }

            if ($request->has('schedule_date') && $request->has('schedule_time')) {
                $UserRequest->schedule_at = date("Y-m-d H:i:s", strtotime("$request->schedule_date $request->schedule_time"));
                $test['schedule_at'] = $UserRequest->schedule_at;
            }

            //*************Updates by nabeel 27-01-21***************

            if ((Setting::get('manual_request', 0) == 0) && (Setting::get('broadcast_request', 0) == 0)) {
                // if (Setting::get('broadcast_request', 0) == 1) {
                    Log::info("broadcast_request is equal to 1 2");

                Log::info('New Request id : ' . $UserRequest->id . ' Assigned to provider : ' . $UserRequest->current_provider_id);
                
                (new SendPushNotification)->IncomingRequest($Providers[0]->id);
            }

            //$UserRequest->save();
            //UserRequests::($UserRequest);

            
            $test['category'] = $request->category;
            $test['product_type'] = $request->product_type;
            $test['product_weight'] = $request->product_weight;
            $test['instruction'] = $request->instruction;
            $test['receiver_name'] = $request->receiver_name;
            $test['receiver_phone'] = $request->receiver_phone;

            $test['booking_id'] = Helper::generate_booking_id();
            
            $test['user_id'] = $uid;//. here web.   Auth::user()->id;

            $test['service_type_id'] = $request->service_type;
            $test['payment_mode'] = $request->payment_mode;

            $test['status'] = 'SEARCHING';

            $test['s_address'] = $request->s_address ?: "";
            $test['d_address'] = $request->d_address ?: "";

            $test['s_latitude'] = $request->s_latitude;
            $test['s_longitude'] = $request->s_longitude;

            $test['d_latitude'] = $request->d_latitude;
            $test['d_longitude'] = $request->d_longitude;
            $test['distance'] = $request->distance;


          //  $test['product_distribution'] = $request->product_distribution;
            $test['product_width'] = $request->product_width;
            $test['product_height'] = $request->product_height;
            $test['weight_unit'] = $request->weight_unit;
            //die('here');
            //var_dump($test);
            //var_dump(UserRequests::create($test));
           // $check= new UserRequests($test);
           // $check->insert($test);
       //     $UserRequest->save();
            //return back()->with('success_msg', 'Trip Details Saved Successfully');
//var_dump("User Request");            
//$check =
//$check =  DB::table('user_requests')->where('user_id',615)->first();
//$check =  DB::table('provider_devices')->where('provider_id', 371)->first();

//$c=DB::table('user_requests')->insert($test);
//var_dump($c);
//var_dump($test);
//die('here');
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
//die($test['provider_id']);
$a1 = "";
$a2 = "";
$a3 = "";
if($request->attachment1 != "")
{
    $a1 = $UserRequest->attachment1;
}
else
{
    $a1=Null;
}
if($request->attachment2 != "")
{
    $a2 = $UserRequest->attachment2;
}
else{
    $a2=Null;
}
if($request->attachment3 != "")
{
    $a3 = $UserRequest->attachment3;
}
else
{
    $a3=Null;
}

$check = DB::table('user_requests')->insertGetId(['provider_id' => 0,
'booking_id' => $test['booking_id'],
        'user_id' => $uid,
        'current_provider_id'=> 0,
        'service_type_id'=> 14,
        'status'=> "SEARCHING",
        'payment_mode'=> $request->payment_mode,
        'is_track'=> "YES",
        'distance'=> $request->distance,
        's_latitude'=> $request->s_latitude, 
        'd_latitude'=> $request->d_latitude,
        's_longitude'=> $request->s_longitude,
        'd_longitude'=> $request->d_longitude,
        's_address'=>$request->s_address,
        'd_address'=>$request->d_address,
        'use_wallet'=> $test['use_wallet'],
        'attachment1'=>$a1,
        'attachment2'=>$a2,
        'attachment3'=>$a3,
        'surge'=> 1,
        'category' => $request->category,
         'product_type'=> $request->product_type, 
         'product_width'=> $request->product_width,
          'product_height'=> $request->product_height,
          'weight_unit'=> $request->weight_unit,
          'product_distribution'=> $request->product_distribution,
           'product_weight'=> $request->product_weight,
            'instruction'=> $request->instruction,
             'receiver_name'=> $request->receiver_name,
              'receiver_phone'=> $request->receiver_phone,
              'route_key'=>$route_key]);
       //       die('here');

//die('User sdsdsd');
//var_dump("PROVIDER");
//var_dump($Providers[0]->id);
//die('here');
for($i=0;$i<count($Providers);$i++){
            $ttt = $Providers[$i]->id;
            //$ProviderDevice = DB::table( DB::raw("SELECT * FROM provider_devices WHERE provider_id = '$ttt'") );
            //$ProviderDevice = DB::table('provider_devices')->where('provider_id', $ttt)->first();
            
           // Log::info("*******************Fine here before creating request filter***********************************");
           // Log::info($ttt);
//            $aa = $this->push_fcm($ProviderDevice->token, 'New Booking Request', 'Please open the app to accept booking');
            $aa = $this->send_notification_to_provider_request($ttt,'New Booking Request', 'Please open the app to accept booking');
           // die('here');
}



            // update payment mode



            User::where('id', $uid)->update(['payment_mode' => $request->payment_mode]);

            if ($request->has('card_id')) {


        //  $tc =      Card::where('user_id', Auth::user()->id)->update(['is_default' => 0]);
          $tcc =     Card::where('card_id', $request->card_id)->update(['is_default' => 1]);
            }



            
            //*************Updates by nabeel 27-01-21***************

            if (Setting::get('manual_request', 0) == 0) {
                // if (Setting::get('broadcast_request', 0) == 1) {
//var_dump('manual');
                    Log::info("broadcast_request is equal to 1 3");
                foreach ($Providers as $key => $Provider) {

                    // if (Setting::get('broadcast_request', 0) == 1) {
                    //     (new SendPushNotification)->IncomingRequest($Provider->id);
                    // }

                    $Filter = new RequestFilter;
                    // Send push notifications to the first provider
                    // incoming request push to provider

                    $Filter->request_id = $check;
                    $Filter->provider_id = $Provider->id;
//   var_dump($check);
//   var_dump($Provider->id);                  
                    $Filter->save();
                    // var_dump("providerid");
                    // var_dump($check);
                    // var_dump($Provider->id);
                    // var_dump($Filter->id);
                    
             //       die('filter');
                }
            }
            // die()
//die($request->ajax());
        //  if ($request->wantsJson()) {

            
            if($request->has('from_web'))
            {
             return redirect('dashboard');   
            }
            else{
                return response()->json([
                            'message' => 'New request Created!',
                            'request_id' => $check,
                            'current_provider' => 0
                ]);}
        //   } else {


        //       return redirect('dashboard');
        //   }
            
        } catch (Exception $e) {
            if ($request->ajax()) {

                return response()->json(['error' => trans('api.something_went_wrong') . $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.' . $e->getMessage());
            }
        // } 
       // var_dump("EXCEPTION");
        //var_dump($e);
        }
        
    }


    public function recreate_send_request1(Request $request) {
       $uid = Auth::user()->id;
         $ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();
        $userrequest= UserRequests::where('id',$request->id)->first();
         if ($ActiveRequests > 0) {   
                 return response()->json(['error' => trans('api.ride.request_inprogress')]);
        }
        if ($request->has('schedule_date') && $request->has('schedule_time')) {
            $beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subHour(1);
            $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addHour(1);
            $CheckScheduling = UserRequests::where('status', 'SCHEDULED')
                    ->where('user_id', $uid)  //web
                    ->whereBetween('schedule_at', [$beforeschedule_time, $afterschedule_time])
                    ->count();
            if ($CheckScheduling > 0) {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                } else {
                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                }
            }
        }
        $distance = Setting::get('provider_search_radius', '10');
        $latitude = $userrequest->s_latitude;
        $longitude = $userrequest->s_longitude;
        $service_type = 14;
        $Providers = Provider::with('service')
                ->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"), 'id')
                ->where('status', 'approved')
                 ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->whereHas('service', function($query) use ($service_type) {
       //             $query->where('status', 'active');
                })
                ->orderBy('distance', 'asc')
                //->take(10)
                ->get();
       Log::info("available providers list is");
       Log::info($Providers);
        if (count($Providers) == 0) {
               return response()->json(['message' => trans('api.ride.no_providers_found')]);
        }
        try {

            $booking_id = Helper::generate_booking_id();
$check = DB::table('user_requests')->insertGetId(['provider_id' => 0,
'booking_id' => $booking_id,
        'user_id' => $uid,
        'current_provider_id'=> 0,
        'service_type_id'=> 14,
        'status'=> "SEARCHING",
        'payment_mode'=> $userrequest->payment_mode,
        'is_track'=> "YES",
        'distance'=> $userrequest->distance,
        's_latitude'=> $userrequest->s_latitude, 
        'd_latitude'=> $userrequest->d_latitude,
        's_longitude'=> $userrequest->s_longitude,
        'd_longitude'=> $userrequest->d_longitude,
        'use_wallet'=> $userrequest->use_wallet,
        'attachment1'=>$userrequest->attachment1,
        'attachment2'=>$userrequest->attachment2,
        'attachment3'=>$userrequest->attachment3,
        's_address'=>$userrequest->s_address,
        'd_address'=>$userrequest->d_address,
        'surge'=> 1,
        'category' => $userrequest->category,
         'product_type'=> $userrequest->product_type, 
         'product_width'=> $userrequest->product_width,
          'product_height'=> $userrequest->product_height,
          'weight_unit'=> $userrequest->weight_unit,
          'product_distribution'=> $userrequest->product_distribution,
           'product_weight'=> $userrequest->product_weight,
            'instruction'=> $userrequest->instruction,
             'receiver_name'=> $userrequest->receiver_name,
              'receiver_phone'=> $userrequest->receiver_phone,
              'route_key'=>$userrequest->route_key]);
for($i=0;$i<count($Providers);$i++){
            $ttt = $Providers[$i]->id;
            $aa = $this->send_notification_to_provider_request($ttt,'New Booking Request', 'Please open the app to accept booking');
}
           
            //*************Updates by nabeel 27-01-21***************
            if (Setting::get('manual_request', 0) == 0) {

                    Log::info("broadcast_request is equal to 1 3");
                foreach ($Providers as $key => $Provider) {
                    $Filter = new RequestFilter;
                    $Filter->request_id = $check;
                    $Filter->provider_id = $Provider->id;
                    $Filter->save();
                }
            }
            if($request->has('web'))
            {
             return redirect('dashboard');   
            }
            else{
                return response()->json([
                            'message' => 'New request Created!',
                            'request_id' => $check,
                            'current_provider' => 0
                ]);}
            
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong') . $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.' . $e->getMessage());
            }
        }
    }


public function send_support_message_user(Request $request)
{
$uid = Auth::user()->id;
$user = User::where('id',$uid)->first();
//var_dump($user);
$name = $user->first_name.' '.$user->last_name;
$email = $user->email;
$message = $request->message; 
$client = new \GuzzleHttp\Client();
//jdoe@example.com/token:6wiIBWbGkBMo1mRDMuVwkw1EPsNkeUj95PIz2akv

$credentials = base64_encode('bleschool@gmail.com/token:ART7NkBf0GGFdoi8jbvbsudgahHnpuPDgvScKIhA');
$subject = $request->subject;
try {
//  $res = $client->request('POST', 'https://testing8137.zendesk.com/api/v2/requests.json', [
//     'Authorization' => ['Basic '.$credentials],
//     'body' => "{\"request\":{\"requester\": {\"name\": \"Anonymous customer\",\"email\":\"mianahmad115@gmail.com\"}, \"subject\": \"Help!\", \"comment\": {\"body\": \"My printer is on fire!\" }}}"
// ]);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://roadstarsupport.zendesk.com/api/v2/requests.json',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"request":{"requester": {"name": "'.$name.'","email":"'.$email.'"}, "subject": "'.$subject.'", "comment": {"body": "'.$message.'" }}
    }',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic '.$credentials,
    'Content-Type: application/json',
    'Cookie: __cfruid=d0fb4f3af350dfa977e7f41469f052bc228819c4-1640190010'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;
return response()->json(['message' => "We will get back to you shortly"], 200);
}
catch (\Exception $e) {

                   Log::info("***************Exception while calculating estimated arrival time of air trip***************");
                   Log::info($e);
                   
                   return response()->json(['error' => "Something went wrong!"], 500);
               }
// } catch ($e) {
//   echo $e->getRequest() . "\n";
  



}
   

    

}
