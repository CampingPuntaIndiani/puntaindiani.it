<?php

# MB : Curl lib

class MBCurl {
    public static function post($url, $data=array())
    {
        # url-ify & secure the data [for the POST action]
        $data_string='';
        foreach($data as $key=>$value)
            $data_string .= $key.'='.urlencode($value).'&'; 
        rtrim($data_string, '&');

        # open connection
        $ch = curl_init();

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
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false
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

        return $content;
    }

    public static function get($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
}
?>
