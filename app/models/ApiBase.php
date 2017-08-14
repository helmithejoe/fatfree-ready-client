<?php
//no namespace for model
class ApiBase
{
    public $debug = false;
    public $timeout = 30;
    public $retry_limit = 5;

    public function curl_send(
        $url, $method, $data, $content_type, $additional_headers=array(),
        $basic = array()
    ) {
        //$data = urlencode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if($method == 'POST') curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        if(!empty($basic))
            curl_setopt($ch, CURLOPT_USERPWD, $basic['username'] . ":" . $basic['password']);
        //curl_setopt($ch, CURLOPT_VERBOSE, true);
        $headers = array(
            'Content-Type: '.$content_type,
            'Content-Length: ' . @strlen($data)
        );

        if(is_array($data)) unset($headers[1]);

        $headers = array_merge($headers, $additional_headers);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $httpcode = 0;
        $retry = 0;
        while($httpcode != 200) {
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($retry >= $this->retry_limit) break;
            $retry++;
        }

        //$error = curl_error($ch);
        if($this->debug) {
            $information = curl_getinfo($ch);
            //print_r($data);exit;
            print_r($information);exit;
        }

        /* Print complete logs */
        /*
        echo 'DATETIME='.date('Y-m-d H:i:s')."\n";
        echo 'URL='.$url."\n";
        echo 'METHOD='.$method."\n";
        echo 'CONTENT_TYPE='.$content_type."\n";
        echo 'HEADERS='."\n";
        print_r($additional_headers);
        echo "\n";
        echo 'POST_DATA='."\n";
        print_r($data);
        echo "\n";
        echo 'RESPONSE='."\n";
        echo $result;
        echo "\n";
        */
		//$information = curl_getinfo($ch);
		//return $information;
        return $result;
    }

}
