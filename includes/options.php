<?php

class options
{


    public $rms_token;
    //test params
    protected $rms_endpoint = "https://restapi8.rmscloud.com/";
    protected $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhZ2lkIjoiMTUiLCJjbGlkIjoiMTEyODEiLCJuYmYiOjE2NTk5Mzk2MDUsImV4cCI6MTY2MDAyNjAwNSwiaWF0IjoxNjU5OTM5NjA1LCJpc3MiOiJ3d3cucm1zY2xvdWQuY29tIn0.UzlWB6mZRBOFAc_d-qlHXR7SrCdfDeAO-vOq-Of5GGc";

    function __construct()
    {
        global $wp_session;

        //generate token from rms params
        $params = [
            "agentId" => get_option("rms_agent_id"),
            "agentPassword" => get_option("rms_agent_password"),
            "clientId" => get_option("rms_client_id"),
            "clientPassword" => get_option("rms_client_password"),
            "useTrainingDatabase" => get_option("rms_use_training_database"),
            "moduleType" => [
                get_option("rms_module_type")
            ]

        ];

        $get = $this->curl_remote_post("authToken", $params);

        $decode = json_decode($get);
        $dateToday = date("m-d-Y");
        $tokenDate = date("m-d-Y", strtotime($decode->expiryDate));
        $wp_session["old_token"] = $decode->token;
        if ($dateToday < $tokenDate) {
            $this->rms_token = $wp_session["old_token"];
        } else {
            $this->rms_token = $decode->token;
   }


        return $this->rms_token;
    }


    protected function rms_options()
    {

        if (empty(get_option("rms_mode"))) {
            add_option("rms_mode", "test");
        }

        if (get_option("rms_mode") == "test") {
            $props = array(
                "rms_agent_id" => 15,
                "rms_client_id" => 11281,
                "rms_agent_password" => '1h&29$vk449f8',
                "rms_client_password" => '6k!Dp$N4',
                "rms_use_training_database" => false,
                "rms_module_type" => "distribution",
                "rms_token" => $this->rms_token,
                "rms_mode" => "test",
                "rms_endpoint" => $this->rms_endpoint
            );
        } elseif (get_option("rms_mode") == "live") {
            $props = array(
                "rms_agent_id" => get_option("rms_agent_id"),
                "rms_client_id" => get_option("rms_client_id"),
                "rms_client_password" => get_option("rms_client_password"),
                "rms_agent_password" => get_option("rms_agent_password"),
                "rms_use_training_database" => get_option("rms_use_training_database"),
                "rms_module_type" => get_option("rms_module_type"),
                "rms_authtoken" => get_option("rms_token"),
                "rms_mode" => get_option("rms_token"),
                "rms_endpoint" => get_option("rms_endpoint")
            );
        }

        return $props;
    }


    function get_rms_options()
    {
        return array(
            "rms_agent_id" => get_option("rms_agent_id"),
            "rms_client_id" => get_option("rms_client_id"),
            "rms_agent_password" => get_option("rms_agent_password"),
            "rms_use_training_database" => get_option("rms_use_training_database"),
            "rms_module_type" => get_option("rms_module_type"),
            "rms_token" => get_option("rms_token"),
            "rms_mode" => get_option("rms_mode"),
            "rms_endpoint" => get_option("rms_endpoint")
        );
    }

    public function curl_remote_get($params)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => get_option("rms_endpoint").$params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "authtoken: ".get_option("rms_token")
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }

    public function default_remote_get()
    {
        return wp_remote_get($this->get_rms_options()["rms_endpoint"],
            array('timeout' => 0,
                'method' => 'GET',
                'headers' => array(
                    "authtoken: " . $this->get_rms_options()["rms_token"]
                )
            ));

    }

    public function curl_remote_post($params, $args)

    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->get_rms_options()["rms_endpoint"] . $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($args),
            CURLOPT_HTTPHEADER => array(
                "authtoken:" . get_option("rms_token"),
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