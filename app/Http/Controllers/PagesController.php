<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceType;

class PagesController extends Controller
{
    public function ride(Request $request)
    {
        $services=ServiceType::all();
        return view('ride',compact('services'));
    }
}
