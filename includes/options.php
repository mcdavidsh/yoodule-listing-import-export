<?php

class options
{

    protected $rms_param = array(

    );
    protected $pre = "rms_";
    protected $agentIdRow = "rms_agent_id";
    protected $clientIdRow = "rms_client_id";
    protected $clientPassword = "rms_agent_password";
    protected $useTrainingDatabase = "rms_use_training_database";
    protected $moduleType = "module_type";
    protected $endpoint = "https://restapi8.rmscloud.com/";
    protected $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhZ2lkIjoiMTUiLCJjbGlkIjoiMTEyODEiLCJuYmYiOjE2NTk3MDQwOTgsImV4cCI6MTY1OTc5MDQ5OCwiaWF0IjoxNjU5NzA0MDk4LCJpc3MiOiJ3d3cucm1zY2xvdWQuY29tIn0.yKLxgXWaV99zDCGVARdY9-tgukgZqwWd_a0UPiG22Yc";
    public $live_id = 612;
    public $live_password = "Q3IPf7IPk0EdKfD";
    public $moduleType_live = "distribution";
    
    public function curl_remote_get($url, $auth)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "authtoken: $auth"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }

    public function default_remote_get($url, $auth)
    {
        return wp_remote_get($url,
            array('timeout' => 0,
                'method' => 'GET',
                'headers' => array(
                    "authtoken: $auth"
                )
            ));

    }

    public function curl_remote_post($url, $args, $authorization)

    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($args),
            CURLOPT_HTTPHEADER => array(
                "authtoken: $authorization",
                'Content-Type: application/json',
                'accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }

    public function default_remote_post($url, $args, $auth)
    {

        wp_remote_post($url, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array("authtoken: $auth", "Content-type" => 'application/json'),
                'body' => $args,
                'cookies' => array()
            )
        );
    }
}