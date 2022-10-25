<?php
namespace App\Repository;

use App\Repository\ApiRepository;
use App\Repository\SessionRepository;
use Curl\Curl;
use Exception;

class LoginRepository {

    private static $username = "hendri.arifin";
    private static $password = "mandi2212";
    private static $code = "221299";
    public static function login(){
        $url = self::$username . "!" . self::$password . "!0";
        $urlx = base64_encode($url);
        $xSrvt = "prerequire?q=pre&arr=".$urlx;
        $token = base64_encode('2d5c15176a695fd7dbf051e37cbcb21e!'.$xSrvt.'!127.0.0.1');
        try{
            $curl = new Curl();
            $curl->setHeader('Authority', $token);
            $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
            $curl->get(ApiRepository::makeUrl($xSrvt));
            if(!$curl->error){
                $response = json_decode($curl->response);
                $arr = explode(",", $response->data);
                $keyLog = $arr[2];
                $headers = explode(';', $curl->response_headers[2])[0];
                preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers, $matches);
                $cookies = array();
                foreach($matches[1] as $item) {
                    parse_str($item, $cookie);
                    $cookies = array_merge($cookies, $cookie);
                }
                if($response->rc == "01"){
                    SessionRepository::save($cookies["JSESSIONID"], $keyLog);
                    $url2 = base64_encode(self::$username . "!" . self::$password . "!" . self::$code . "!-NA-!0");
                    $xSrvt2 = "prerequire?q=vld&arr=".$url2;
                    $response = ApiRepository::hitApi($xSrvt2);
                    if($response != null && $response->rc != "00"){
                        throw new Exception("Gagal!");
                    }
                }else{
                    throw new Exception("Gagal!");
                }
                return true;
            }else{
                throw new Exception("Gagal!");
            }
        }catch (Exception $e) {
            return false;
        }
    }

}