<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class RideController extends Controller
{
    protected $UserAPI;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $UserAPI)
    {
        $this->middleware('auth');
        $this->UserAPI = $UserAPI;
    }


    /**
     * Ride Confirmation.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm_ride(Request $request)
    {

        $attachment1=$attachment2=$attachment3='';

        $fare = $this->UserAPI->estimated_fare($request)->getData();
        $service = (new Resource\ServiceResource)->show($request->service_type);
        $cards = (new Resource\CardResource)->index();

        if($request->has('current_longitude') && $request->has('current_latitude'))
        {
            User::where('id',Auth::user()->id)->update([
                'latitude' => $request->current_latitude,
                'longitude' => $request->current_longitude
            ]);
        }

    
        return view('user.ride.confirm_ride',compact('request','fare','service','cards'));
    }

    /**
     * Create Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_ride(Request $request)
    {
        // dd($request->all());
        //$request->from_web = 1;
        $request->merge(['from_web' => 1]);
        //$request->service_type= 14;
        
        return $this->UserAPI->send_request1($request);
    }


    /**
     * ReCreate Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function recreate_ride(Request $request)
    {
        // dd($request->all());
        //$request->from_web = 1;
        $request->merge(['from_web' => 1]);
        $request->service_type= 14;
        
        return $this->UserAPI->re_send_request1($request);
    }

    /**
     * Get Request Status Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function status()
    {
        return $this->UserAPI->request_status_check();
    }

    /**
     * Cancel Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel_ride(Request $request)
    {
        //die($request);
        return $this->UserAPI->cancel_request($request);
    }

    /**
     * Rate Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function rate(Request $request)
    {
        return $this->UserAPI->rate_provider($request);
    }
}
