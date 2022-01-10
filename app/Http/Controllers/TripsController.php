<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trip;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Provider;
use App\Helpers\Helper;


class TripsController extends Controller {

    public function index() {

       $trips = DB::table('trips')
                ->join('providers', 'trips.provider_id', '=', 'providers.id')
                ->select('trips.*', 'providers.first_name', 'providers.last_name', 'providers.email', 'providers.avatar', 'providers.mobile')
                ->where(['trips.status' => 1,'providers.status'=>'approved'])
                ->where('arrival_date', '>=', date('Y-m-d'))
                ->get();
        return view('trips.travels', compact('trips'));
    }

    public function detail($trip_id) {
        $trip = DB::table('trips')
                ->join('providers', 'trips.provider_id', '=', 'providers.id')
                ->select('trips.*', 'providers.first_name', 'providers.last_name', 'providers.email', 'providers.avatar','providers.rating' ,'providers.mobile')
                ->where(['trips.status' => 1,'providers.status'=>'approved', 'trips.id' => $trip_id])
                ->where('arrival_date', '>=', date('Y-m-d'))
                ->first();
        $total_trips['total_trips'] = DB::table('trips')->where('provider_id',$trip->provider_id)->get();

      //  $trip= (array) $trips;
       
        $trip = (object) array_merge((array)$trip, (array)$total_trips);
       //$trip['total_trips'] = $total_trips;

       // $merged = $trip->merge($total_trips);
        //$rating = DB::table('providers')->where('id',$trip->provider_id);
        //$trip->total_trips = $total_trips;
        //$trip->provider_rating = $rating->rating;

        return view('trips.travel-detail', compact('trip'));
    }

    public function trip_form() {
        return view('trips.travel-form');
    }

    public function search_trips(Request $request) {
//      DB::enableQueryLog(); 
        $trips = DB::table('trips')
                       ->join('providers', 'trips.provider_id', '=', 'providers.id')
                ->select('trips.*', 'providers.first_name', 'providers.last_name', 'providers.email', 'providers.avatar', 'providers.mobile')
               ->where('arrival_date', '>=', date('Y-m-d'))
                        ->where([
                            'tripfrom' => $request->tripfrom,
                            'tripto' => $request->tripto,
                            'trips.status' => 1,
                            'providers.status'=>'approved'
                        ])->get();
//        dd(DB::getQueryLog());

        if ($request->ajax()) {
            return response()->json(['trips' => $trips]);
        } else {
            return view('trips.travels', compact('trips'));
            return back()->with(['trips' => $trips]);
        }

    }

    public function save(Request $request) {

        try {
            $provider = Provider::where('email', $request->email)->first();

            if ($provider) {

                $trip = $request->all();
                $trip['provider_id'] = $provider->id;
                $trip['booking_id'] = Helper::generate_booking_id(); 
             
                Trip::create($trip);
                if ($request->return_date !== '' && (strtotime($request->return_date) >= strtotime($request->arrival_date)) && $request->recurrence=='never') {
                    $trip['arrival_date'] = $request->return_date;
                    $trip['tripfrom'] = $request->tripto;
                    $trip['tripfrom_lat'] = $request->tripto_lat;
                    $trip['tripfrom_lng'] = $request->tripto_lng;
                    $trip['tripto'] = $request->tripfrom;
                    $trip['tripto_lat'] = $request->tripfrom_lat;
                    $trip['tripto_lng'] = $request->tripfrom_lng;
                    
                    Trip::create($trip);
                }
                return back()->with('success_msg', 'Trip Details Saved Successfully');
            } else {
                return back()->withInput()->with('error_msg', 'Provider Email Not Exists.');
            }
        } catch (Exception $e) {
            return back()->with('error_msg', 'Something went wrong.');
        }
    }
    
   

}
