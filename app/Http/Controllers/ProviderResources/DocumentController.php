<?php

namespace App\Http\Controllers\ProviderResources;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use App\Document;
use App\ProviderDocument;
use Log;

class DocumentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
        }
        $VehicleDocuments = Document::vehicle()->get();

        $DriverDocuments = Document::driver()->get();

//        dd($VehicleDocuments);
        $Provider = \Auth::guard('provider')->user();

        return view('provider.document.index', compact('DriverDocuments', 'VehicleDocuments', 'Provider'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function documents_listing(Request $request) {

        try {
            if ($request->ajax()) {
                $document = Document::orderBy('created_at', 'desc')->get();

                return $document;
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function documents_upload(Request $request) {

        try {
            if ($request->ajax()) {

                $Provider = Auth::user();

                $Provider->comp_name = $request->comp_name;
                $Provider->comp_reg_no = $request->comp_reg_no;
                $Provider->number_of_vehicle = $request->number_of_vehicle;



                $Provider->save();

                $files = $request->file('picture');

                if ($request->hasFile('picture')) {

                    //foreach ($files as $file) {
                    $length = count($files);

                    for ($x = 0; $x < $length; $x++) {

                        ProviderDocument::create([
                            'document_id' => $request->document_id[$x],
                            'document_name' => $request->document_name[$x],
                            'url' => $files[$x]->store('provider/documents'),
                            'provider_id' => $Provider->id,
                            'status' => 'ACTIVE',
                        ]);
                    }
                    return response()->json(['Success' => trans('Documents uploaded Successfully.')], 200);
                }

                return response()->json(['error' => trans('Please attach Images')], 500);
            }
        } catch (Exception $e) {
            echo $e;
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
                // 'document' => 'mimes:jpg,jpeg,png,pdf',
        ]);

        try {

            $Document = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)
                    ->where('document_id', $id)
                    ->firstOrFail();

            $Document->update([
                'url' => $request->document->store('provider/documents'),
                'status' => 'ASSESSING',
            ]);

            return back();
        } catch (ModelNotFoundException $e) {

            ProviderDocument::create([
                'url' => $request->document->store('provider/documents'),
                'provider_id' => \Auth::guard('provider')->user()->id,
                'document_id' => $id,
                'status' => 'ASSESSING',
            ]);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function upload_documents() {
        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
        }
        $DriverDocuments = Document::driver()->get();
        $Document = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)
                ->get();
        if (count($Document) >= count($DriverDocuments)) {
            return redirect('provider/profile');
        }
        $Provider = \Auth::guard('provider')->user();
        
        return view('provider.document.upload', compact('DriverDocuments', 'Provider'));
    }

    public function save_documents(Request $request) {

        $Provider = Auth::user();

        $Provider->comp_name = $request->comp_name;
        $Provider->comp_reg_no = $request->comp_reg_no;
        $Provider->number_of_vehicle = $request->number_of_vehicle;



        $Provider->save();
        $files = $request->file('document');
     
       
        
        if ($request->hasFile('document')) {
          
            foreach ($files as $key => $file) {

                ProviderDocument::create([
                    'document_id' => $key,
                    'url' => $file->store('provider/documents'),
                    'provider_id' => $Provider->id,
                    'status' => 'ACTIVE',
                ]);
            }
        }
        return redirect('provider/profile')->with('success', 'Your information is saved successfully.');
    }

     public function documents_listing_with_name(Request $request) {

            Log::info("documents_listing_with_name");
            Log::info(ProviderDocument::where('provider_id',Auth::user()->id)->get());
         return response()->json( ProviderDocument::where('provider_id',Auth::user()->id)->get());

     }

}
