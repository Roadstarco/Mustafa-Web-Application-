<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
class VesselFinderApiTestController extends Controller
{
    public function portCall(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        // $res = $client->request('GET', 'https://api.vesselfinder.com/portcalls?userkey=AABBCCDD&interval=14400&imo=9175717');
        $res = $client->request('GET', 'https://services.marinetraffic.com/en/api/etatoport/58894cf8a593af444d8109bbf5dd0bc7c6d1c7df/protocol:json/portid:288/shipid:547677/msgtype:simple');
        if($res->getStatusCode()=='200')
        { 
            $body= $res->getBody();
            $obj = json_decode($body);

           return $obj[0][0];

                   
        }
    }


    public function flightInfo(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        // $res = $client->request('GET', 'https://api.vesselfinder.com/portcalls?userkey=AABBCCDD&interval=14400&imo=9175717');
        $res = $client->request('GET', 'http://IsaacImasuagbon:7f4a458f262566736e9217bf7ea3636a92d17821@flightxml.flightaware.com/json/FlightXML2/Scheduled?airport=EGLL');
        if($res->getStatusCode()=='200')
        { 
           
            $body= $res->getBody();
            $obj = json_decode($body, true);

            $scheduled= $obj['ScheduledResult']['scheduled']; 
             $collection = collect($scheduled);

            $filtered = $collection->where('ident', 'DLH901')->first();

            return $filtered;
    
        }
    }

}
