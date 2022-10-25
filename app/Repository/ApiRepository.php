<?php
namespace App\Repository;

use Curl\Curl;
use App\Repository\SessionRepository;
use App\Repository\LoginRepository;

class ApiRepository {

    public static function makeUrl(String $xSrvt): String{
        return "https://119.82.241.42:6008/bitplus/" . $xSrvt;
    }

    public static function getToken(String $keyLog, String $xSrvt): String{
        $token = base64_encode($keyLog.'!'.$xSrvt.'!127.0.0.1');        
        return $token;
    }

    public static function hitApi(String $xSrvt){
        if(!SessionRepository::checkSession()) {
            LoginRepository::login();
        }
        $session = SessionRepository::getSession();
        $curl = new Curl();
        $curl->setHeader('Authority', self::getToken($session["keyLog"], $xSrvt));
        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_COOKIE, 'JSESSIONID='.$session["cookie"]);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->get(self::makeUrl($xSrvt));
        if(!$curl->error){
            return json_decode($curl->response);
        }else{
            return null;
        }
    } 
    
}