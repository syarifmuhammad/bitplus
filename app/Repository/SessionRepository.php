<?php
namespace App\Repository;

class SessionRepository {

    public static function save(String $cookie, String $keyLog): bool{
        $session = [
            "cookie" => $cookie,
            "keyLog" => $keyLog
        ];
        file_put_contents("session.txt", json_encode($session));
        return true;
    }

    public static function getSession(): array{
        if(file_exists("session.txt")){
            $session = file_get_contents("session.txt");
            $session = json_decode($session);
            $cookie = $session->cookie;
            $keyLog = $session->keyLog;
            $response = [
                "cookie" => $cookie,
                "keyLog" => $keyLog
            ];   
        }else{
            $response = [];
        }
        return $response;
    }

    public static function checkSession(): bool{
        if(file_exists("session.txt")){
            $session = file_get_contents("session.txt");
            $session = json_decode($session);
            return isset($session->cookie) && isset($session->keyLog);
        }else{
            return false;
        }
    }

    public static function delete(){
        if(file_exists("session.txt")){
            unlink("session.txt");
        }
    }

}