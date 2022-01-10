<?php

namespace App\Http\Controllers\Resource;


use App\Zones;
use App\UserRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use Storage;

class ZonesResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zones = Zones::orderBy('created_at' , 'desc')->get();
        return view('admin.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.zones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'city' => 'required|max:255',
            'complete_address' => 'required|max:255',
            'state' => 'required|max:255',
            'country' => 'required|max:255',
            'currency' => 'required|max:255',
            'status' => 'required|max:255',
            'zone_area' => 'required',
        ]);

        try{

            $zone = $request->all();


            $zone = Zones::create($zone);

            return redirect()->route('admin.zones.index')->with('flash_success', 'Zone Saved Successfully');
        }

        catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Zones  $zone
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $zone = Zones::findOrFail($id);
            return view('admin.zones.zone-detail', compact('zone'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Zones  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $zone = Zones::findOrFail($id);
            return view('admin.zones.edit',compact('zone'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Zones  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'city' => 'required|max:255',
            'state' => 'required|max:255',
            'country' => 'required|max:255',
            'currency' => 'required|max:255',
            'status' => 'required|max:255',
            'zone_area' => 'required',
        ]);

        try {

            $zone = Zones::findOrFail($id);

            $zone->name = $request->name;
            $zone->city = $request->city;
            $zone->state = $request->state;
            $zone->country = $request->country;
            $zone->currency = $request->currency;
            $zone->status = $request->status;
            $zone->zone_area = $request->zone_area;
            $zone->save();

            return redirect()->route('admin.zones.index')->with('flash_success', 'Zone Updated Successfully');
        }

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Zones Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Zones  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            Zones::find($id)->delete();
            return redirect()->route('admin.zones.index')->with('flash_success', 'Zone deleted Successfully');
        }
        catch (Exception $e) {
            return back()->with('flash_error', 'Zones Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Zones  $zone
     * @return \Illuminate\Http\Response
     */
    public function request($id){

        try{

            $requests = UserRequests::where('user_requests.user_id',$id)
                ->RequestHistory()
                ->get();

            return view('admin.request.index', compact('requests'));
        }

        catch (Exception $e) {
            return back()->with('flash_error','Something Went Wrong!');
        }

    }
}
