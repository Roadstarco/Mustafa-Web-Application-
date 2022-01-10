<?php

namespace App\Http\Controllers\Resource;

use App\Reward;
use App\RewardAmount;
use App\ServiceType;
use App\UserRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use Storage;

class RewardsAmountResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rewardAmounts = RewardAmount::orderBy('created_at' , 'desc')->get();
        return view('admin.reward-amount.index', compact('rewardAmounts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RewardAmount  $reward
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $rewardAmount = RewardAmount::findOrFail($id);
            return view('admin.reward-amount.edit', compact('rewardAmount'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RewardAmount  $reward
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'redeemable_points' => 'required',
            'reward_money' => 'required',
        ]);

        try {

            $reward = RewardAmount::findOrFail($id);
            $reward->redeemable_points = $request->redeemable_points;
            $reward->reward_money = $request->reward_money;
            $reward->save();

            return redirect()->route('admin.reward-amount.index')->with('flash_success', 'Reward Updated Successfully');
        }

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'RewardAmount Not Found');
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
