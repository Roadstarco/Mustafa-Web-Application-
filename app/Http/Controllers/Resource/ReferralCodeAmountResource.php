<?php

namespace App\Http\Controllers\Resource;


use App\ReferralCodeAmount;
use App\UserRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use Storage;

class ReferralCodeAmountResource extends Controller
{
    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $referral_code_amount = ReferralCodeAmount::findOrFail($id);
            return view('admin.referral-code.show', compact('referral_code_amount'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $referral_code_amount = ReferralCodeAmount::findOrFail($id);
            return view('admin.referral-code.edit',compact('referral_code_amount'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReferralCodeAmount  $referralCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'referral_code_amount_to_user' => 'required',
            'referral_code_amount_by_user' => 'required',
        ]);

        try {

            $referralCode = ReferralCodeAmount::findOrFail($id);

            $referralCode->referral_code_amount_to_user = $request->referral_code_amount_to_user;
            $referralCode->referral_code_amount_by_user = $request->referral_code_amount_by_user;
            $referralCode->save();

            return redirect()->route('admin.referral-code.show', 1)->with('flash_success', 'Referral Amount Updated Successfully');
        }

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Place Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReferralCodeAmount  $referralCode
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
