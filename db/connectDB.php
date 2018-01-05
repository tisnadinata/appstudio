<?php 
@session_start();
$mysqli = new mysqli("localhost","u4800409_yes1","yesnumber1","u4800409_yesnumber1");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();exit;
}
function url_web(){
    $url_web = "https://yesnumber1.com/";
    return $url_web;
}
function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];
            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        }
        else $base_url = url_web();
        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }
        return $base_url;
}

function imageBase64FromURL($url){
 $urlParts = pathinfo($url);
 $extension = $urlParts['extension'];
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch, CURLOPT_HEADER, 0);
 $response = curl_exec($ch);
 curl_close($ch);
 $base64 = 'data:image/' . $extension . ';base64,' . base64_encode($response);
 return $base64;
}

function setHarga($harga){
    return number_format($harga,0,",",".");
}

function youtube($string){
    return preg_replace(
        "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
        "www.youtube.com/embed/$2",$string
    );
}

function getIpCustomer(){
$ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'IP Tidak Dikenali';
 
    return $ipaddress;
}

function getGeoIP($ip = null, $jsonArray = false) {
    try {
        // If no IP is provided use the current users IP
        if($ip == null) {
            $ip   = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        }
        // If the IP is equal to 127.0.0.1 (IPv4) or ::1 (IPv6) then cancel, won't work on localhost
        if($ip == "127.0.0.1" || $ip == "::1") {
            throw new Exception('You are on a local sever, this script won\'t work right.');
        }
        // Make sure IP provided is valid
        if(!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new Exception('Invalid IP address "' . $ip . '".');
        }
        if(!is_bool($jsonArray)) {
            throw new Exception('The second parameter must be a boolean - true (return array) or false (return JSON object); default is false.');
        }
        // Fetch JSON data with the IP provided
        $url  = "http://freegeoip.net/json/" . $ip;
        // Return the contents, supress errors because we will check in a bit
        $json = @file_get_contents($url);
        // Did we manage to get data?
        if($json === false) {
            return false;
        }
        // Decode JSON
        $json = json_decode($json, $jsonArray);
        // If an error happens we can assume the JSON is bad or invalid IP
        if($json === null) {
            // Return false
            return false;
        } else {
            // Otherwise return JSON data
            return $json;
        }
    } catch(Exception $e) {
        return $e->getMessage();
    }
}

