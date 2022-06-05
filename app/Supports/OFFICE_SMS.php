<?php


namespace App\Supports;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Matthewbdaly\SMS\Drivers\Nexmo;
use Matthewbdaly\SMS\Drivers\Twilio;
use Matthewbdaly\SMS\Client;

class OFFICE_SMS
{
    static function send($to, $content){
        $str = ltrim($to, '0'); //remove '0'
        $guzzle = new GuzzleClient;
        $resp = new Response;
        $driver = new Nexmo($guzzle, $resp, [
            'api_key' => env('API_KEY'),
            'api_secret' => env('API_SECRET'),
        ]);
        $client = new Client($driver);
        $msg = [
            'to'      => '+84'.$str,
            'from'    => '+84 '.env('PHONE_NUMBER_RERISTERS'),
            'content' => $content,
        ];
        $client->send($msg);
    }

    static function sendSMS($sendTo, $content){
        $str = ltrim($sendTo, '0'); //remove '0'
        $to = "+84".$str;
        $from = getenv("TWILIO_FROM");
        $message = $content;
        //open connection

        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, getenv("TWILIO_SID").':'.getenv("TWILIO_TOKEN"));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_URL, sprintf('https://api.twilio.com/2010-04-01/Accounts/'.getenv("TWILIO_SID").'/Messages.json', getenv("TWILIO_SID")));
        curl_setopt($ch, CURLOPT_POST, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'To='.$to.'&From='.$from.'&Body='.$message);

        // execute post
        $result = curl_exec($ch);
        $result = json_decode($result);
        // close connection
        curl_close($ch);
    }
}