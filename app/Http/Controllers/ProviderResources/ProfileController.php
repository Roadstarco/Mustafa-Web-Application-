<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Auth;
use Setting;
use Storage;
use Exception;
use Carbon\Carbon;
use App\ProviderProfile;
use App\UserRequests;
use App\ProviderService;
use App\Fleet;
use App\RequestFilter;
use App\Document;
use App\ProviderDocument;
use DB;
use App\Http\Controllers\ProviderResources\TripController;
use App\Trip;
use App\TripRequests;
use App\SeaTripEstimatedArrival;
use Log;

class ProfileController extends Controller {

    protected $ProviderTripAPI;

    /**
     * Create a new user instance.
     *
     * @return void
     */
     
    // public function __construct() {

    // }



    public function __construct(TripController $ProviderTripAPI) {

        $this->middleware('provider.api', ['except' => ['show', 'store', 'available', 'location_edit', 'location_update', 'int_trips', 'local_trips', 'add_bid',
                'store_bid', 'update_trip_status',
                'trip_bids',
                'bid_counter_offer',
                'accept_bid_counter',
                'reject_bid_counter',
                'rate_trip_user',
                'accept_bid',
                'international_trips_request_detail',
                'international_trip_service_type_see_start',
                'international_trip_service_type_air_start',
                'message_provider',
                'chat_provider',
        ]]);

        $this->ProviderTripAPI = $ProviderTripAPI;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {

            //die('here');
            Auth::user()->service = ProviderService::where('provider_id', Auth::user()->id)
                    ->with('service_type')
                    ->first();
            Auth::user()->fleet = Fleet::find(Auth::user()->fleet);
            Auth::user()->currency = Setting::get('currency', '$');
            Auth::user()->sos = Setting::get('sos_number', '911');
            Auth::user()->documents_uploaded = $this->checkDocument();
            return Auth::user();
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
public function update_fcm(Request $request){
        try {

            $user = DB::table('provider_devices')->where('provider_id', Auth::user()->id)->update(['token' => $request->fcm]);
            return response()->json(['message' => 'Token Updated successfully!']);
        } catch (Exception $e) {

            return $e->getMessage();
        }
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'mobile' => 'required',
            // 'avatar' => 'mimes:jpeg,bmp,png',
            'language' => 'max:255',
            'address' => 'max:255',
            'address_secondary' => 'max:255',
            'city' => 'max:255',
            'country' => 'max:255',
            'postal_code' => 'max:255',
        ]);

        try {

            $Provider = Auth::user();

            if ($request->has('first_name'))
                $Provider->first_name = $request->first_name;

            if ($request->has('last_name'))
                $Provider->last_name = $request->last_name;

            if ($request->has('mobile'))
                $Provider->mobile = $request->mobile;

            if ($request->hasFile('avatar')) {
                Storage::delete($Provider->avatar);
                $Provider->avatar = $request->avatar->store('provider/profile');
            }

            if ($request->has('service_type')) {
                if ($Provider->service) {
                    if ($Provider->service->service_type_id != $request->service_type) {
                        $Provider->status = 'banned';
                    }
                    //$ProviderService = ProviderService::where('provider_id',Auth::user()->id);
                    $Provider->service->service_type_id = $request->service_type;
                    $Provider->service->service_number = $request->service_number;
                    $Provider->service->service_model = $request->service_model;
                    $Provider->service->save();
                } else {
                    ProviderService::create([
                        'provider_id' => $Provider->id,
                        'service_type_id' => $request->service_type,
                        'service_number' => $request->service_number,
                        'service_model' => $request->service_model,
                    ]);
                    $Provider->status = 'banned';
                }
            }

            if ($Provider->profile) {
                $Provider->profile->update([
                    'language' => $request->language ?: $Provider->profile->language,
                    'address' => $request->address ?: $Provider->profile->address,
                    'address_secondary' => $request->address_secondary ?: $Provider->profile->address_secondary,
                    'city' => $request->city ?: $Provider->profile->city,
                    'country' => $request->country ?: $Provider->profile->country,
                    'postal_code' => $request->postal_code ?: $Provider->profile->postal_code,
                ]);
            } else {
                ProviderProfile::create([
                    'provider_id' => $Provider->id,
                    'language' => $request->language,
                    'address' => $request->address,
                    'address_secondary' => $request->address_secondary,
                    'city' => $request->city,
                    'country' => $request->country,
                    'postal_code' => $request->postal_code,
                ]);
            }

            $Provider->save();

            return redirect(route('provider.profile.index'));
        } catch (ModelNotFoundException $e) {

            return response()->json(['error' => 'Provider Not Found!'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {

        if ($this->checkDocument()) {
            return view('provider.profile.index');
        } else {

            return redirect('/provider/upload_documents');
        }
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'mobile' => 'required',
            // 'avatar' => 'mimes:jpeg,bmp,png',
            'language' => 'max:255',
            'address' => 'max:255',
            'address_secondary' => 'max:255',
            'city' => 'max:255',
            'country' => 'max:255',
            'postal_code' => 'max:255',
        ]);

        try {

            $Provider = Auth::user();

            if ($request->has('first_name'))
                $Provider->first_name = $request->first_name;

            if ($request->has('last_name'))
                $Provider->last_name = $request->last_name;

            if ($request->has('mobile'))
                $Provider->mobile = $request->mobile;

            if ($request->hasFile('avatar')) {
                Storage::delete($Provider->avatar);
                // $Provider->avatar = $request->avatar->store('provider/profile');
                $Provider->avatar = url('storage/app/public/' . $request->avatar->store('provider/profile'));
            }

            if ($Provider->profile) {
                $Provider->profile->update([
                    'language' => $request->language ?: $Provider->profile->language,
                    'address' => $request->address ?: $Provider->profile->address,
                    'address_secondary' => $request->address_secondary ?: $Provider->profile->address_secondary,
                    'city' => $request->city ?: $Provider->profile->city,
                    'country' => $request->country ?: $Provider->profile->country,
                    'postal_code' => $request->postal_code ?: $Provider->profile->postal_code,
                ]);
            } else {
                ProviderProfile::create([
                    'provider_id' => $Provider->id,
                    'language' => $request->language,
                    'address' => $request->address,
                    'address_secondary' => $request->address_secondary,
                    'city' => $request->city,
                    'country' => $request->country,
                    'postal_code' => $request->postal_code,
                ]);
            }


            $Provider->save();

            return $Provider;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Provider Not Found!'], 404);
        }
    }

    /**
     * Update latitude and longitude of the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function location(Request $request) {
        $this->validate($request, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($Provider = Auth::user()) {

            $Provider->latitude = $request->latitude;
            $Provider->longitude = $request->longitude;
            $Provider->save();

            return response()->json(['message' => 'Location Updated successfully!']);
        } else {
            return response()->json(['error' => 'Provider Not Found!']);
        }
    }

    /**
     * Toggle service availability of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request) {
                // dd("fine here");

        if (!$this->checkDocument()) {
            return redirect('/provider/upload_documents');
        }
        $this->validate($request, [
            'service_status' => 'required|in:active,offline',
        ]);

        $Provider = Auth::user();

        if ($Provider->service) {

            $provider = $Provider->id;
            $OfflineOpenRequest = RequestFilter::with(['request.provider', 'request'])
                            ->where('provider_id', $provider)
                            ->whereHas('request', function($query) use ($provider) {
                                $query->where('status', 'SEARCHING');
                                $query->where('current_provider_id', '<>', $provider);
                                $query->orWhereNull('current_provider_id');
                            })->pluck('id');

            if (count($OfflineOpenRequest) > 0) {
                RequestFilter::whereIn('id', $OfflineOpenRequest)->delete();
            }

            $Provider->service->update(['status' => $request->service_status]);
        } else {
            return response()->json(['error' => 'You account has not been approved for driving']);
        }

        return $Provider;
    }

    /**
     * Update password of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request) {
        $this->validate($request, [
            'password' => 'required|confirmed',
            'password_old' => 'required',
        ]);

        $Provider = Auth::user();

        if (password_verify($request->password_old, $Provider->password)) {
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            return response()->json(['message' => 'Password changed successfully!']);
        } else {
            return response()->json(['error' => 'Required is new password should not be same as old password'], 422);
        }
    }

    /**
     * Show providers daily target.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function target(Request $request) {
        try {

            $Rides = UserRequests::where('provider_id', Auth::user()->id)
                    ->where('status', 'COMPLETED')
                    ->where('created_at', '>=', Carbon::today())
                    ->with('payment', 'service_type')
                    ->get();

            return response()->json([
                        'rides' => $Rides,
                        'rides_count' => $Rides->count(),
                        'target' => Setting::get('daily_target', '0')
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }

    private function checkDocument() {
        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
        }
        $Document = ProviderDocument::where('provider_id', Auth::user()->id)
                ->get();

        $DriverDocuments = Document::driver()->get();

        if (count($Document) < count($DriverDocuments)) {
            return false;
        } else {
            return true;
        }
    }

public function message_provider() {
        return view('message-provider');
    }

    public function chat_provider() {
        return view('chat-provider');
    }
    public function int_trips(Request $request) {

        $int_trips = DB::table('trips')
                ->join('users', 'trips.user_id', '=', 'users.id')
                ->select('trips.*', 'users.first_name', 'users.last_name', 'users.email', 'users.picture')
                ->where(["trip_status" => "PENDING", "created_by" => "user"])
                ->where('arrival_date', '>=', date('Y-m-d'))
                ->orderBy('id', 'DESC')
                ->get();
        $provider_id = Auth::user()->id;
        $my_trips = Trip::where([
                    "provider_id" => Auth::user()->id
                ])->get();

        foreach ($my_trips as $mttrip) {
          $mttrip->bids = TripRequests::
                    where(['trip_id' => $mttrip->id,'status'=>'Pending'])->get()->count();
        }

        foreach ($int_trips as $trip) {
            $bid = TripRequests::
                    where(['trip_id' => $trip->id, 'provider_id' => Auth::user()->id])->get()->first();

            $trip->bid_details = $bid ? $bid : (object) [];
        }
        return view('provider.int-trips.user_trips', compact('int_trips', 'provider_id', 'my_trips'));
    }

    public function trip_bids(Request $request, $id) {

        $request->merge(['trip_id' => $id]);

        $trip = Trip::findorFail($id);

         $trip_bids = json_decode($this->ProviderTripAPI->trip_bids($request)->content());

        //   dd($trip_bids);

        return view('provider.int-trips.trip_bids', compact('trip_bids', 'trip'));
    }

    public function add_bid(Request $request, $id) {
        $request->merge(['trip_id' => $id]);

        $trip = Trip::findorFail($id);
        return view('provider.int-trips.create_bid', compact('trip'));
    }

    public function store_bid(Request $request) {
        $trip = json_decode($this->ProviderTripAPI->bid_user_trip($request));

        if ($trip[0]['error']) {
            return back()->withInput()->with('error', 'Something Went Wrong. Please try again.');
        }
        return redirect('provider/international-trips')->with('message', 'Bid Created Successfully');
    }

    public function update_trip_status(Request $request, $status) {


        $request->merge(['trip_status' => $status]);


        $trip_status = json_decode($this->ProviderTripAPI->update_trip($request));

        if (!isset($trip_status->id)) {
            return back()->withInput()->with('error', $trip_status[0]['error']);
        }
        return back()->withInput()->with('message', ucfirst(strtolower($status)) . ' Successfully');
    }

    public function accept_bid(Request $request) {


        $request->status = 'Approved';

        $response = json_decode($this->ProviderTripAPI->update_bid($request)->content());


        if (isset($response->error)) {

            return back()->with('error', $response->error);

        }

        // print_r($response);
        // die();
        // return redirect('/international-trips')->with('message', $response->message);
       return redirect('provider/international-trips')->with('message', 'Trip Accepted');

    }

    public function bid_counter_offer(Request $request) {



        $response = json_decode($this->ProviderTripAPI->bid_counter_offer($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        }
        return back()->with('message', $response->message);
    }

    public function accept_bid_counter(Request $request) {

        $request->merge(['trip_id' => $request->trip_id, 'bid_id' => $request->bid_id]);

        $response = json_decode($this->ProviderTripAPI->accept_bid_counter($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        }
        return back()->with('message', $response->message);
    }

    public function reject_bid_counter(Request $request) {

        $request->merge(['trip_id' => $request->trip_id, 'bid_id' => $request->bid_id]);

        $response = json_decode($this->ProviderTripAPI->reject_bid_counter($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        }
        return back()->with('message', $response->message);
    }

    public function rate_trip_user(Request $request) {

        $response = json_decode($this->ProviderTripAPI->rate_trip_user($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        }
        return back()->with('message', $response->message);
    }


    public function local_trips(Request $request) {


    return view('provider.local-trips.user_trips');

    }

public function international_trips_request_detail($id) {

    return response()->json(TripRequests::find($id));

    }

    public function international_trip_service_type_see_start(Request $request)
    {

        $request->merge(['trip_status' => 'STARTED']);

        $vid=$request->vessel_id;

        $vessel = DB::table('vessels')->where('marine_traffic_id',$vid)->first();
        // return $request->all();

        $vesselId=$vessel->marine_traffic_id;
        $vesselImo=$vessel->imo;
        $vesselName=$vessel->name;

        $request->merge(['vessel_id' => $vesselId]);
        $request->merge(['vessel_imo' => $vesselImo]);
        $request->merge(['vessel_name' => $vesselName]);

        $trip_status = json_decode($this->ProviderTripAPI->update_trip($request));

        if(!isset($trip_status))
        {
            return back()->withInput()->with('error', 'Sorry we are unable to find sea trip info');

        }

            // dd($trip_status);
        if (!isset($trip_status->id)) {
            return back()->withInput()->with('error', $trip_status[0]['error']);
        }
        return back()->withInput()->with('message', ucfirst(strtolower('started')) . ' Successfully');
    }

    public function international_trip_service_type_air_start(Request $request)
    {

        $request->merge(['trip_status' => 'STARTED']);


        $trip_status = json_decode($this->ProviderTripAPI->update_trip($request));


        Log::info("trip status in profile controller is");
        // Log::info($trip_status);
//var_dump('i am herer');
//var_dump($request);

var_dump($trip_status);
$flightNo=$request->flight_no;
                       $airport=$request->airport;
                       $iden = $request->ident;
                    //    var_dump('flight NO');
                    //    var_dump($flightNo);
                    //    var_dump('Airport');
                    //    var_dump($airport);
$airport = strtoupper($airport);
$airports =DB::table('airports')->where('iata_code',$airport)->first();
$iden = $airports->ident;
$request->merge(['ident' => $airports->ident]);
//                        var_dump('Iden');
//                        var_dump($iden);
//                        var_dump($this->ProviderTripAPI->update_trip($request));
// die($trip_status);
        if(!isset($trip_status))
        {
            return back()->withInput()->with('error', 'Sorry we are unable to find flight info');

        }

        if (!isset($trip_status->id)) {
            return back()->withInput()->with('error', $trip_status[0]['error']);
        }
        return back()->withInput()->with('message', ucfirst(strtolower('started')) . ' Successfully');
    }

    /**
    * Function to show messages
    */ 
    public function showMessages()
    {
        die('here');
        return view('provider/profile/messages');
    }
    
    

}
