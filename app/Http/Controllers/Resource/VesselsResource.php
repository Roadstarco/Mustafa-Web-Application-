<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Setting;
use Log;
use App\Vessel;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeInvalidRequestError;
use App\Card;
use App\Port;
use App\Trip;
use App\Provider;
use App\User;
use Illuminate\Support\Facades\Auth;



class VesselsResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{

         return \response()->json(Vessel::where('name','like','%'.$request->searchTerm.'%')->get(['marine_traffic_id', 'name', 'imo']));
           //return response()->json(['vesselsList' => Vessel::all()]); 

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
 public function store(Request $request) {
    
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
    public function destroy(Request $request)
    {

    }


//    Route::get('/send-message-notification-user','Resource\VesselsResource@send_message_notification_user');
    public function send_message_notification_user(Request $request)
    {
                $user = DB::table('users')->where('id', $request->user_id)->first();
        $serverKey = "AAAANqPZbgE:APA91bG2mqZVYse-mtvgGgU_nja4D_q4vNa1vlFb5zFJFoscQPr8EnC34XSbDfWG2Qlr6gT0VPfIJjrSaoCeZ_RL2tKM-g9-hsVmsf6OgYySqUJzA4gXl1VFIORZ0AJ7-Kme4FLw3vVy";
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => "Roadstar",
            'body' => "You have a new message"
        ];
        

        $fcmNotification = [
 
            'to' => $user->device_token, //single token
            'notification' => $notification
 
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        //var_dump($result);
        return response()->json(['message' => "Notification Sent"], 200);


    }

    public function send_message_notification_provider(Request $request)
    {
        $user = DB::table('provider_devices')->where('provider_id', $request->user_id)->first();
        $serverKey = "AAAANqPZbgE:APA91bG2mqZVYse-mtvgGgU_nja4D_q4vNa1vlFb5zFJFoscQPr8EnC34XSbDfWG2Qlr6gT0VPfIJjrSaoCeZ_RL2tKM-g9-hsVmsf6OgYySqUJzA4gXl1VFIORZ0AJ7-Kme4FLw3vVy";
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => "RoadStar",
            'body' => "You have a new message"
        ];
        

        $fcmNotification = [
            //'registration_ids' => $tokens, //multple token array
            'to' => $user->token, //single token
            'notification' => $notification
            //'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        // var_dump($result);

        return response()->json(['message' => "Notification Sent"], 200);


    }




    //***********Stor Trip vessel info method added by nabeel start************
public function trackVessel1(Request $request)
    {
        $trip = Trip::where('id',$request->trip_id)->first();
    //var_dump($trip);
//    $last = DB::table('last_location')->('trip_id',$request->trip_id)->first();
//     DB::table('users')->insert([
//     'email' => 'kayla@example.com',
//     'votes' => 0
// ]);
	$service_type = $trip->service_type;
	$package_longitude = 0.0;
	$package_latitude = 0.0;
	if($trip->service_type == 'By Road')
	{
		$provider = Provider::find($trip->provider_id);
		$package_latitude = $provider->latitude;
		$package_longitude = $provider->longitude;
		$response= array('service_type' =>  'By Road','current_longitude' => $package_longitude,'current_latitude'=> $package_latitude,'timeout' => "ok",
                                             'arrivalTime' => 0,
                                             'departureTime' => 0);
		return response()->json($response);
	}
	else if($trip->service_type == 'By Air')
	{
        //var_dump($trip->flight_no);
		$client = new \GuzzleHttp\Client();
		$res = $client->request('GET', 'http://IsaacImasuagbon:'.Setting::get('flightaware_api_key', '').'@flightxml.flightaware.com/json/FlightXML2/InFlightInfo?ident='.$trip->flight_no);
        
		if($res->getStatusCode()=='200')
                        { 
                            $body= $res->getBody();

                            $InFlightInfoResult = json_decode($body, true);
                            $inFlightInfoResult= $InFlightInfoResult["InFlightInfoResult"];
                            //var_dump($inFlightInfoResult);
                            // if($last ==null)
                            // {
                            //     $last = 
                            // }
                            $response= array('service_type' => $trip->service_type,
                                             'current_longitude' => $InFlightInfoResult["InFlightInfoResult"]["longitude"],
                                             'current_latitude' => $InFlightInfoResult["InFlightInfoResult"]["latitude"],
                                             'timeout' => $InFlightInfoResult["InFlightInfoResult"]["timeout"],
                                             'arrivalTime' => $InFlightInfoResult["InFlightInfoResult"]["arrivalTime"],
                                             'departureTime' => $InFlightInfoResult["InFlightInfoResult"]["departureTime"]);
                            return response()->json($response);

                        }
	}
	else if($trip->service_type == 'By Sea')
	{
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET','https://services.marinetraffic.com/api/exportvessel/3b5750a049049272ac57167877c5585a38f70c37?v=5&shipid='.$trip->vessel_id.'&timespan=2&msgtype=simple&protocol=json');
        if($res->getStatusCode()=='200')
                        { 
                            $body= $res->getBody();
                            $InFlightInfoResult = json_decode($body, true);
                            if($InFlightInfoResult == [])
                            {
                                $response = array('service_type' => $trip->service_type,
                                'current_longitude' => 0.0,
                                'current_latitude' => 0.0,
                                'timeout' => "ok",
                          'arrivalTime' => 0,
                          'departureTime' => 0);
                            return response()->json($response);                        
                            }
                            else{
                                // var_dump($InFlightInfoResult);
                                // die();
                                $response = array('service_type' => $trip->service_type,
                                'current_longitude' => $InFlightInfoResult[0][2],
                                'current_latitude' => $InFlightInfoResult[0][1],
                                'timeout' => "ok",
                          'arrivalTime' => 0,
                          'departureTime' => 0);
                            return response()->json($response);
                            }
                        }
		$response = array('service_type' => $trip->service_type,
                          'current_longitude' => 0.0,
                          'current_latitude' => 0.0,
                          'timeout' => "ok",
                          'arrivalTime' => 0,
                          'departureTime' => 0);
                            return response()->json($response);

	}
	else
		{return response()->json(['error' => trans('api.something_went_wrong')], 500);}
    }
    public function trackVessel(Request $request)
    {
        try {
                $trip=Trip::find($request->trip_id);
                $vesselId=$trip->vessel_id;
                $vesselTrackingCount = $trip->vessel_tracking_count;

                $origin = $trip->source_port_id;
                $destination = $trip->destination_port_id;

                $originPort=Port::where('marine_traffic_id', $origin)->first();
                $destinationPort=Port::where('marine_traffic_id', $destination)->first();
                // return $vesselId;
                if($vesselTrackingCount>0)
                {
                    $client = new \GuzzleHttp\Client();
                    $res = $client->request('GET', 'https://services.marinetraffic.com/en/api/exportvessel/v:5/'.Setting::get('marine_traffic_svp_api_key', '').'/timespan:200/shipid:'.$vesselId.'/msgtype:extended');
                    Log::info('marinetraffic single vessel position api response is');
                    Log::info($res->getBody());

                        if($res->getStatusCode()=='200')
                        { 
                            $body= $res->getBody();
                            $xml = simplexml_load_string($body); // where $xml_string is the XML data you'd like to use (a well-formatted XML string). If retrieving from an external source, you can use file_get_contents to retrieve the data and populate this variable.
                            $json = json_encode($xml); // convert the XML string to JSON
                            $array = json_decode($json,TRUE); // convert the JSON-encoded string to a PHP variable

                            $vesselPosition= $array['row']['@attributes'];
                            
                            $trip->vessel_tracking_count=$vesselTrackingCount-1;
                            $trip->save();

                            $response= array('vessel_position' => $vesselPosition,
                             'origin_port_lat' => $originPort->latitude,
                             'origin_port_long' => $originPort->longitude,
                             'origin_port_name' => $originPort->name,
                             'destination_port_lat' => $destinationPort->latitude,
                             'destination_port_long' => $destinationPort->longitude,
                             'destination_port_name' => $destinationPort->name);

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
                        $res = $client->request('GET', 'https://services.marinetraffic.com/en/api/exportvessel/v:5/'.Setting::get('marine_traffic_svp_api_key', '').'/timespan:20/shipid:'.$vesselId.'/msgtype:extended');
                        Log::info('marinetraffic single vessel position api response is');
                            if($res->getStatusCode()=='200')
                            { 
                                $body= $res->getBody();
                                $xml = simplexml_load_string($body); // where $xml_string is the XML data you'd like to use (a well-formatted XML string). If retrieving from an external source, you can use file_get_contents to retrieve the data and populate this variable.
                                $json = json_encode($xml); // convert the XML string to JSON
                                $array = json_decode($json,TRUE); // convert the JSON-encoded string to a PHP variable
    
                                $vesselPosition= $array['row']['@attributes'];
                                
                                $trip->vessel_tracking_count=2;
                                $trip->save();

                                $response= array('vessel_position' => $vesselPosition,
                                    'origin_port_lat' => $originPort->latitude,
                                    'origin_port_long' => $originPort->longitude,
                                    'origin_port_name' => $originPort->name,
                                    'destination_port_lat' => $destinationPort->latitude,
                                    'destination_port_long' => $destinationPort->longitude,
                                    'destination_port_name' => $destinationPort->name);

                                return response()->json($response);
    
                            }else{
    
                                return $res->getBody();
                            }

                        }

                }catch(\Stripe\Exception\CardException $e){
                    return response()->json(['error' => $e->getError()->message], 200);
                }catch (\Stripe\Exception\InvalidRequestException $e) {
                    return response()->json(['error' => 'Sorry for the time being we are unable to track vessel position so please try later'], 200);
                  }catch (\Exception $e) {

                    Log::info("***************Exception while finding vessel position of sea trip***************");
                    Log::info( $e);
                    return response()->json(['error' => 'Sorry for the time being we are unable to track vessel position so please try later'], 200);
                }
        
    }

}
