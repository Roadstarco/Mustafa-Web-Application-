<?php

namespace App\Http\Controllers\Resource;

use App\Fleet;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;

class FleetResource extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('demo', ['only' => [ 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fleets = Fleet::orderBy('created_at' , 'desc')->get();
        return view('admin.fleet.index', compact('fleets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.fleet.create');
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
            'company' => 'required|max:255',
            'email' => 'required|unique:fleets,email|email|max:255',
            'mobile' => 'digits_between:6,13',
            // 'logo' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'password' => 'required|min:6|confirmed',
        ]);

        try{

            $fleet = $request->all();
            $fleet['password'] = bcrypt($request->password);
            if($request->hasFile('logo')) {
                $fleet['logo'] = $request->logo->store('fleet');
            }

            $fleet = Fleet::create($fleet);

            return back()->with('flash_success','Company Owner Details Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_error', 'Company Owner Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $fleet = Fleet::findOrFail($id);
            return view('admin.fleet.edit',compact('fleet'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            'name' => 'required|max:255',
            'company' => 'required|max:255',
            'mobile' => 'digits_between:6,13',
            // 'logo' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try {

            $fleet = Fleet::findOrFail($id);

            if($request->hasFile('logo')) {
                \Storage::delete($fleet->logo);
                $fleet->logo = $request->logo->store('fleet');
            }

            $fleet->name = $request->name;
            $fleet->company = $request->company;
            $fleet->mobile = $request->mobile;
            $fleet->save();

            return redirect()->route('admin.fleet.index')->with('flash_success', 'Company Owner Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Company Owner Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fleet  $Fleet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {
            Fleet::find($id)->delete();
            return back()->with('message', 'Company Owner  deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Company Owner  Not Found');
        }
    }
public function approve($id)
    {
        try {
            $Fleet = Fleet::findOrFail($id);
            // if($Fleet->service) {
                if(1) {

                $Fleet->update(['status' => 'approved']);
                return back()->with('flash_success', "Company Owner Approved");
            } else {
                return redirect()->route('admin.fleet.index', $id)->with('flash_error', "Company Owner has not been assigned a service type!");
            }
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', "Something went wrong! Please try again later.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function disapprove($id)
    {

        Fleet::where('id',$id)->update(['status' => 'banned']);
        return back()->with('flash_success', "Company Owner Disapproved");
    }

}
