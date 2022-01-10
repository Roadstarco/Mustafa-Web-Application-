<?php

namespace App\Http\Controllers\Resource;

use App\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;

class SupportMessageResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supportMessages = SupportMessage::orderBy('created_at' , 'desc')->get();
        return view('admin.support-message.index', compact('supportMessages'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SupportMessage $supportMessage
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $supportMessage = SupportMessage::findOrFail($id);
            return view('admin.support-message.show', compact('supportMessage'));
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SupportMessage  $supportMessage
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $supportMessage = SupportMessage::findOrFail($id);
            return view('admin.support-message.edit',compact('supportMessage'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SupportMessage  $supportMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $supportMessage = SupportMessage::findOrFail($id);

            $supportMessage->status = $request->status;
            $supportMessage->save();

            return redirect()->route('admin.support-message.index')->with('flash_success', 'Support Message Updated Successfully');
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'SupportMessage Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SupportMessage  $supportMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            SupportMessage::find($id)->delete();
            return back()->with('message', 'SupportMessage deleted successfully');
        } 
        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'SupportMessage Not Found');
        }
    }
}
