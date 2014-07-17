<?php

# MB : Curl lib

class MBCurl {
    public static function post($url, $data=array()) {
        # url-ify & secure the data [for the POST action]
        $data_string='';
        foreach($data as $key=>$value)
            $data_string .= $key.'='.urlencode($value).'&'; 
        rtrim($data_string, '&');

        # open connection
        $ch = curl_init();

        global $curl_user, $curl_pwd, $curl_host;

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => count($data_string),
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 4,      // timeout on connect in seconds
            CURLOPT_TIMEOUT        => 6,      // timeout on response in seconds
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,    // fuck verysign (self signed CA) 
            CURLOPT_HTTPHEADER     => array('Host: backend.martin-dev.eu'), // Django ALLOWED HOSTS
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC, //auth type (https+http_auth are enought for non sensitive data)
            CURLOPT_USERPWD        => sprintf('%s:%s', $curl_user, $curl_pwd) //http auth
        );

        curl_setopt_array($ch, $options);

        # execute post
        $content = curl_exec($ch);
        $err = curl_errno($ch);

        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );

        #close connection
        curl_close($ch);

        if ($err) {
            throw new Exception($errmsg, $err);
        }

        if ($header['http_code'] !== 200) {
            throw new Exception($errmsg, $header['http_code']);
        }

        return $content;
    }
}
?>
