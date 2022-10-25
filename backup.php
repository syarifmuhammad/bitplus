<?php
include('vendor/autoload.php');

function makeUrl($keyLog, $no_pdam){
    // $urlx = base64_encode($url2);
    $xSrvt = "ReqResp?q=inq&t=pdam&arr=5551%20-%20PDAM%20KOTA%20BARU!".$no_pdam."!T";
    $token = base64_encode($keyLog.'!'.$xSrvt.'!127.0.0.1');
    return [
        "url" => "https://119.82.241.42:6008/bitplus/".$xSrvt,
        "token" => $token
    ];
}

function hitApi($cookie, $url){
    $curl = new Curl\Curl();
    $curl->setHeader('Authority', $url['token']);
    $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
    $curl->setOpt(CURLOPT_COOKIE, 'JSESSIONID='.$cookie);
    $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
    $curl->get($url['url']);
    return $curl;
}
$url = "hendri.arifin!mandi2212!0";
$urlx = base64_encode($url);
$xSrvt = "prerequire?q=pre&arr=".$urlx;
$token = base64_encode('2d5c15176a695fd7dbf051e37cbcb21e!'.$xSrvt.'!127.0.0.1');
// $cookie_jar = tempnam(getcwd().'/tmp','coo');
$curl = new Curl\Curl();
$curl->setHeader('Authority', $token);
// $curl->setOpt(CURLOPT_COOKIEJAR, $cookie_jar);
$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
$curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
$curl->get("https://119.82.241.42:6008/bitplus/".$xSrvt);
// echo $xSrvt;
// echo "</br>";
if ($curl->error) {
    echo $curl->error_code;
}
else {
    $response = json_decode($curl->response);
    $arr = explode(",", $response->data);
    $keyLog = $arr[2];
    $url2 = "hendri.arifin!mandi2212!221299!-NA-!0";
    $urlx2 = base64_encode($url2);
    $xSrvt2 = "prerequire?q=vld&arr=".$urlx2;
    $token2 = base64_encode($keyLog.'!'.$xSrvt2.'!127.0.0.1');
    $tes = explode(';', $curl->response_headers[2])[0];
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $tes, $matches);
    $cookies = array();
    foreach($matches[1] as $item) {
        parse_str($item, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }
    $curl = new Curl\Curl();
    $curl->setHeader('Authority', $token2);
    $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
    $curl->setOpt(CURLOPT_COOKIE, 'JSESSIONID='.$cookies["JSESSIONID"]);
    $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
    $curl->get("https://119.82.241.42:6008/bitplus/".$xSrvt2);
    if(!$curl->error){
        // $response2 = json_decode($curl->response);
        $hasil = hitApi($cookies["JSESSIONID"], makeUrl($keyLog, isset($_GET['no_pdam']) ? $_GET['no_pdam'] : ''));
        // var_dump($hasil);
        if(!$hasil->error){
            $result = json_decode($hasil->response);
            echo $result->data;
        }
    }  else{
        // var_dump($curl);
    }
}
?>