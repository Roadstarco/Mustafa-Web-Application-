<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Airport;
use Exception;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeInvalidRequestError;
use App\Card;
use App\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Setting;
use App\AirTripFlightInfo;


class AirportsResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{

            return \response()->json(Airport::whereIn('type', ['small_airport', 'medium_airport', 'large_airport'])
            ->where('name','like','%'.$request->searchTerm.'%')->where('iata_code','<>','')->get(['id', 'ident', 'name','iata_code']));
 
         } catch(Exception $e){
             return response()->json(['error' => $e->getMessage()], 500);
         }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function airportsSelect2(Request $request)
    {
        try{

            return \response()->json(Airport::whereIn('type', ['small_airport', 'medium_airport', 'large_airport'])
            ->where('name','like','%'.$request->searchTerm.'%')->get(['id', 'ident', 'name','iata_code']));
 
         } catch(Exception $e){
             return response()->json(['error' => $e->getMessage()], 500);
         }
    }

    public function trackFlight(Request $request)
    {
        try {

                $trip=Trip::find($request->trip_id);
                $flightId=$trip->flight_no;
                $flightTrackingCount = $trip->flight_tracking_count;

                $flightInfo =AirTripFlightInfo::where('trip_id',$trip->id)->first();
                $origin = $flightInfo->origin;
                $destination = $flightInfo->destination;

                $originAirport=Airport::where('ident', $origin)->first();
                $destinationAirport=Airport::where('ident', $destination)->first();

                if($flightTrackingCount>0)
                {
                    $client = new \GuzzleHttp\Client();
                    $res = $client->request('GET', 'http://IsaacImasuagbon:'.Setting::get('flightaware_api_key', '').'@flightxml.flightaware.com/json/FlightXML2/InFlightInfo?ident='.$flightId);
                    Log::info('flightaware InFlightInfo api response is');
                    Log::info($res->getBody());

                        if($res->getStatusCode()=='200')
                        { 
                            $body= $res->getBody();

                            $InFlightInfoResult = json_decode($body, true);
                            $inFlightInfoResult= $InFlightInfoResult["InFlightInfoResult"];

                                
                            $trip->flight_tracking_count=$flightTrackingCount-1;
                            $trip->save();

                            $response= array('flight_data' => $inFlightInfoResult,
                                             'origin_airport_lat' => $originAirport->latitude_deg,
                                             'origin_airport_long' => $originAirport->longitude_deg,
                                             'origin_airport_name' => $flightInfo->origin_name,
                                             'destination_airport_lat' => $destinationAirport->latitude_deg,
                                             'destination_airport_long' => $destinationAirport->longitude_deg,
                                             'destination_airport_name' => $flightInfo->destination_name);
                            return response()->json($response);

                        }else{

                            return $res->getBody();
                        }
                }else{

                    $Card = Card::where('user_id',Auth::user()->id)->where('is_default',1)->first();
                    if(!$Card)
                    {
                        return response()->json(['card_not_found' => 'Please Enter your Credit Card Details'], 200);
                    }
                    Stripe::setApiKey(Setting::get('stripe_secret_key'));
    
                    $Charge = Charge::create(array(
                        "amount" => 100,
                        "currency" => "usd",
                        "customer" => Auth::user()->stripe_cust_id,
                        "card" => $Card->card_id,
                        "description" => "Payment Charge for ".Auth::user()->email,
                        "receipt_email" => Auth::user()->email
                        ));

                        $client = new \GuzzleHttp\Client();
                        $res = $client->request('GET', 'http://IsaacImasuagbon:'.Setting::get('flightaware_api_key', '').'@flightxml.flightaware.com/json/FlightXML2/InFlightInfo?ident='.$flightId);
                        Log::info('flightaware InFlightInfo api response is');
                        Log::info($res->getBody());

                            if($res->getStatusCode()=='200')
                            { 
                                $body= $res->getBody();
                                $InFlightInfoResult = json_decode($body, true);
                                $inFlightInfoResult= $InFlightInfoResult["InFlightInfoResult"];

                                $trip->flight_tracking_count=2;
                                $trip->save();

                                $response= array('flight_data' => $inFlightInfoResult,
                                             'origin_airport_lat' => $originAirport->latitude_deg,
                                             'origin_airport_long' => $originAirport->longitude_deg,
                                             'destination_airport_lat' => $destinationAirport->latitude_deg,
                                             'destination_airport_long' => $destinationAirport->longitude_deg,);
                            return response()->json($response);
    
                            }else{
    
                                return $res->getBody();
                            }

                        }

                }catch(\Stripe\Exception\CardException $e){
                        return response()->json(['error' => $e->getError()->message], 200);
                }catch (\Stripe\Exception\InvalidRequestException $e) {
                    return response()->json(['error' => 'Sorry for the time being we are unable to track flight so please try later'], 200);
                  }catch (\Exception $e) {

                    Log::info("***************Exception while tracking flight***************");
                    Log::info( $e);

                    return response()->json(['error' => 'Sorry for the time being we are unable to track flight so please try later'], 200);

                }
        
    }
}
