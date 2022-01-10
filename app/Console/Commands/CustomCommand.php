<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use DB;
use App\Http\Controllers\SendPushNotification;
use App\Http\Controllers\AdminController;
use Carbon\Carbon;

class CustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rides';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating the Scheduled Rides Timing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $UserRequest = DB::table('user_requests')->where('status','SCHEDULED')
            ->where('schedule_at','<=',\Carbon\Carbon::now()->addMinutes(30))
            ->get();

        $hour =  \Carbon\Carbon::now()->subHour();
        $futurehours = \Carbon\Carbon::now()->addMinutes(30);
        $date =  \Carbon\Carbon::now();

        \Log::info("Schedule Service Request Started.".$date."==".$hour."==".$futurehours);

        if(!empty($UserRequest)){
            foreach($UserRequest as $ride){
                DB::table('user_requests')
                    ->where('id',$ride->id)
                    ->update(['status' => 'STARTED', 'assigned_at' =>Carbon::now() , 'schedule_at' => null ]);

                //scehule start request push to user
                $User =  User::where('id', $ride->user_id)->first();
                $this->push_fcm($User->device_token,'Driver Arrived ','Your driver has arrived');

                //scehule start request push to provider
                $ProviderDevice =  DB::table('provider_devices')->where('provider_id', $ride->provider_id)->first();
                $this->push_fcm($ProviderDevice->token,'New Booking Request','Please open the app to accept booking');

                DB::table('provider_services')->where('provider_id',$ride->provider_id)->update(['status' =>'riding']);
            }
        }

        $CustomPush = DB::table('custom_pushes')
            ->where('schedule_at','<=',\Carbon\Carbon::now()->addMinutes(30))
            ->get();

        if(!empty($UserRequest)){
            foreach($CustomPush as $Push){
                DB::table('custom_pushes')
                    ->where('id',$Push->id)
                    ->update(['schedule_at' => null ]);

                // sending push
                (new AdminController)->SendCustomPush($Push->id);
            }
        }


    }


    public function push_fcm($token,$title,$message){

        $serverKey = 'AAAAGTwWSDk:APA91bF8VvY24ySZJdDTmn44Pg1KWei1S34cEekja_rSgGI8Sgd9oKosamE-W2kf_9rSs_4NHLqWrcqaJJm3GwWOyNHy0nAU3Xzfytna_VHjgjJQhUwxqTGYj6s_gbrDN184SBNa-HFo3l6aVEgCH81l17ZyY0vb_Q';


        $body = array(
            "to" => $token,
            "notification" => array(
                "title" => $title,
                "body" => $message,
                "sound" => 'alert_tone.mp3',
                "android_channel_id"=> '12345678'
            ),

            "data" => array("targetScreen" => "SplashScreen"),
            "priority" => 1
        );


        $headers = array(
            'Content-Type: application/json',
            'Authorization: key=' . $serverKey
        );

        if($ch = curl_init()) {
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            $result = curl_exec($ch );
            curl_close( $ch );
            //echo $result . "\n\n";
        }


        return 'not';
    }
}
