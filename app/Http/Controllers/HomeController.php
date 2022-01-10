<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trip;
use Response;
use Redirect;
use App\UserRequests;
use Auth;
use Setting;

class HomeController extends Controller {

    protected $UserAPI;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $UserAPI) {
        $this->middleware('auth');
        $this->UserAPI = $UserAPI;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $Response = $this->UserAPI->request_status_check()->getData();

        if (empty($Response->data)) {
            $services = $this->UserAPI->services();
            return view('user.dashboard', compact('services'));
        } else {
            return view('user.ride.waiting')->with('request', $Response->data[0]);
        }
    }

    /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile() {
        return view('user.account.profile');
    }

    /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_profile() {
        return view('user.account.edit_profile');
    }

    /**
     * Update profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request) {
        return $this->UserAPI->update_profile($request);
    }

    /**
     * Show the application change password.
     *
     * @return \Illuminate\Http\Response
     */
    public function change_password() {
        return view('user.account.change_password');
    }

    /**
     * Change Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request) {
        return $this->UserAPI->change_password($request);
    }

    /**
     * Trips.
     *
     * @return \Illuminate\Http\Response
     */
    public function trips() {
        // $trips = $this->UserAPI->trips();
        $UserRequests = UserRequests::UserTrips(Auth::user()->id)->get();
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

           $trips= $UserRequests;
        return view('user.ride.trips', compact('trips'));
    }

    /**
     * Payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment() {
        $cards = (new Resource\CardResource)->index();
        return view('user.account.payment', compact('cards'));
    }

    /**
     * Wallet.
     *
     * @return \Illuminate\Http\Response
     */
    public function wallet(Request $request) {
        $cards = (new Resource\CardResource)->index();
        return view('user.account.wallet', compact('cards'));
    }

    /**
     * Promotion.
     *
     * @return \Illuminate\Http\Response
     */
    public function promotions_index(Request $request) {
        $promocodes = $this->UserAPI->promocodes();
        return view('user.account.promotions', compact('promocodes'));
    }

    /**
     * Add promocode.
     *
     * @return \Illuminate\Http\Response
     */
    public function promotions_store(Request $request) {
        return $this->UserAPI->add_promocode($request);
    }

    /**
     * Upcoming Trips.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_trips() {
        $trips = $this->UserAPI->upcoming_trips();
        return view('user.ride.upcoming', compact('trips'));
    }

    /*
     * User International Trips
     */

    public function create_trip(Request $request) {
        return view('user.int-trips.create');
    }


    public function message_user(Request $request) {
        return view('message-user');
    }

    public function guest_chat(Request $request) {
        return view('guest-chat');
    }

    public function store_trip(Request $request) {
        $trip = json_decode($this->UserAPI->create_trip($request));

        if ($trip[0]['error']) {
            return back()->withInput()->with('error', 'Something Went Wrong. Please try again.');
        }
        return redirect('/international-trips')->with('message', 'Trip Created Successfully');
    }

    public function user_trips(Request $request) {

        $user_trips = json_decode($this->UserAPI->user_trips($request)->content());

        return view('user.int-trips.list', compact('user_trips'));
    }

    public function trip_bids(Request $request, $id) {

        $request->merge(['trip_id' => $id]);

        $trip = Trip::findorFail($id);

        $trip_bids = json_decode($this->UserAPI->trip_bids($request)->content());

        return view('user.int-trips.trip_bids', compact('trip_bids', 'trip'));
    }

    public function accept_bid(Request $request) {

        $response = json_decode($this->UserAPI->accept_bid($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        }
        return redirect('/international-trips')->with('message', $response->message);
    }

    public function rate_trip_provider(Request $request) {

        $response = json_decode($this->UserAPI->rate_trip_provider($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        }
        return back()->with('message', $response->message);
    }

    public function travels(Request $request) {

        $error=null;
        $provider_trips = json_decode($this->UserAPI->travels($request)->content());
       
        if (isset($provider_trips->error)) {

             $error=$provider_trips->error;

            // return back()->with('error', $provider_trips->error);

        }

     return view('user.int-trips.provider_trips', compact('provider_trips','error'));


    }

    public function create_bid(Request $request, $id) {

        $trip = Trip::findorFail($id);

        return view('user.int-trips.create_bid', compact('trip'));
    }

    public function store_bid(Request $request) {

        $response = json_decode($this->UserAPI->request_traveler($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        } else {
            
        }
        return redirect('/provider-trips')->withInput()->with('message', "Your Bid has been posted successfully.");
    }

    public function bid_counter_offer(Request $request) {



        $response = json_decode($this->UserAPI->bid_counter_offer($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        }
        return back()->with('message', $response->message);
    }

    public function accept_bid_counter(Request $request) {

        $request->merge(['trip_id' => $request->trip_id, 'bid_id' => $request->bid_id]);

        $response = json_decode($this->UserAPI->accept_bid_counter($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        }
        return back()->with('message', $response->message);
    }

    public function reject_bid_counter(Request $request) {

        $request->merge(['trip_id' => $request->trip_id, 'bid_id' => $request->bid_id]);

        $response = json_decode($this->UserAPI->reject_bid_counter($request)->content());

        if (isset($response->error)) {
            return back()->with('error', $response->error);
        }
        return back()->with('message', $response->message);
    }

}
