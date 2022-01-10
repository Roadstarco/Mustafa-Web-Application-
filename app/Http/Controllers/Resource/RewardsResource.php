<?php

namespace App\Http\Controllers\Resource;

use App\Reward;
use App\ServiceType;
use App\UserRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use Storage;

class RewardsResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ServiceTypes = ServiceType::all();
        $rewardsList = Reward::orderBy('created_at' , 'desc')->get();
        return view('admin.rewards.index', compact('rewardsList', 'ServiceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ServiceTypes = ServiceType::all();
        return view('admin.rewards.create', compact('ServiceTypes'));
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
            'service_type_id' => 'required|exists:service_types,id',
            'points' => 'required',
        ]);

        try{
            $reward = $request->all();

            // check if reward already exist for this service
            $alreadyExistRewards = Reward::where('service_type_id' , $reward['service_type_id'])->first();

            if ($alreadyExistRewards){
                return back()->with('flash_error', "Reward for this service type aleady exist. You can/'t add two rewards for same service ", compact('reward'));
            }

            $reward = Reward::create($reward);
            $reward->spending_amount = 1;
            $reward->save();
            return back()->with('flash_success','Reward Details Saved Successfully');
        }

        catch (Exception $e) {
            return back()->with('flash_error', 'Error while adding Reward');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reward  $reward
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $reward = Reward::findOrFail($id);
            return view('admin.rewards.details', compact('reward'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reward  $reward
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $reward = Reward::findOrFail($id);
            $ServiceTypes = ServiceType::all();
            return view('admin.rewards.edit', compact('reward', 'ServiceTypes'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reward  $reward
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'service_type_id' => 'required|exists:service_types,id',
            'points' => 'required',
        ]);

        try {

            $reward = Reward::findOrFail($id);

            $reward->service_type_id = $request->service_type_id;
            $reward->spending_amount = 1;
            $reward->points = $request->points;
            $reward->save();

            return redirect()->route('admin.rewards.index')->with('flash_success', 'Reward Updated Successfully');
        }

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Reward Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reward  $reward
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            Reward::find($id)->delete();
            return back()->with('message', 'Reward deleted successfully');
        }
        catch (Exception $e) {
            return back()->with('flash_error', 'Reward Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reward  $reward
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
