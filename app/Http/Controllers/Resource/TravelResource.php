<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trip;
use App\TripRequests;
use App\Provider;
use Auth;
use Setting;
use Illuminate\Support\Facades\DB;
class TravelResource extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('demo', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($service)
    {
       if($service=="air")
       {
           $service='By Air';   
       }

      else if($service=="sea")
       {
             $service='By Sea';
   
       }

       else
       {
           $service='By Road';
       }



        try {
                    $trips = DB::table('trips')
                        ->join('providers', 'trips.provider_id', '=', 'providers.id')
                        ->select('trips.*', 'providers.first_name', 'providers.last_name', 'providers.email', 'providers.avatar', 'providers.mobile')
                        ->where(['service_type' => $service])
                        ->get();
                        // return $trips;
                    return view('admin.trips.index',compact('trips'));
                } catch (Exception $e) {
                    return back()->with('flash_error','Something Went Wrong!');
                }

      
    }



    public function show($id)
    {
        try {
            $trip = Trip::findOrFail($id);
            return view('admin.trips.show', compact('trip'));
        } catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }
    


    public function destroy($id)
    {
        try {
            $Request = Trip::findOrFail($id);
            $Request->delete();
            TripRequests::where('trip_id',$id)->first()->delete();
            return back()->with('flash_success','Request Deleted!');
        } catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }
    }
}
