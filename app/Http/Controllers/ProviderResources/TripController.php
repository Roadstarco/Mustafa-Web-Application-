<?php

namespace App\Http\Controllers\ProviderResources;

use App\Document;
use App\Provider;
use App\Reward;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use Log;
use Setting;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Http\Controllers\SendPushNotification;
use DB;
use App\User;
use App\Admin;
use App\Promocode;
use App\UserRequests;
use App\RequestFilter;
use App\PromocodeUsage;
use App\PromocodePassbook;
use App\ProviderService;
use App\UserRequestRating;
use App\UserRequestPayment;
use App\ServiceType;
use App\WalletPassbook;
use Location\Coordinate;
use Location\Distance\Vincenty;
use App\Trip;
use App\TripRequests;
use App\TripPayments;
use App\Card;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeInvalidRequestError;
use App\SupportMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TripInvoice;
use App\SeaTripEstimatedArrival;
use App\AirTripFlightInfo;
use App\Airport;

class TripController extends Controller {

    public $paymentErrors;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

//die('here');
        Log::info("Provider latitude is");
        Log::info($request->latitude);


        Log::info("Provider longitude is");
        Log::info($request->longitude);



        try {
            if ($request->ajax()) {
                $Provider = Auth::user();
            } else {
                $Provider = Auth::guard('provider')->user();
            }
$Provider = Auth::user();
//var_dump($Provider->id);
            $provider = $Provider->id;
//var_dump($provider);
            //the below mentioned query line is commented by nabeel on 27-01-21

            // $AfterAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
            //         ->where('provider_id', 364)
            //         ->whereHas('request', function($query) use ($provider) {
            //     $query->where('status',0);
            //     $query->where('provider_id', 364);
            //     $query->where('current_provider_id', 364);
            // });

$AfterAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
                    ->where('provider_id', $provider)
                     ->whereHas('request', function($query) use ($provider) {
                     $query->where('current_provider_id', $provider);
                     $query->whereNotIn('status',['CANCELLED', 'SCHEDULED']);
                     })->get();
// var_dump("After Assign Provider");
// var_dump($AfterAssignProvider);
// die('here');
// $AfterAssignProvider = UserRequests::whereNotIn('status',['CANCELLED', 'SCHEDULED'])->where('provider_id', $provider)->where('current_provider_id', $provider)->get();
//->where('status', '<>', 'SCHEDULED');//->where('provider_id', $provider)->where('current_provider_id', $provider);
// var_dump("After Assign Provider");
// var_dump($AfterAssignProvider);
// die('here');

          //  Log::info("AfterAssignProvider is");

            //Log::info($AfterAssignProvider);

            if (Setting::get('broadcast_request', 0) == 1) {

                //Log::info("broadcast_request is 1");
//var_dump("i am here");
                $BeforeAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
                         ->where('provider_id', $provider)
                        ->whereHas('request', function($query) use ($provider) {
                          // $query->whereNotIn('status',['CANCELLED', 'SCHEDULED']);
                      $query->where('status',  'SEARCHING');
                    //  $query->where('status', '<>', 'SCHEDULED');
                    $query->where('current_provider_id', 0);
                }) ->get();
                //               $BeforeAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
                //          ->where('provider_id', $provider)
                //         ->whereHas('request', function($query) use ($provider) {
                //           // $query->whereNotIn('status',['CANCELLED', 'SCHEDULED']);
                //       $query->where('status',  'SEARCHING');
                //     //  $query->where('status', '<>', 'SCHEDULED');
                //     $query->where('current_provider_id', $provider);
                // }) ->get();
 
           //      var_dump($BeforeAssignProvider);
//                 die('here');
// $BeforeAssignProvider = UserRequests::whereNotIn('status',['CANCELLED', 'SCHEDULED'])->where('provider_id', 0)->where('current_provider_id', 0)->get();
// var_dump("$BeforeAssignProvider");
// var_dump($BeforeAssignProvider);
               // Log::info("BeforeAssignProvider is");
               // Log::info($BeforeAssignProvider);

            } else {
//$BeforeAssignProvider = UserRequests::whereNotIn('status',['CANCELLED', 'SCHEDULED'])->where('provider_id', $provider)->where('current_provider_id', $provider)->get();




                $BeforeAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
                        ->where('provider_id', $provider)
                        ->whereHas('request', function($query) use ($provider) {
                            $query->where('status', 'SEARCHING');
                    // $query->where('status', '<>', 'CANCELLED');
                    // $query->where('status', '<>', 'SCHEDULED');
                    $query->where('current_provider_id', $provider);
                });

                //Log::info("BeforeAssignProvider is");
               // Log::info($BeforeAssignProvider);
            }
// $IncomingRequests = UserRequests::whereNotIn('status',['CANCELLED', 'SCHEDULED'])->whereIn('provider_id', [$provider,0])->whereIn('current_provider_id', [$provider,0])->get()->toArray();           meeeeeee
// var_dump("Incoming requests");
// var_dump($IncomingRequests);     
// die('here');
          //  $IncomingRequests = array_merge($AfterAssignProvider,$BeforeAssignProvider);//$BeforeAssignProvider->union($AfterAssignProvider);
$IncomingRequests =$BeforeAssignProvider->union($AfterAssignProvider);
// var_dump($BeforeAssignProvider);
// var_dump($AfterAssignProvider);
// var_dump($IncomingRequests);
            //Log::info("IncomingRequests");
           // Log::info($IncomingRequests);

            if (!empty($request->latitude)) {
                $Provider->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }

            if (Setting::get('manual_request', 0) == 0) {

                //Log::info("manual_request is 0");
                
                $Timeout = Setting::get('provider_select_timeout', 180);
                if (!empty($IncomingRequests)) {
                    for ($i = 0; $i < sizeof($IncomingRequests); $i++) {
                        $IncomingRequests[$i]->time_left_to_respond = $Timeout - (time() - strtotime($IncomingRequests[$i]->request->assigned_at));
                        // var_dump($IncomingRequests[$i]->request->s_latitude);
                        // var_dump($IncomingRequests[$i]->request->d_latitude);
                        // var_dump($IncomingRequests[$i]->request->s_longitude);
                        // var_dump($IncomingRequests[$i]->request->d_longitude);
                        // var_dump($IncomingRequests[$i]->request->service_type_id);
                        $s_latitude =  $IncomingRequests[$i]->request->s_latitude;
                        $s_longitude = $IncomingRequests[$i]->request->s_longitude;
                        $service_typee =  $IncomingRequests[$i]->request->service_type_id;
                        $d_latitude = $IncomingRequests[$i]->request->d_latitude;
                        $d_longitude = $IncomingRequests[$i]->request->d_longitude;
                       // var_dump($this->estimate($s_latitude,$s_longitude,IncomingRequests[$i]->request->service_type_id,$IncomingRequests[$i]->request->d_latitude,$IncomingRequests[$i]->request->d_longitude));
                        $IncomingRequests[$i]->estimated_fair = $this->estimate($IncomingRequests[$i]->request->s_latitude,$IncomingRequests[$i]->request->s_longitude,$IncomingRequests[$i]->request->service_type_id,$IncomingRequests[$i]->request->d_latitude,$IncomingRequests[$i]->request->d_longitude);


                        $IncomingRequests[$i]->request->estimated_fair = $this->estimate($IncomingRequests[$i]->request->s_latitude,$IncomingRequests[$i]->request->s_longitude,$IncomingRequests[$i]->request->service_type_id,$IncomingRequests[$i]->request->d_latitude,$IncomingRequests[$i]->request->d_longitude);

//$IncomingRequests[$i]['time_left_to_respond'] = $Timeout - (time() - strtotime($IncomingRequests[$i]['assigned_at']));
                        // if ($IncomingRequests[$i]->request->status == 'SEARCHING' && $IncomingRequests[$i]->time_left_to_respond < 0) {
                        //     if (Setting::get('broadcast_request', 0) == 1) {
                        //         $this->assign_destroy($IncomingRequests[$i]->request->id);
                        //     } else {
                        //         $this->assign_next_provider($IncomingRequests[$i]->request->id);
                        //     }
                        // }

                         if ($IncomingRequests[$i]['status'] == 'SEARCHING' && $IncomingRequests[$i]['time_left_to_respond'] < 0) {
                        //     if (Setting::get('broadcast_request', 0) == 1) {
                                 $this->assign_destroy($IncomingRequests[$i]['id']);
                        //     } else {
                        //         $this->assign_next_provider($IncomingRequests[$i]['id']);
                        //     }
                         }

                    }
                }
            }

            $user_trips = DB::table('trips')
                    ->join('users', 'trips.user_id', '=', 'users.id')
                    ->select('trips.*', 'users.first_name', 'users.last_name', 'users.email', 'users.picture')
                    ->where(["trip_status" => "PENDING", "created_by" => "user"])
                    ->where('arrival_date', '>=', date('Y-m-d'))
                    ->orderBy('id','DESC')
                    ->get();
            foreach ($user_trips as $trip) {
                $bid = TripRequests::
                        where(['trip_id' => $trip->id, 'provider_id' => Auth::user()->id])->get()->first();

                $trip->bid_details = $bid ? $bid : (object)[];
            }
            $Response = [
                'account_status' => $Provider->status,
                'service_status' => $Provider->service ? Auth::user()->service->status : 'offline',
                'requests' => $IncomingRequests,
                'user_trips' => $user_trips
            ];

            return $Response;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Something went wrong']);
        }
    }



    public function estimate($s_latitude,$s_longitude,$service_typee,$d_latitude,$d_longitude)
    {
        try {

            $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $s_latitude . "," . $s_longitude . "&destinations=" . $d_latitude . "," . $d_longitude . "&mode=driving&sensor=false&key=" . Setting::get('map_key');

            $json = curl($details);

            $details = json_decode($json, TRUE);

            $meter = $details['rows'][0]['elements'][0]['distance']['value'];
            $time = $details['rows'][0]['elements'][0]['duration']['text'];
            $seconds = $details['rows'][0]['elements'][0]['duration']['value'];

            $kilometer = round($meter / 1000);
            $minutes = round($seconds / 60);

            $tax_percentage = Setting::get('tax_percentage');
            $commission_percentage = Setting::get('commission_percentage');
            $service_type = ServiceType::findOrFail($service_typee);

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

            $ActiveProviders = ProviderService::AvailableServiceProvider($service_typee)->get()->pluck('provider_id');

            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $s_latitude;
            $longitude = $s_longitude;

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
            return round($total, 2);
        }
        catch (Exception $e) {
            return -1;
        }
    }

    /**
     * Calculate distance between two coordinates.
     *
     * @return \Illuminate\Http\Response
     */
    public function calculate_distance(Request $request, $id) {
        $this->validate($request, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);
        try {

            if ($request->ajax()) {
                $Provider = Auth::user();
            } else {
                $Provider = Auth::guard('provider')->user();
            }


            $Response = [
                'status' => 'success',
                '$Provider' => $Provider,
                'requestSent' => $request->longitude,
            ];

            // return $Response;

            $UserRequest = UserRequests::where('status', 'PICKEDUP')
                    ->where('provider_id', $Provider->id)
                    ->find($id);

            if ($UserRequest && ($request->latitude && $request->longitude)) {

                Log::info("REQUEST ID:" . $UserRequest->id . "==SOURCE LATITUDE:" . $UserRequest->track_latitude . "==SOURCE LONGITUDE:" . $UserRequest->track_longitude);

                if ($UserRequest->track_latitude && $UserRequest->track_longitude) {

                    //$coordinate1 = new Coordinate($UserRequest->track_latitude, $UserRequest->track_longitude); /** Set Distance Calculation Source Coordinates ****/
                    //$coordinate2 = new Coordinate($request->latitude, $request->longitude); /** Set Distance calculation Destination Coordinates ****/
                    //$calculator = new Vincenty();

                    /*                     * *Distance between two coordinates using spherical algorithm (library as mjaschen/phpgeo) ** */

                    //$mydistance = $calculator->getDistance($coordinate1, $coordinate2);
                    //  $mydistance = distance($UserRequest->track_latitude, $UserRequest->track_longitude, $request->latitude, $request->longitude, 'K');
                    // $meters = round($mydistance);
                    //Log::info("REQUEST ID:".$UserRequest->id."==BETWEEN TWO COORDINATES DISTANCE:".$meters." (m)");
                    //if($mydistance >0.03364000){
                    /*                     * * If traveled distance riched houndred meters means to be the source coordinates ** */
                    //$traveldistance = round(($mydistance/1000),6);
                    //$traveldistance = $mydistance;
                    // if($traveldistance>0){

                    $calulatedistance = $request->distance;


                    $StartedDate = date_create($UserRequest->started_at);
                    $FinisedDate = Carbon::now();
                    $TimeInterval = date_diff($StartedDate, $FinisedDate);
                    $MintuesTime = $TimeInterval->i;


                    $UserRequest->travel_time = $MintuesTime;
                    $UserRequest->track_distance = $calulatedistance;
                    $UserRequest->distance = $calulatedistance;
                    $UserRequest->track_latitude = $request->latitude;
                    $UserRequest->track_longitude = $request->longitude;
                    $UserRequest->altitude = $request->altitude;
                    $UserRequest->bearing = $request->bearing;
                    $UserRequest->save();


                    //}
                } else if (!$UserRequest->track_latitude && !$UserRequest->track_longitude) {
                    $UserRequest->distance = 0;
                    $UserRequest->track_latitude = $request->latitude;
                    $UserRequest->track_longitude = $request->longitude;
                    $UserRequest->save();
                }
            }


            return $UserRequest;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Something went wrong']);
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "M") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    /**
     * Cancel given request.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request) {
        $this->validate($request, [
            'cancel_reason' => 'max:255',
        ]);

        try {

            $UserRequest = UserRequests::findOrFail($request->id);
            $Cancellable = ['SEARCHING', 'ACCEPTED', 'ARRIVED', 'STARTED', 'CREATED', 'SCHEDULED'];

            if (!in_array($UserRequest->status, $Cancellable)) {
                return back()->with(['flash_error' => 'Cannot cancel request at this stage!']);
            }

            $UserRequest->status = "CANCELLED";
            $UserRequest->cancel_reason = $request->cancel_reason;
            $UserRequest->cancelled_by = "PROVIDER";
            $UserRequest->save();

            RequestFilter::where('request_id', $UserRequest->id)->delete();

            ProviderService::where('provider_id', $UserRequest->provider_id)->update(['status' => 'active']);

            $Provider = Auth::user();
            $Provider->status = "onboarding";
            $Provider->save();



            // Send Push Notification to User
            (new SendPushNotification)->ProviderCancellRide($UserRequest);

            return $UserRequest;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Something went wrong']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rate(Request $request, $id) {

        Log::info("Local Request ratting is");
        Log::info($request->all());

        $this->validate($request, [
            'rating' => 'required|integer|in:1,2,3,4,5',
            'comment' => 'max:255',
        ]);

        try {

            $UserRequest = UserRequests::where('id', $id)
                    ->where('status', 'COMPLETED')
                    ->firstOrFail();

            if ($UserRequest->rating == null) {
                UserRequestRating::create([
                    'provider_id' => $UserRequest->provider_id,
                    'user_id' => $UserRequest->user_id,
                    'request_id' => $UserRequest->id,
                    'provider_rating' => $request->rating,
                    'provider_comment' => $request->comment,
                ]);
            } else {
                $UserRequest->rating->update([
                    'provider_rating' => $request->rating,
                    'provider_comment' => $request->comment,
                ]);
            }

            $UserRequest->update(['provider_rated' => 1]);

            // Delete from filter so that it doesn't show up in status checks.
            RequestFilter::where('request_id', $id)->delete();

            ProviderService::where('provider_id', $UserRequest->provider_id)->update(['status' => 'active']);

            // Send Push Notification to Provider
            $average = UserRequestRating::where('provider_id', $UserRequest->provider_id)->avg('provider_rating');

            $UserRequest->user->update(['rating' => $average]);

            return response()->json(['message' => 'Request Completed!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Request not yet completed!'], 500);
        }
    }

    /**
     * Get the trip history of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduled(Request $request) {

        try {

            $Jobs = UserRequests::where('provider_id', Auth::user()->id)
                    ->where('status', 'SCHEDULED')
                    ->with('service_type')
                    ->get();

            if (!empty($Jobs)) {
                $map_icon = asset('asset/img/marker-start.png');
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

            return $Jobs;
        } catch (Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }

    /**
     * Get the trip history of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request) {
    /*       Log::info("Provider latitude is");
        Log::info($request->latitude);


        Log::info("Provider longitude is");
        Log::info($request->longitude);

//var_dump(Auth::user());

        try {
            if ($request->ajax()) {
                $Provider = Auth::user();
            } else {
                $Provider = Auth::guard('provider')->user();
            }
            $Provider = Auth::user();
            $provider = $Provider->id;

            //the below mentioned query line is commented by nabeel on 27-01-21

            $AfterAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
                    ->where('provider_id', $provider)->where('status','!=', 'SEARCHING')->where('current_provider_id', $provider);
            //         ->whereHas('request', function($query) use ($provider) {
            //     $query->where('status', '<>', 'CANCELLED');
            //     $query->where('status', '<>', 'SCHEDULED');
            //     $query->where('provider_id', $provider);
            //     $query->where('current_provider_id', $provider);
            // });
            //$AfterAssignProvider = DB::table('user_requests')->where('status','SEARCHING')->where('provider_id', $provider)->get();

          //  Log::info("AfterAssignProvider is");

            //Log::info($AfterAssignProvider);

            if (Setting::get('broadcast_request', 0) == 1) {

                //Log::info("broadcast_request is 1");
                // var_dump("1");
                $BeforeAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
                        ->where('provider_id', 0)->where('status','SEARCHING')->where('current_provider_id',0);
                //         ->whereHas('request', function($query) use ($provider) {
                //     $query->where('status', '<>', 'CANCELLED');
                //     $query->where('status', '<>', 'SCHEDULED');
                //     $query->where('current_provider_id', 0);
                // });

//$BeforeAssignProvider = DB::table('user_requests')->where('status','SEARCHING')->get();

            //   var_dump("before");
            //   var_dump($BeforeAssignProvider);
            //   var_dump("done before");
            //   die('here');
               // Log::info("BeforeAssignProvider is");
               // Log::info($BeforeAssignProvider);

            } else {
          //      var_dump("2");
                $BeforeAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request'])
                        ->where('provider_id', $provider)
                        ->whereHas('request', function($query) use ($provider) {
                    $query->where('status', '<>', 'CANCELLED');
                    $query->where('status', '<>', 'SCHEDULED');
                    $query->where('current_provider_id', $provider);
                });

                //Log::info("BeforeAssignProvider is");
               // Log::info($BeforeAssignProvider);
            }

            $IncomingRequests = $BeforeAssignProvider->union($AfterAssignProvider)->get();

            //Log::info("IncomingRequests");
           // Log::info($IncomingRequests);

            if (!empty($request->latitude)) {
                $Provider->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }

            // if (Setting::get('manual_request', 0) == 0) {

            //     //Log::info("manual_request is 0");
                
            //     $Timeout = Setting::get('provider_select_timeout', 180);
            //     if (!empty($IncomingRequests)) {
            //         for ($i = 0; $i < sizeof($IncomingRequests); $i++) {
            //             $IncomingRequests[$i]->time_left_to_respond = $Timeout - (time() - strtotime($IncomingRequests[$i]->request->assigned_at));
            //             if ($IncomingRequests[$i]->request->status == 'SEARCHING' && $IncomingRequests[$i]->time_left_to_respond < 0) {
            //                 if (Setting::get('broadcast_request', 0) == 1) {
            //                     $this->assign_destroy($IncomingRequests[$i]->request->id);
            //                 } else {
            //                     $this->assign_next_provider($IncomingRequests[$i]->request->id);
            //                 }
            //             }
            //         }
            //     }
            // }

            $user_trips = DB::table('trips')
                    ->join('users', 'trips.user_id', '=', 'users.id')
                    ->select('trips.*', 'users.first_name', 'users.last_name', 'users.email', 'users.picture')
                    ->where(["trip_status" => "PENDING", "created_by" => "user"])
                    ->where('arrival_date', '>=', date('Y-m-d'))
                    ->orderBy('id','DESC')
                    ->get();
            foreach ($user_trips as $trip) {
                $bid = TripRequests::
                        where(['trip_id' => $trip->id, 'provider_id' => Auth::user()->id])->get()->first();

                $trip->bid_details = $bid ? $bid : (object)[];
            }
            $Response = [
                'account_status' => $Provider->status,
                'service_status' => $Provider->service ? Auth::user()->service->status : 'offline',
                'requests' => $IncomingRequests,
                'user_trips' => $user_trips
            ];

            return $Response;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Something went wrong']);
        }
    */    
        // return $request;
        // if ($request->ajax()) {
        if( $request->is('api/*')){
                              
            //*******Update by nabeel hassan start*********
             $Jobs=UserRequests::where('provider_id', Auth::user()->id)
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
                    
             $internationalJobs=Trip::where('provider_id', Auth::user()->id)
                    ->where('trip_status', 'COMPLETED')
                    ->orderBy('created_at', 'desc')
                     ->with('payment','tripRequest')
                    ->get();
                    
                   
                return response()->json(['localJobs' => $localJobs, 'internationalJobs' => $internationalJobs]) ;  
           
        }
         
        $Jobs = UserRequests::where('provider_id', Auth::guard('provider')->user()->id)->with('user', 'service_type', 'payment', 'rating')->get();
        return view('provider.trip.index', compact('Jobs'));
        
    }                                        

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, $id) {
        try {


            $x = DB::table('user_requests')->where('id',$id)->first();
            if($x->status != "SEARCHING") {
                return response()->json(['error' => 'Request already under progress!']);
            }
            $UserRequest = UserRequests::findOrFail($id);

            if ($UserRequest->status != "SEARCHING") {
                return response()->json(['error' => 'Request already under progress!']);
            }

            $UserRequest->provider_id = Auth::user()->id;

            if (Setting::get('broadcast_request', 0) == 1) {
                $UserRequest->current_provider_id = Auth::user()->id;
            }

            if ($UserRequest->schedule_at != "") {

                $beforeschedule_time = strtotime($UserRequest->schedule_at . "- 1 hour");
                $afterschedule_time = strtotime($UserRequest->schedule_at . "+ 1 hour");

                $CheckScheduling = UserRequests::where('status', 'SCHEDULED')
                        ->where('provider_id', Auth::user()->id)
                        ->whereBetween('schedule_at', [$beforeschedule_time, $afterschedule_time])
                        ->count();

                if ($CheckScheduling > 0) {
                    if ($request->ajax()) {
                        return response()->json(['error' => trans('api.ride.request_already_scheduled')]);
                    } else {
                        return redirect('dashboard')->with('flash_error', 'If the ride is already scheduled then we cannot schedule/request another ride for the after 1 hour or before 1 hour');
                    }
                }

                RequestFilter::where('request_id', $UserRequest->id)->where('provider_id', Auth::user()->id)->update(['status' => 2]);

                $UserRequest->status = "SCHEDULED";
                $UserRequest->save();
            } else {


                $UserRequest->status = "STARTED";
                $UserRequest->save();


                ProviderService::where('provider_id', $UserRequest->provider_id)->update(['status' => 'riding']);

                $Filters = RequestFilter::where('request_id', $UserRequest->id)->where('provider_id', '!=', Auth::user()->id)->get();
                // dd($Filters->toArray());
                foreach ($Filters as $Filter) {
                    $Filter->delete();
                }
            }

            $UnwantedRequest = RequestFilter::where('request_id', '!=', $UserRequest->id)
                    ->where('provider_id', Auth::user()->id)
                    ->whereHas('request', function($query) {
                $query->where('status', '<>', 'SCHEDULED');
            });

            if ($UnwantedRequest->count() > 0) {
                $UnwantedRequest->delete();
            }

            // Send Push Notification to User
             $this->send_notification_to_provider_request($UserRequest->user_id,"RoadStar","Ride Accepted");
            (new SendPushNotification)->RideAccepted($UserRequest);

            return $UserRequest->with('user')->get();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Unable to accept, Please try again later']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Connection Error']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        Log::info("from here local trip status is updating");

       

        $this->validate($request, [
            'status' => 'required|in:ACCEPTED,STARTED,ARRIVED,PICKEDUP,DROPPED,PAYMENT,COMPLETED',
        ]);

        

        try {

            $UserRequest = UserRequests::with('user')->findOrFail($id);

           

            if ($request->status == 'DROPPED' && $UserRequest->payment_mode != 'CASH') {

                $UserRequest->status = 'COMPLETED';


                
            } else if ($request->status == 'COMPLETED' && $UserRequest->payment_mode == 'CASH') {
                $UserRequest->status = $request->status;
                $UserRequest->paid = 1;
                // ProviderService::where('provider_id',$UserRequest->provider_id)->update(['status' =>'active']);

                
            } else {
                $UserRequest->status = $request->status;

                if ($request->status == 'ARRIVED') {

                    (new SendPushNotification)->Arrived($UserRequest);
                    $User = DB::table('users')->where('id', $UserRequest->user_id)->first();
                    $aa = $this->push_fcm($User->device_token, 'Driver Arrived ', 'Your driver has arrived');
                    $this->send_notification_to_provider_request($User->id,"RoadStar","Your driver has arrived");
                }
            }

            if ($request->status == 'PICKEDUP') {

                 

                if ($request->pickedUpImage != "") {
                $fileName = time() . '_' . $request->pickedUpImage->extension();
                $request->pickedUpImage->move(public_path('uploads/user/request/attachments'), $fileName);
                $UserRequest->pickedup_image = url('public/uploads/user/request/attachments/' . $fileName);
              //  $this->send_notification_to_provider_request($UserRequest->user_id,"RoadStar","Your driver has arrived");

            }


                if ($UserRequest->is_track == "YES") {
                    $UserRequest->distance = 0;
                }
                $UserRequest->started_at = Carbon::now();

            $this->send_notification_to_provider_request($UserRequest->user_id,"RoadStar","Your package has been pickedup by driver");
           // $aa = $this->push_fcm($User->device_token, 'Package Pickedup ', 'Your package has been pickedup by driver');

            }
$UserRequest->save();


            if ($request->status == 'DROPPED') {

                    if ($request->droppedOfImage != "") {

                    $fileName = time() . '_' . $request->droppedOfImage->extension();
                    $request->droppedOfImage->move(public_path('uploads/user/request/attachments'), $fileName);
                    $UserRequest->droppedof_image = url('public/uploads/user/request/attachments/' . $fileName);

                   // $aa = $this->push_fcm($User->device_token, 'Package Dropped ', 'Your package has been dropped by driver');
                 //$this->send_notification_to_provider_request($UserRequest->user_id,"Your package has been dropped by driver");

               }
                $this->send_notification_to_provider_request($UserRequest->user_id,"Roadstar","Your package has been dropped by driver");
                if ($UserRequest->is_track == "YES") {
                    $UserRequest->d_latitude = $request->latitude ?: $UserRequest->d_latitude;
                    $UserRequest->d_longitude = $request->longitude ?: $UserRequest->d_longitude;
                    $UserRequest->d_address = $request->address ?: $UserRequest->d_address;
                }
                $UserRequest->finished_at = Carbon::now();
                $StartedDate = date_create($UserRequest->started_at);
                $FinisedDate = Carbon::now();
                $TimeInterval = date_diff($StartedDate, $FinisedDate);
                $MintuesTime = $TimeInterval->i;
                $UserRequest->travel_time = $MintuesTime;
                $UserRequest->save();
                $UserRequest->with('user')->findOrFail($id);
                $UserRequest->invoice = $this->invoice($id);

                //(new SendPushNotification)->Dropped($UserRequest);
                //Helper::site_sendmail($UserRequest);
            }

            // Send Push Notification to User
            
            return $UserRequest;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Unable to update, Please try again later']);
        } catch (Exception $e) {
            Log::info($e);
            return $e;
            return response()->json(['error' => 'Connection Error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $UserRequest = UserRequests::find($id);
        $Provider = Auth::user();
        $Provider->save();

        try {
            $this->assign_next_provider($UserRequest->id);
            return $UserRequest->with('user')->get();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Unable to reject, Please try again later']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Connection Error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assign_destroy($id) {
        $UserRequest = UserRequests::find($id);
        try {
            UserRequests::where('id', $UserRequest->id)->update(['status' => 'CANCELLED']);
            // No longer need request specific rows from RequestMeta
            RequestFilter::where('request_id', $UserRequest->id)->delete();
            //  request push to user provider not available
            (new SendPushNotification)->ProviderNotAvailable($UserRequest->user_id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Unable to reject, Please try again later']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Connection Error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assign_next_provider($request_id) {

        try {
            $UserRequest = UserRequests::findOrFail($request_id);
        } catch (ModelNotFoundException $e) {
            // Cancelled between update.
            return false;
        }

        $RequestFilter = RequestFilter::where('provider_id', $UserRequest->current_provider_id)
                ->where('request_id', $UserRequest->id)
                ->delete();

        try {

            $next_provider = RequestFilter::where('request_id', $UserRequest->id)
                    ->orderBy('id')
                    ->firstOrFail();

            $UserRequest->current_provider_id = $next_provider->provider_id;
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->save();

            // incoming request push to provider
            (new SendPushNotification)->IncomingRequest($next_provider->provider_id);


            $ttt = $next_provider->provider_id;
            $ProviderDevice = DB::table('provider_devices')->where('provider_id', $ttt)->first();
            //$aa =  $this->push_fcm($ProviderDevice->token,'New Booking Request','Please open the app to accept booking');
        } catch (ModelNotFoundException $e) {

            UserRequests::where('id', $UserRequest->id)->update(['status' => 'CANCELLED']);

            // No longer need request specific rows from RequestMeta
            RequestFilter::where('request_id', $UserRequest->id)->delete();

            //  request push to user provider not available
            (new SendPushNotification)->ProviderNotAvailable($UserRequest->user_id);
        }
    }

    public function invoice($request_id) {
        try {
            $UserRequest = UserRequests::findOrFail($request_id);
            $tax_percentage = Setting::get('tax_percentage', 10);
            $commission_percentage = Setting::get('commission_percentage', 10);
            $provider_commission_percentage = Setting::get('provider_commission_percentage', 10);
            //$provider_service_type = DB::table('provider_services')->where('provider_id',$UserRequest->provider_id)->first();
            //$service_type = ServiceType::findOrFail($UserRequest->service_type_id);
            
            $service_type = ServiceType::findOrFail($UserRequest->service_type_id);

            $kilometer = $UserRequest->distance;
            $Fixed = $service_type->fixed;
            $Distance = 0;
            $minutes = $UserRequest->travel_time;
            $Discount = 0; // Promo Code discounts should be added here.
            $Wallet = 0;
            $Surge = 0;
            $ProviderCommission = 0;
            $ProviderPay = 0;
            $MaxAmountToAvailDiscount = 0;
            $MinAmountToAvailDiscount = 0;


            if ($service_type->calculator == 'MIN') {
                $Distance = $service_type->minute * $minutes;
            } else if ($service_type->calculator == 'HOUR') {
                $Distance = $service_type->minute * 60;
            } else if ($service_type->calculator == 'DISTANCE') {
                $Distance = ($kilometer * $service_type->price);
            } else if ($service_type->calculator == 'DISTANCEMIN') {
                $Distance = ($kilometer * $service_type->price) + ($service_type->minute * $minutes);
            } else if ($service_type->calculator == 'DISTANCEHOUR') {
                $Distance = ($kilometer * $service_type->price) + ($service_type->minute * $minutes * 60);
            } else {
                $Distance = ($kilometer * $service_type->price);
            }

            $Commision = ($Distance + $Fixed) * ($commission_percentage / 100);
            $Tax = ($Distance + $Fixed) * ($tax_percentage / 100);
            $ProviderCommission = ($Distance + $Fixed) * ($provider_commission_percentage / 100);
            $ProviderPay = ($Distance + $Fixed) - $ProviderCommission;
            $Total = $Fixed + $Distance + $Tax;

            if ($PromocodeUsage = PromocodeUsage::where('user_id', $UserRequest->user_id)->where('status', 'ADDED')->first()) {

                $Promocode = Promocode::find($PromocodeUsage->promocode_id);

                $maxUsageByUserCheck = PromocodeUsage::where('user_id', $UserRequest->user_id)->where('promocode_id', $PromocodeUsage->promocode_id)->count();
                $totalRemainingUsage = $PromocodeUsage->max_usage;
                $MaxAmountToAvailDiscount = $Promocode->max_amount_for_discount;
                $MinAmountToAvailDiscount = $Promocode->min_amount_for_discount;


                if ($maxUsageByUserCheck <= $Promocode->max_usage_by_user) {
                    if ($Total <= $MaxAmountToAvailDiscount && $Total >= $MinAmountToAvailDiscount) {
                        if ($PromocodeUsage) {
                            if ($Promocode) {
                                $Discount = $Promocode->discount;
                                $PromocodeUsage->status = 'ADDED';
                                $PromocodeUsage->max_usage = $totalRemainingUsage - 1;

                                if ($maxUsageByUserCheck <= $Promocode->max_usage_by_user) {
                                    $PromocodeUsage->status = 'USED';
                                } else {
                                    $PromocodeUsage->status = 'ADDED';
                                }


                                $PromocodeUsage->save();
                            }

                            PromocodePassbook::create([
                                'user_id' => Auth::user()->id,
                                'status' => 'ADDED',
                                'promocode_id' => $PromocodeUsage->promocode_id
                            ]);
                        }

                        if ($PromocodeUsage->promocode->discount_type == 'amount') {
                            $Total = $Total - $Discount;
                        } else {


                            $Total = ($Total) - (($Total) * ($Discount / 100));
                            $Discount = (($Fixed + $Distance + $Tax) * ($Discount / 100));
                        }
                    }
                }
            } else {

                $Total = $Total - $Discount;
            }


            if ($UserRequest->surge) {
                $Surge = (Setting::get('surge_percentage') / 100) * $Total;
                $Total += $Surge;
            }

            if ($Total < 0) {
                $Total = 0.00; // prevent from negative value
            }

            $Payment = new UserRequestPayment;
            $Payment->request_id = $UserRequest->id;

            /*
             * Reported by Jeya, We are adding the surge price with Base price of Service Type.
             */
            $Payment->fixed = $Fixed + $Surge;
            $Payment->distance = $Distance;
            $Payment->commision = $Commision;
            $Payment->surge = $Surge;
            $Payment->total = $Total;
            $Payment->provider_commission = $ProviderCommission;
            $Payment->provider_pay = $ProviderPay;
            if ($Discount != 0 && $PromocodeUsage) {
                $Payment->promocode_id = $PromocodeUsage->promocode_id;
            }
            $Payment->discount = $Discount;

            if ($Discount == ($Fixed + $Distance + $Tax)) {
                $UserRequest->paid = 1;
            }

            if ($UserRequest->use_wallet == 1 && $Total > 0) {

                $User = User::find($UserRequest->user_id);

                $Wallet = $User->wallet_balance;

                if ($Wallet != 0) {

                    if ($Total > $Wallet) {

                        $Payment->wallet = $Wallet;
                        $Payable = $Total - $Wallet;
                        User::where('id', $UserRequest->user_id)->update(['wallet_balance' => 0]);
                        $Payment->payable = abs($Payable);

                        WalletPassbook::create([
                            'user_id' => $UserRequest->user_id,
                            'amount' => $Wallet,
                            'status' => 'DEBITED',
                            'via' => 'TRIP',
                        ]);

                        // charged wallet money push
                        (new SendPushNotification)->ChargedWalletMoney($UserRequest->user_id, currency($Wallet));
                    } else {

                        $Payment->payable = 0;
                        $WalletBalance = $Wallet - $Total;
                        User::where('id', $UserRequest->user_id)->update(['wallet_balance' => $WalletBalance]);
                        $Payment->wallet = $Total;

                        $Payment->payment_id = 'WALLET';
                        $Payment->payment_mode = $UserRequest->payment_mode;

                        $UserRequest->paid = 1;
                        $UserRequest->status = 'COMPLETED';
                        $UserRequest->save();

                        WalletPassbook::create([
                            'user_id' => $UserRequest->user_id,
                            'amount' => $Total,
                            'status' => 'DEBITED',
                            'via' => 'TRIP',
                        ]);

                        // charged wallet money push
                        (new SendPushNotification)->ChargedWalletMoney($UserRequest->user_id, currency($Total));
                    }
                }
            } else {
                $Payment->total = abs($Total);
                $Payment->payable = abs($Total);
            }

            $reward_points = Reward::where('service_type_id', $UserRequest->service_type_id)->first();
            if ($reward_points) {
                $earned_points = $Total * $reward_points->points;
                User::where('id', $UserRequest->user_id)->update(['points' => floor($earned_points)]);
            }


            $provider = Provider::findOrFail(Auth::user()->id);
            $provider->commission_payable += $Payment->commision;
            $provider->wallet += $ProviderPay;
            $provider->save();

            /******* Updates By nabeel Hassan 31-12-2020**********/
            /******* if User Request Payment Mode Card **********/
            if($UserRequest->payment_mode=='CARD')
            {
                $User = User::find($UserRequest->user_id);

                        // Log::info("Local Trip Payment Mode is Card");
                        if ($User->stripe_cust_id) {



                                    Log::info("Local Trip Customer has Stripe id");

                                    $Card = Card::where('user_id', $User->id)->where('is_default', 1)->first();
                               

                                    Log::info("Local Payments Card Details are");
                                    Log::info($Card);


                                    Stripe::setApiKey(Setting::get('stripe_secret_key'));
                                    
                                    Log::info("Stripe Api Key is");
                                    Log::info(Setting::get('stripe_secret_key'));

                                    Log::info("Stripe Request Data is");
                                    Log::info("charge is::::");
                                    Log::info($Total);
                                    Log::info("customer is::::");
                                    Log::info($User->stripe_cust_id);
                                    Log::info("card is::::");
                                    Log::info($Card->card_id);
                                    
                                    $Charge = Charge::create(array(
                                                "amount" => ceil($Total*100),
                                                "currency" => "usd",
                                                "customer" => $User->stripe_cust_id,
                                                "card" => $Card->card_id,
                                                "description" => "Payment Charge for " . $User->email,
                                                "receipt_email" => $User->email
                                    ));
                                    
                                    Log::info("Charge is::::::::::::");
                                    Log::info($Charge);
                                    $Payment->payment_id = $Charge["id"];
                                    $Payment->payment_mode = 'CARD';
                                    $Payment->save();


                                    $UserRequest->paid = 1;
                                    $UserRequest->save();

                                    //to send payment invoice on mail
                                    // Mail::to($User->email)->send(new TripInvoice($UserRequest));

                                    $aa = $this->push_fcm($User->device_token, 'Ride Completed', 'Your ride has been completed');
                                    return $Payment;

                    }
            }
            /******* if User Request Payment Mode Card end here **********/
        
            else
            {
                $Payment->save();
                return $Payment;
            }

            
        } catch (ModelNotFoundException $e) {
            return false;
        }

        // catch(\Stripe\Exception\CardException $e) {

        //     $UserRequest->paid = 0;
        //     $UserRequest->status = 'DROPPED';
        //     $UserRequest->save();

        //     return response()->json([
        //         'Status' => $e->getHttpStatus(),
        //         'Type' => $e->getError()->type,
        //         'Code' => $e->getError()->code,
        //         'Param' => $e->getError()->param,
        //         'Message' => $e->getError()->message

        //      ]);
        // }
    }

    /**
     * Get the trip history details of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function history_details(Request $request) {
        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);

        if ($request->ajax()) {

            $Jobs = UserRequests::where('id', $request->request_id)
                    ->where('provider_id', Auth::user()->id)
                    ->with('payment', 'service_type', 'user', 'rating')
                    ->get();
            if (!empty($Jobs)) {
                $map_icon = asset('asset/img/marker-start.png');
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

            return $Jobs;
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_trips() {

        try {
            $UserRequests = UserRequests::ProviderUpcomingRequest(Auth::user()->id)->get();
            if (!empty($UserRequests)) {
                $map_icon = asset('asset/marker.png');
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
     * Get the trip history details of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_details(Request $request) {
        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);

        if ($request->ajax()) {

            $Jobs = UserRequests::where('id', $request->request_id)
                    ->where('provider_id', Auth::user()->id)
                    ->with('service_type', 'user')
                    ->get();
            if (!empty($Jobs)) {
                $map_icon = asset('asset/img/marker-start.png');
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

            return $Jobs;
        }
    }

    /**
     * Get the trip history details of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request) {
        try {
             
            // if ($request->ajax()) {
             if( $request->is('api/*')){
                $rides = UserRequests::where('provider_id', Auth::user()->id)->count();
               
                $revenue = UserRequestPayment::whereHas('request', function($query) use ($request) {
                            $query->where('provider_id', Auth::user()->id);
                        })
                        ->sum('total');
                        
                       
                $cancel_rides = UserRequests::where('status', 'CANCELLED')->where('provider_id', Auth::user()->id)->count();
                $scheduled_rides = UserRequests::where('status', 'SCHEDULED')->where('provider_id', Auth::user()->id)->count();
                
                
                
                $internationalRides=Trip::where('provider_id', Auth::user()->id)->count();
                // $internationalRidesRevenue = TripPayments::whereHas('payment', function($query) use ($request) {
                //             $query->where('provider_id', Auth::user()->id);
                //         })
                //         ->sum('total');
                
                  $internationalRidesRevenue = TripPayments::where('provider_id', Auth::user()->id)->sum('total');
               
                
                $international_cancel_rides = Trip::where('status', 'CANCELLED')->where('provider_id', Auth::user()->id)->count();
                $international_scheduled_rides = Trip::where('status', 'SCHEDULED')->where('provider_id', Auth::user()->id)->count();

                return response()->json([
                            'rides' => $rides,
                            'revenue' => $revenue,
                            'cancel_rides' => $cancel_rides,
                            'scheduled_rides' => $scheduled_rides,
                            'internationalRides' =>  $internationalRides,
                            'internationalRidesRevenue' => $internationalRidesRevenue,
                            'international_cancel_rides' => $international_cancel_rides,
                            'international_scheduled_rides' =>  $international_scheduled_rides,
                            
                            
                ]);
            }
        } catch (Exception $e) {
            return $e;
            return response()->json(['error' => trans('api.something_went_wrong')]);
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function documents_listing(Request $request) {

        try {
            if ($request->ajax()) {
                $document = Document::orderBy('created_at', 'desc')->get();

                return $document;
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function push_fcm($token, $title, $message) {

        // $serverKey = 'AAAA9P8P04Q:APA91bFvb9hnuwIpscd65Q4XDxCVyodTFBgPaUmZaphM0oUQUmA6BZUhrQkW4quAACHBDMPa5p6MGgyYTw9OGPqqkhXoghxGVJN7uWD6PX-bNk_HcUWibtu4iEBiJEULBW89qRva-3Ta';
        $serverKey = 'AAAAb9KJPtg:APA91bGcs0D6JcsjEo0Bk3-0HIf5ffPBeG3I4ucGDI8BrXZGIR-2vwykTzJZ1FhtojJgRhgkn1Fbxb_zTCnqIivUvwF5M-CsDT5mCi6Luj728YD5JiCaYwtY0WHPhtzNt9jnKIivBYyg';
        $body = array(
            "to" => $token,
            "notification" => array(
                "title" => $title,
                "body" => $message,
                "sound" => "default"
            ),
            "data" => array("targetScreen" => "detail"),
            "priority" => 10
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

        return 'not';
    }

// Provider Travel Request Apis 20-11-2020
    public function post_trip(Request $request) {

        $booking_id = Helper::generate_booking_id();

        $trip = new Trip();

        $trip->booking_id = $booking_id;
        $trip->provider_id = Auth::user()->id;
        $trip->arrival_date = date('Y-m-d', strtotime($request->arrival_date));
        $trip->tripto = $request->tripto;
        $trip->tripfrom = $request->tripfrom;
        $trip->recurrence = $request->recurrence;
        $trip->item_size = $request->item_size;
        $trip->other_information = $request->other_information;
        $trip->service_type = $request->service_type;
        $trip->tripfrom_lat = $request->tripto_lat;
                    $trip->tripfrom_lng = $request->tripto_lng;
                    $trip->tripto_lat = $request->tripfrom_lat;
                    $trip->tripto_lng = $request->tripfrom_lng;

        $trip->save();

        if ($request->return_date !== '' && (strtotime($request->return_date) >= strtotime($request->arrival_date)) && $request->recurrence == 'never') {
            $trip2 = new Trip();
            $trip2->booking_id = $booking_id;
            $trip2->arrival_date = date('Y-m-d', strtotime($request->return_date));
            $trip2->tripto = $request->tripfrom;
            $trip2->tripfrom = $request->tripto;

            $trip2->provider_id = Auth::user()->id;
            $trip->tripto_lat = $request->tripto_lat;
                    $trip->tripto_lng = $request->tripto_lng;
                    $trip->tripfrom_lat = $request->tripfrom_lat;
                    $trip->tripfrom_lng = $request->tripfrom_lng;

            $trip2->recurrence = $request->recurrence;
            $trip2->item_size = $request->item_size;
            $trip2->other_information = $request->other_information;
            $trip2->service_type = $request->service_type;
            $trip2->save();
        }
        if ($trip) {
            return response()->json($trip, 200);
        } else {
            return response()->json(['error' => "Trips not available"], 500);
        }
    }

    /**
     * Provider_trips Trips
     *
     */
    public function provider_trips(Request $request) {

        $provider_id = Auth::user()->id;
        $usertrips = Trip::where(['provider_id' => $provider_id])
                ->orderBy('id', 'DESC')
                ->get();
                        Log::info($usertrips);

         foreach($usertrips as $usertrip)
        {
            if(is_null($usertrip->user_id))
            {
                $usertrip["avatar"]=null;
            }

            else
            { 
                $user=User::find($usertrip->user_id);
                $usertrip["avatar"]=$user->picture;
                $usertrip["first_name"] = $user->first_name;
                $usertrip["last_name"] = $user->last_name;
                $usertrip["email"] = $user->email;
                $usertrip["picture"]=$user->picture;
               
            }

            //add card_last_4 in response if payment is done and payment type is card
            if($usertrip->payment)
            {
                if($usertrip->payment->card_id!=0)
                {
                    $card=Card::find($usertrip->payment->card_id);
                    if($card == null)
                    {
                        $usertrip["card_last_4"]=null;
                    }
                    else{
                    $usertrip["card_last_4"]=$card->last_four;
                    }
                }
                else
                {
                    $usertrip["card_last_4"]=null;
                }
            }

        }

        if ($usertrips) {
            return response()->json($usertrips);
        } else {
            return response()->json(['error' => "No trips available to show"], 500);
        }
    }

    /**
     * Show Trip Bids 
     *
     */
    public function trip_bids(Request $request) {

        if ($request->has('trip_id')) {
            $trip_id = $request->trip_id;
        }
        $bids = DB::table('trip_requests')
                ->join('trips', 'trip_requests.trip_id', '=', 'trips.id')
                ->join('users', 'trip_requests.user_id', '=', 'users.id')
                ->select('trip_requests.*', 'trips.service_type AS trip_service_type', 'users.first_name', 'users.last_name', 'users.email', 'users.picture',                                      'users.mobile')
                ->where(['trip_requests.trip_id' => $trip_id])
                ->where('trip_requests.status', '!=', 'Rejected')
                ->get();

        if ($bids) {
            return response()->json($bids, 200);
        } else {
            return response()->json(['error' => "Trips not available"], 500);
        }
    }

    /**
     * Update Bid Status
     *
     */
    public function update_bid(Request $request) {

        // dd("hello");

        try {

            if ($request->status == 'Approved') {
                TripRequests:: where('trip_id', $request->trip_id)
                        ->where('id', '!=', $request->bid_id)
                        ->update(['status' => 'Rejected']);

                TripRequests::
                        where('id', $request->bid_id)
                        ->update(['status' => 'Approved', 'traveller_response' => $request->traveller_response]);

                $bid = TripRequests::
                        where('id', $request->bid_id)->first();
                   
                 Log::info("******************update_bid******************");
                 Log::info($bid);
                
                Trip::where('id', $request->trip_id)
                        ->update([
                            'user_id' => $bid->user_id,
                            'trip_status' => 'SCHEDULED',
                            'trip_amount' => $bid->amount,
                            'item' => $bid->item,
                            'item_type' => $bid->item_type,
                            'receiver_name' => $bid->receiver_name,
                            'receiver_phone' => $bid->receiver_phone,
                            'picture1' => $bid->picture1,
                            'picture2' => $bid->picture2,
                            'picture3' => $bid->picture3,

                ]);
                return response()->json($bid);
            } else {
                $bid = TripRequests::findOrFail($request->bid_id);
                $bid->status = $request->status;
                $bid->traveller_response = $request->traveller_response;
                $bid->save();
                return response()->json($bid);
            }
            //return response()->json($bid);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Rate user
     *
     */
    public function rate_trip_user(Request $request) {

        try {

            // $UserRequest = Trip::where('id', $request->trip_id)
            //         ->where('trip_status', 'COMPLETED')
            //         ->first();

            $UserRequest = Trip::where('id', $request->trip_id)
            ->where('trip_status', 'COMPLETED')
            ->first();

            if (!$UserRequest) {
                return response()->json(['error' => "Trip not completed"], 500);
            }
            $trip_rating = UserRequestRating::where('trip_id', $request->trip_id)->first();
            if (!$trip_rating) {

                $bid = TripRequests:: where('trip_id', $request->trip_id)->first();

                $addrating = new UserRequestRating();
                $addrating['provider_id'] = $UserRequest->provider_id ? $UserRequest->provider_id : $bid->provider_id;
                $addrating['user_id'] = $bid->user_id ? $bid->user_id : $UserRequest->user_id;
                $addrating['trip_id'] = $request->trip_id;
                $addrating['provider_rating'] = $request->rating;
                $addrating['provider_comment'] = $request->comment;
                $addrating->save();
            } else {

                $trip_rating->provider_rating = $request->rating;
                $trip_rating->provider_comment = $request->comment;
                $trip_rating->save();
            }


            $average = UserRequestRating::where('user_id', $UserRequest->user_id)->avg('provider_rating');

            User::where('id', $UserRequest->user_id)->update(['rating' => $average]);
            Trip::where('id', $request->trip_id)->update(['provider_rated' => 1]);

            return response()->json(['message' => 'Request Completed!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Request not yet completed!'], 500);
        }
    }



    public function trip_details_new(Request $request)
    {
     $tid = $request->trip_id;
     $uid = $request->uid;
     $trip = DB::table('trips')->where('id',$tid)->first();
                $bid = TripRequests::where(['trip_id' => $request->trip_id, 'provider_id' => $uid])->get()->first();

                $trip->bid_details = $bid ? $bid : (object)[];

    $user=User::find($trip->user_id);
                $trip->avatar=$user->picture;
                $trip->first_name = $user->first_name;
                $trip->last_name = $user->last_name;
                $trip->email = $user->email;
                $trip->picture=$user->picture;
    
            
     return response()->json(['Trip' => $trip]);
    }

    /**
     * Update User Trip
     *
     */
    public function update_trip(Request $request) {

        $this->validate($request, [
            'trip_status' => 'required|in:STARTED,ARRIVED,PICKEDUP,DROPPED,COMPLETED',
        ]);

        Log::info('request status with data is in international trip update');

        Log::info($request->all());

        try {
         
            $trip=Trip::find($request->trip_id);
            $tripLastStatus=$trip->trip_status;

            Trip::where('id',$request->trip_id)->update(["trip_status" => $request->trip_status]);
            // $Trip = Trip::find($request->trip_id)->first();

             $Trip = Trip::find($request->trip_id);

            $User=User::findOrFail($Trip->user_id);

            // return;

            if ($request->trip_status == 'STARTED' && $Trip->service_type=="By Sea") {

                Log::info('trip is started and service type is see');

               $tripVesselInfo =$this->storeTripVesselInfo($request);

               if($tripVesselInfo=="success")
               {

               }else{

                    // Trip::find($request->trip_id)->update(["trip_status" => $tripLastStatus]);
                    // return response()->json(['error' => 'Sorry we are unable to find sea trip info']);
               }

           }
           
           if ($request->trip_status == 'STARTED' && $Trip->service_type=="By Air") {

            Log::info('trip is started and service type is air');

           $tripFlightInfo =$this->storeTripFlightInfo($request);

           Log::info("***********tripFlightInfo***********");
           Log::info($tripFlightInfo);

           if($tripFlightInfo=="success")
           {

           }else{
                Trip::find($request->trip_id)->update(["trip_status" => $tripLastStatus]);
                Log::info("Sorry we are unable to find flight info");
                return response()->json(['error' => 'Sorry we are unable to find flight info']);
           }

       }

             if ($request->trip_status == 'ARRIVED') {

                 $aa = $this->push_fcm($User->device_token, 'Driver Arrived ', 'Your driver has arrived');
                 $this->send_notification_to_provider_request($User->id,"RoadStar","Your driver has arrived");
            }

             if ($request->trip_status == 'PICKEDUP')
              {
                    Log::info($request->all());
                    if ($request->pickedUpImage != "") {
                        
                    $fileName = time() . '.' . $request->pickedUpImage->extension();
                    $request->pickedUpImage->move(public_path('uploads/'), $fileName);
                    $Trip->pickedup_image = url('public/uploads/' . $fileName);
                    $Trip->save();

                }
                 $this->send_notification_to_provider_request($User->id,"RoadStar","Your package has been pickedup by driver");
                 $aa = $this->push_fcm($User->device_token, 'Package Pickedup ', 'Your package has been pickedup by driver');
            }


            if ($request->trip_status == 'DROPPED') {

                    Log::info($request->all());
                    if ($request->droppedOfImage != "") {


                    $fileName = time() . '.' . $request->droppedOfImage->extension();
                    $request->droppedOfImage->move(public_path('uploads/'), $fileName);
                    $Trip->droppedof_image = url('public/uploads/' . $fileName);
                    $Trip->save();
               }
            $this->send_notification_to_provider_request($User->id,"RoadStar","Your package has been dropped by driver");
               $aa = $this->push_fcm($User->device_token, 'Package Dropped ', 'Your package has been dropped by driver');
            }

            
            Log::info("Trip Id is :::::::::::::");
            Log::info($request->trip_id);
            
            if($request->trip_status=="COMPLETED")
            {

                Log::info("Trip is completed");
                $paymentStatus=$this->trip_payment($request->trip_id);
                
                
                if($paymentStatus=="PAID")
                {
                    Log::info("Trip is Paid");
                    return response()->json(['Trip' => $Trip,'message' => trans('api.paid')]);
                     $this->send_notification_to_provider_request($User->id,"RoadStar","Your ride has been completed");
                     $aa = $this->push_fcm($User->device_token, 'Ride Completed', 'Your ride has been completed');
                }
                
                elseif($paymentStatus=="Not PAID")
                {
                     Log::info("Trip is not Paid");
                    return response()->json(['Trip' => $Trip,'error' => $this->paymentErrors]);
                }
                
                else
                {
                    Log::info("card is not found");
                    return response()->json(['error' => 'Please add credit card details first']);

                }
            }

            return $Trip;
        } catch (ModelNotFoundException $e) {
            Log::info("Model not found exception");
            Log::info($e);
            return response()->json(['error' => 'Unable to update, Please try again later']);
        } catch (Exception $e) {
            Log::info("Update Trip exception");
            Log::info($e);
            return response()->json(['error' => 'Connection Error']);
        }
    }

        //***********Stor Trip vessel info method added by nabeel start************

            public function storeTripVesselInfo(Request $request)
            {
                try {
                        //code...

                        Trip::find($request->trip_id)->update(["vessel_id" => $request->vessel_id, "vessel_imo" => $request->vessel_imo, "vessel_name" => $request->vessel_name,
                        "source_port_id" => $request->source_port_id, "destination_port_id" => $request->destination_port_id]);

                        $vesselId=$request->vessel_id;
                        $vesselImo=$request->vessel_imo;
                        $vesselName=$request->vessel_name;
                        $sourcePortId=$request->source_port_id;
                        $destinationPortId=$request->destination_port_id;

                        $client = new \GuzzleHttp\Client();
                        // $res = $client->request('GET', 'https://api.vesselfinder.com/portcalls?userkey=AABBCCDD&interval=14400&imo=9175717');
                        $res = $client->request('GET', 'https://services.marinetraffic.com/en/api/etatoport/'.Setting::get('marine_traffic_eta_api_key', '1ffd1c15b665ea045e4f9884b230dc70d892c74f').'/protocol:json/portid:'.$destinationPortId.'/shipid:'.$vesselId.'/msgtype:simple');

                        Log::info('marinetraffic api response is');
                        Log::info($res->getBody());


                        if($res->getStatusCode()=='200')
                        { 
                            $body= $res->getBody();
                            $vesselExpectedArrivalAtDestination = json_decode($body);

                            if(!empty($vesselExpectedArrivalAtDestination[0][0]))
                            {

                            $shipId= $vesselExpectedArrivalAtDestination[0][0];
                            $mmsi= $vesselExpectedArrivalAtDestination[0][1];
                            $imo= $vesselExpectedArrivalAtDestination[0][2];
                            $lastPortId= $vesselExpectedArrivalAtDestination[0][3];
                            $lastPort= $vesselExpectedArrivalAtDestination[0][4];
                            $lastPortUNLoCode= $vesselExpectedArrivalAtDestination[0][5];
                            $lastPortTime= $vesselExpectedArrivalAtDestination[0][6];
                            $nextPortName= $vesselExpectedArrivalAtDestination[0][7];
                            $nextPortUNLoCode= $vesselExpectedArrivalAtDestination[0][8];
                            $etaCalc= $vesselExpectedArrivalAtDestination[0][9];

                            $seaTripEstimatedArrival= new SeaTripEstimatedArrival();
                            $seaTripEstimatedArrival->trip_id = $request->trip_id;
                            $seaTripEstimatedArrival->ship_id = $shipId;
                            $seaTripEstimatedArrival-> mmsi = $mmsi;
                            $seaTripEstimatedArrival->imo = $imo;
                            $seaTripEstimatedArrival->last_port_id = $lastPortId;
                            $seaTripEstimatedArrival->last_port = $lastPort;  
                            $seaTripEstimatedArrival->last_port_unlocode = $lastPortUNLoCode; 
                            $seaTripEstimatedArrival->last_port_time = $lastPortTime; 
                            $seaTripEstimatedArrival->next_port_name = $nextPortName; 
                            $seaTripEstimatedArrival->next_port_unlocode= $nextPortUNLoCode;
                            $seaTripEstimatedArrival->eta_calc = $etaCalc; 
                            // $seaTripEstimatedArrival->api_json_response = $body; 
                            $seaTripEstimatedArrival->save();

                            return "success";
                            }{

                                return "error";
                            }
                        }else{

                            return "error";
                        }

                } catch (\Exception $e) {

                    Log::info("***************Exception while calculating estimated arrival time of sea trip***************");
                    Log::info($e);

                    return "error";
                }
                
            }

        //Store Vessel

        // public function storeTripVesselInfoNew(Request $request)
        //     {
        //         try {
        //                 //code...

        //                 Trip::find($request->trip_id)->update(["vessel_id" => $request->vessel_id, "vessel_imo" => $request->vessel_imo, "vessel_name" =>$request->vessel_name);

        //                 $vesselId=$request->vessel_id;
        //                 $vesselImo=$request->vessel_imo;
        //                 $vesselName=$request->vessel_name;
        //                 return "success";

                        

        //         } catch (\Exception $e) {

        //             Log::info("***************Exception while calculating estimated arrival time of sea trip***************");
        //             Log::info($e);

        //             return "error";
        //         }
                
        //     }


        //***********Store Trip vessel info method added by nabeel end************


           //***********Stor Trip flight info method added by nabeel start************

           public function storeTripFlightInfo(Request $request)
           {
               try {
                       //code...

                       Trip::find($request->trip_id)->update(["flight_no" => $request->flight_no, "departure_time" => $request->departure_time, "airport" => $request->airport]);

                       $flightNo=$request->flight_no;
                       $airport=$request->airport;
                       $iden = $request->ident;
                       $client = new \GuzzleHttp\Client();
                       // $res = $client->request('GET', 'https://api.vesselfinder.com/portcalls?userkey=AABBCCDD&interval=14400&imo=9175717');
                      
$n=0;

                       for($b=true;$b!=false;)
                       {
        $test="http://IsaacImasuagbon:7f4a458f262566736e9217bf7ea3636a92d17821@flightxml.flightaware.com/json/FlightXML2/Scheduled?howMany=100&airport=".$airport."&offset=".$n;
                           $ress = $client->request('GET',      "http://IsaacImasuagbon:7f4a458f262566736e9217bf7ea3636a92d17821@flightxml.flightaware.com/json/FlightXML2/Scheduled?howMany=100&airport=".$airport."&offset=".$n); 
                        //   var_dump($test);
                       if($ress->getStatusCode()=='200')
                       {
                           //var_dump('success');
                           $body1= $ress->getBody();
                           
                          $obj1 = json_decode($body1, true);
                         // var_dump($obj1);
                          $n = $obj1['ScheduledResult']['next_offset'];

                          if($n==-1)
                          {
                             $b = false;   
                          }
                          if(!empty($obj1['ScheduledResult']['scheduled']))
                          {
                                $fl= preg_replace('/[^0-9]/', '', $flightNo);
                                $scheduled= $obj1['ScheduledResult']['scheduled']; 
                                $collection = collect($scheduled);
                                // var_dump($fl);
                                // var_dump($iden);
                                for($z=0;$z<sizeof($scheduled);$z++)
                                {
                                    $fl1 = preg_replace('/[^0-9]/', '', $obj1['ScheduledResult']['scheduled'][$z]['ident']);
                                    //var_dump('fl1');
                                    //var_dump($fl1);
                                    if(($fl1 == $fl) && ($obj1['ScheduledResult']['scheduled'][$z]['origin']==$iden))
                                    {
                                        // var_dump('found');
                                        // var_dump($fl1);
                                        // var_dump($fl);    
                                        $airTripFlightInfo= new AirTripFlightInfo();
                                            $airTripFlightInfo->trip_id = $request->trip_id;
                                            //var_dump('1');
                                            $airTripFlightInfo->ident = $obj1['ScheduledResult']['scheduled'][$z]['ident'];
                                            //var_dump('2');
                                            $airTripFlightInfo->aircraft_type = $obj1['ScheduledResult']['scheduled'][$z]['aircrafttype'];
                                            //var_dump('3');
                                            $airTripFlightInfo->filed_departure_time = $obj1['ScheduledResult']['scheduled'][$z]['filed_departuretime'];
                                            //var_dump('4');
                                            $airTripFlightInfo->estimated_arrival_time = $obj1['ScheduledResult']['scheduled'][$z]['estimatedarrivaltime'];
                                            //var_dump('5');
                                            $airTripFlightInfo->origin = $obj1['ScheduledResult']['scheduled'][$z]['origin'];
                                            //var_dump('6');
                                            $airTripFlightInfo->destination = $obj1['ScheduledResult']['scheduled'][$z]['destination'];  
                                            //var_dump('7');
                                            $airTripFlightInfo->origin_name = $obj1['ScheduledResult']['scheduled'][$z]['originName']; 
                                            //var_dump('8');
                                            $airTripFlightInfo->origin_city = $obj1['ScheduledResult']['scheduled'][$z]['originCity'];
                                            //var_dump('9');
                                            $airTripFlightInfo->destination_name = $obj1['ScheduledResult']['scheduled'][$z]['destinationName']; 
                                            //var_dump('10');
                                            $airTripFlightInfo->destination_city = $obj1['ScheduledResult']['scheduled'][$z]['destinationCity']; 
                                           // var_dump('11');
                                            // $seaTripEstimatedArrival->api_json_response = $body; 
                                           // var_dump($airTripFlightInfo);
                                            $airTripFlightInfo->save();
                                            //die($airTripFlightInfo);
                                            return "success";

                                    }
                                }
                                //die('lun');
                         /*       $filtered = $collection->where('origin',$iden)->first();
                                //var_dump($filtered);
                                if($filtered)
                                {
                                 
                                            //var_dump('found');
                                            $airTripFlightInfo= new AirTripFlightInfo();
                                            $airTripFlightInfo->trip_id = $request->trip_id;
                                            $airTripFlightInfo->ident = $filtered['ident'];
                                            $airTripFlightInfo->aircraft_type = $filtered['aircraftType'];
                                            $airTripFlightInfo->filed_departure_time = $filtered['filedDepartureTime'];
                                            $airTripFlightInfo->estimated_arrival_time = $filtered['estimatedArrivalTime'];
                                            $airTripFlightInfo->origin = $filtered['origin'];
                                            $airTripFlightInfo->destination = $filtered['destination'];  
                                            $airTripFlightInfo->origin_name = $filtered['originName']; 
                                            $airTripFlightInfo->origin_city = $filtered['originCity'];
                                            $airTripFlightInfo->destination_name = $filtered['destinationName']; 
                                            $airTripFlightInfo->destination_city = $filtered['destinationCity']; 
                                            
                                            // $seaTripEstimatedArrival->api_json_response = $body; 
                                            $airTripFlightInfo->save();
                                    die($airTripFlightInfo);
                                    return "success";
                                }
                                */

                          }
                          
                          

                       }    

                       }
                       return "error";
                       
                        $res = $client->request('GET', 'http://IsaacImasuagbon:'.Setting::get('flightaware_api_key', '').'@flightxml.flightaware.com/json/FlightXML2/Scheduled?airport='.$airport);
                       //var_dump($res);

                       Log::info('flightaware api response is');
                       Log::info($res->getBody());
                        

                       if($res->getStatusCode()=='200')
                       { 
                           $body= $res->getBody();
                          $obj = json_decode($body, true);

                           if(!empty($obj['ScheduledResult']['scheduled']))
                           {
                               
                            $scheduled= $obj['ScheduledResult']['scheduled']; 
                            $collection = collect($scheduled);
                            var_dump($collection->count());
die();
                            $filtered = $collection->where('ident', $flightNo)->first();
                            if(!$filtered)
                            {
                                $res1 = $client->request('GET', 'http://IsaacImasuagbon:'.Setting::get('flightaware_api_key', '').'@flightxml.flightaware.com/json/FlightXML2/Search?query=-idents '.$flightNo);
                                var_dump($res1->getStatusCode());
                                $obj1 = json_decode($res1->getBody(), true);
                                var_dump( $obj1);

                               //die();    
                                if($res->getStatusCode()=='200')
                                { 
                                    $body= $res->getBody();
                                    $obj = json_decode($body, true);

                                    Log::info("Flight Search Request Response is");
                                    Log::info($obj);
                                    
                                    if(!empty($obj['SearchResult']['aircraft']))
                                    {
                                            $flightInfo=$obj['SearchResult']['aircraft'][0];

                                            if($flightInfo['origin']==$airport){

                                            // Log::info("Flight Search Info is");
                                            // Log::info($flightInfo['ident']);

                                            $ident= $flightInfo['ident'];
                                            $aircraftType= $flightInfo['type'];
                                            $filedDepartureTime= $flightInfo['departureTime'];
                                            $estimatedArrivalTime= $flightInfo['arrivalTime'];
                                            $origin= $flightInfo['origin'];
                                            $destination= $flightInfo['destination'];

                                            $originAirport=Airport::where('ident', $flightInfo['origin'])->first();
                                            $destinationAirport=Airport::where('ident', $flightInfo['destination'])->first();

                                            $originName= $originAirport->name;
                                            $originCity= $originAirport->municipality;
                                            $destinationName= $destinationAirport->name;
                                            $destinationCity= $destinationAirport->municipality;

                                            $airTripFlightInfo= new AirTripFlightInfo();
                                            $airTripFlightInfo->trip_id = $request->trip_id;
                                            $airTripFlightInfo->ident = $ident;
                                            $airTripFlightInfo->aircraft_type = $aircraftType;
                                            $airTripFlightInfo->filed_departure_time = $filedDepartureTime;
                                            $airTripFlightInfo->estimated_arrival_time = $estimatedArrivalTime;
                                            $airTripFlightInfo->origin = $origin;
                                            $airTripFlightInfo->destination = $destination;  
                                            $airTripFlightInfo->origin_name = $originName; 
                                            $airTripFlightInfo->origin_city = $originCity;
                                            $airTripFlightInfo->destination_name = $destinationName; 
                                            $airTripFlightInfo->destination_city = $destinationCity; 
                                            
                                            // $seaTripEstimatedArrival->api_json_response = $body; 
                                            $airTripFlightInfo->save();
                                                return "success";

                                        }else{

                                            return "error";
                                        } 

                                    }else{

                                        return "error";
                                    }

                                }else{

                                    return "error";
                                }
                            }

                           $ident= $filtered['ident'];
                           $aircraftType= $filtered['aircrafttype'];
                           $filedDepartureTime= $filtered['filed_departuretime'];
                           $estimatedArrivalTime= $filtered['estimatedarrivaltime'];
                           $origin= $filtered['origin'];
                           $destination= $filtered['destination'];
                           $originName= $filtered['originName'];
                           $originCity= $filtered['originCity'];
                           $destinationName= $filtered['destinationName'];
                           $destinationCity= $filtered['destinationCity'];
                


                           $airTripFlightInfo= new AirTripFlightInfo();
                           $airTripFlightInfo->trip_id = $request->trip_id;
                           $airTripFlightInfo->ident = $ident;
                           $airTripFlightInfo->aircraft_type = $aircraftType;
                           $airTripFlightInfo->filed_departure_time = $filedDepartureTime;
                           $airTripFlightInfo->estimated_arrival_time = $estimatedArrivalTime;
                           $airTripFlightInfo->origin = $origin;
                           $airTripFlightInfo->destination = $destination;  
                           $airTripFlightInfo->origin_name = $originName; 
                           $airTripFlightInfo->origin_city = $originCity;
                           $airTripFlightInfo->destination_name = $destinationName; 
                           $airTripFlightInfo->destination_city = $destinationCity; 
                         
                           // $seaTripEstimatedArrival->api_json_response = $body; 
                           $airTripFlightInfo->save();
                            return "success";
                           }{

                               return "error";
                           }
                       }else{

                           return "error";
                       }

               } catch (\Exception $e) {

                   Log::info("***************Exception while calculating estimated arrival time of air trip***************");
                   Log::info($e);
                   
                   return "error";
               }
               
           }



       //***********Store Trip flight info method added by nabeel end************

    
         //***********Trip payment method added by nabeel start************
    
        public function trip_payment($tripId) {
            
            

        Log::info("trip_payment func called successfully");
        Log::info($tripId);
        
        Log::info("trip id in payment method is");
       
        $Trip = Trip::find($tripId);
         Log::info("Trip is :::::::::::::::::");
          Log::info($Trip);
          
          $tripUser=User::find($Trip->user_id);
          Log::info("Trip user is :::::::::::::::::");
          Log::info($tripUser);

        
        
        if ($tripUser->stripe_cust_id) {

            Log::info("stripe user found");
            $tripRequest = TripRequests::where('trip_id',$tripId)->where('user_id',$tripUser->id)->first();

             Log::info("Trip request is :::::::::::::::::");
             Log::info($tripRequest);
             
            $trip = Trip::find($tripRequest->trip_id);

            $tax_percentage = Setting::get('tax_percentage', 10);

            $commission_percentage = Setting::get('commission_percentage', 10);
        
             Log::info("Fine here :::::::::::::::::");
             
            $payment = new TripPayments();
            $payment->bid_id = $tripRequest->id;
            $payment->trip_id = $tripRequest->trip_id;
            $payment->provider_id = $trip->provider_id;
            $payment->user_id =  $tripUser->id;
            $payment->fixed = $tripRequest->amount;
            
            Log::info("Fine here 1:::::::::::::::::");

            $Commision = ($tripRequest->amount * $commission_percentage / 100);

            $Tax = ($tripRequest->amount * $tax_percentage / 100);

            $ProviderPay = $tripRequest->amount - $Commision;

            $Total = $tripRequest->amount + $Tax;

            $payment->commision = $Commision;
            $payment->tax = $Tax;
            $payment->provider_pay = $ProviderPay;
            $payment->total = $Total;

            Log::info("Fine here 2:::::::::::::::::");

            $StripeCharge = $Total * 100;

            try {
                
                
                $Card = Card::where('user_id', $tripUser->id)->where('is_default', 1)->first();
                Log::info("Card Details are");
                Log::info($Card);

                Stripe::setApiKey(Setting::get('stripe_secret_key'));
                
                Log::info("Stripe Api Key is");
                Log::info(Setting::get('stripe_secret_key'));

                Log::info("Stripe Request Data is");
                Log::info("charge is::::");
                Log::info($StripeCharge);
                Log::info("customer is::::");
                Log::info($tripUser->stripe_cust_id);
                Log::info("card is::::");
                Log::info($Card->card_id);
                
                $Charge = Charge::create(array(
                            "amount" => $StripeCharge,
                            "currency" => "usd",
                            "customer" => $tripUser->stripe_cust_id,
                            "card" => $Card->card_id,
                            "description" => "Payment Charge for " . $tripUser->email,
                            "receipt_email" => $tripUser->email
                ));
                
                Log::info("Charge is::::::::::::");
                Log::info($Charge);
                $payment->payment_id = $Charge["id"];
                $payment->payment_mode = 'CARD';
                $payment->card_id = $Card->id;
                $payment->save();

                $tripRequest->status = 'Paid';
                $tripRequest->save();
                $Trip = Trip::find($tripRequest->trip_id)->first();

                $Trip->trip_status = 'PAID';

                $Trip->save();
                
               return "PAID";
                // return response()->json(['message' => trans('api.paid')]);
            } catch (StripeInvalidRequestError $e) {

                //   return response()->json(['error' => $e->getMessage()], 500);
                Log::info("Exception while payment");
                $this->paymentErrors=$e->getMessage();
                Log::info("Stripe invalid request error");
                Log::info( $this->paymentErrors);
                
                 return "Not PAID";
                 
            }
        } else {
                            return "Card NOT Found";

            // return response()->json(['error' => 'Please add credit card details first'], 500);
        }
    }
    
            //***********Trip payment method added by nabeel end************

    public function bid_user_trip(Request $request) {

        try {

            $bid = new TripRequests();
            $bid->provider_id = Auth::user()->id;
            $bid->service_type = $request->service_type;
            $bid->trip_id = $request->trip_id;
            $bid->traveller_response = $request->traveller_response;
            $bid->amount = $request->amount;
            $bid->status = 'Pending';
            $bid->created_by = 'provider';
            $bid->save();
            $trip = DB::table('trips')->where('id',$bid->trip_id)->first();
            $this->send_notification_to_provider_request($trip->user_id,"RoadStar","You Have a new Bid");
            return response()->json($bid);
        } catch (Exception $e) {
            return $e->getMessage();
          //  return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    // From Web New
    public function accept_bid_counter(Request $request) {

        try {
            TripRequests:: where('trip_id', $request->trip_id)
                    ->where('id', '!=', $request->bid_id)
                    ->update(['status' => 'Rejected']);


            TripRequests::where('id', $request->bid_id)
                    ->update(['status' => 'Approved']);
                    
            $bid_details = TripRequests::where('id', $request->bid_id)->first();
            
            Trip::where('id', $request->trip_id)->update([
                        'provider_id' => $bid_details->provider_id,
                        'trip_status' => 'SCHEDULED',
                        'service_type' => $bid_details->service_type,
                        'trip_amount' => $bid_details->counter_amount
            ]);
            
            $trip = DB::table('trips')->where('id',$request->trip_id)->first();
            
            $this->send_notification_to_provider_request($trip->user_id,"RoadStar","Your Bid Is Accepted");
            return response()->json(['message' => "Bid has been accepted successfully."], 200);
        } catch (Exception $e) {
          //  var_dump($e);
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    public function reject_bid_counter(Request $request) {

        try {

            TripRequests:: where('trip_id', $request->trip_id)
                    ->where('id', $request->bid_id)
                    ->update(['status' => 'Rejected']);
                    $bid_details = TripRequests::
                    where('id', $request->bid_id)->first();
 $trip = DB::table('trips')->where('id',$request->trip_id)->first();
$this->send_notification_to_provider_request($trip->user_id,"RoadStar","Your Bid Is Rejected");
            return response()->json(['message' => "Your Bid has been cancelled."], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    // Send Counter Offer To User Bid
    public function bid_counter_offer(Request $request) {

        try {
            TripRequests:: where('id', $request->bid_id)
                    ->update(['counter_amount' => $request->counter_amount, 'is_counter' => 1]);
$bid_details = TripRequests::
                    find($request->bid_id);
$trip = DB::table('trips')->where('id',$bid_details->trip_id)->first();
//$this->send_notification_to_provider_request($bid_details->user_id,"RoadStar","Your Bid Is Rejected");
$this->send_notification_to_provider_request($bid_details->user_id,"RoadStar","Your Have A counter Offer");
            return response()->json(['message' => "Counter Offer has been posted successfully."], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }
    public function send_notification_to_provider_request($user_id,$title,$message)
    {
//        $user_id = $request->uid;
  //      var_dump($user_id);
        //die('here');
//DB::table('provider_devices')->where('provider_id', $ttt)->first();
// var_dump($user_id);
        $user = DB::table('users')->where('id', $user_id)->first();
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
            'to' => $user->device_token, //single token
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
        //  var_dump($result);


    }


    public function send_message_notification_to_user(Request $request)
    {

        $user = DB::table('users')->where('id', $request->user_id)->first();
        $serverKey = "AAAANqPZbgE:APA91bG2mqZVYse-mtvgGgU_nja4D_q4vNa1vlFb5zFJFoscQPr8EnC34XSbDfWG2Qlr6gT0VPfIJjrSaoCeZ_RL2tKM-g9-hsVmsf6OgYySqUJzA4gXl1VFIORZ0AJ7-Kme4FLw3vVy";
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => "Roadstar",
            'body' => "You have a new message"
        ];
        

        $fcmNotification = [
 
            'to' => $user->device_token, //single token
            'notification' => $notification
 
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
        //var_dump($result);
        return response()->json(['message' => "Notification Sent"], 200);


    }


     public function support_message(Request $request) {


        try {

            $user_id = Auth::user()->id;


            $support_message = new SupportMessage();
            $support_message->provider_id = $user_id;
            $support_message->user_type = "PROVIDER";
            $support_message->subject = $request->subject;
            $support_message->message = $request->message;
            $support_message->status = "NEW";

            $support_message->save();
            return response()->json(['message' => "Message Send Successfully. We'll check and get back to you soon."], 200);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


     public function send_invoice_email(Request $request) {

        $UserRequest = UserRequests::find(927);
       Mail::to('taxiapps.test@gmail.com')->send(new TripInvoice($UserRequest));
    }


    public function send_support_message_provider(Request $request)
{
    $Provider = Auth::user();
    $uid = $Provider->id;
//$uid = Auth::user()->id;
$user = Provider::where('id',$uid)->first();
//var_dump($user);
$name = $user->first_name.' '.$user->last_name;
$email = $user->email;
$message = $request->message; 
$client = new \GuzzleHttp\Client();
$credentials = base64_encode('bleschool@gmail.com/token:ART7NkBf0GGFdoi8jbvbsudgahHnpuPDgvScKIhA');
//$credentials = base64_encode('safeeramian@gmail.com:Qwerty123!@#');
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
// echo $response;
return response()->json(['message' => "We will get back to you shortly"], 200);
}
catch (\Exception $e) {

                   Log::info("***************Exception while calculating estimated arrival time of air trip***************");
                   Log::info($e);
                   
                   return response()->json(['error' => "Something went wrong"], 500);
               }
// } catch ($e) {
//   echo $e->getRequest() . "\n";
  



}

}
