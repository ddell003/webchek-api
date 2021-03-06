<?php


namespace App\Services\helpers;



use App\Models\Test;
use Illuminate\Support\Facades\Http;

class RunTest
{
    public function __construct(){

    }

    public function run(Test $test){
        if($test->url){
            $process = $this->getHttpCall($test->url);
        }else{
            //run curl call
            return ["status"=>"passed", "message"=>"Not Set up to run curl calls!"];
        }
        $data = [
            "status"=>"running",
            "message"=>"The Test is running"
        ];
        if($process['status'] == $test->expected_status_code){
            $data = [
                "status"=>"passed",
                "message"=>$process['message']." - ".$process["status"],
            ];
        }
        else{
            $data = [
                "status"=>"failed",
                "message"=>$process['message']." - ".$process["status"],
            ];
        }

        return $data;
    }

    public function getHttpCall($url)
    {
        $response = Http::withHeaders([
            'Accepts' => 'application/json',
            'content-type' => 'application/json',
        ])->get($url);
        $status = $response->status();


        if($response->successful()){
            $body = ($response->json()) ? $response->json() : "Success";
            return ["status"=>$status, "message"=>$body,];
        }

        $body = $response->body();

        return ["status"=>$status, "message"=>$body];
    }

    public function runCurl()
    {
        //$call = $this->getHttpCall();
        $raw = `curl --location --request GET 'https://webchek-api.herokuapp.com/api/users' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer n6tJBJWhjXkqJuPfe4jT3T06n47r5HbnT8aWlMFxSrvTWvC5q0LDX0TsU5uLT0m4Ipn5EOl5zTacaK3J' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email":"parkerdell94@gmail.com",
    "password":"password"
}'`;

        $ch = curl_init($raw);
       /* curl_setopt($ch, CURLOPT_URL, $raw);
        // SSL important
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
*/
        $output = curl_exec($ch);
        curl_close($ch);


        //$output = json_decode($output);

        dd($output, $raw);



    }
}
