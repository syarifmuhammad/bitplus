<?php
namespace App\Controller;

use App\Repository\ApiRepository;
use App\Repository\SessionRepository;
class PdamController {

    public function cek_tagihan($no_pdam_r = null){
        $no_pdam = $no_pdam_r == null ? (isset($_GET["no_pdam"]) ? $_GET["no_pdam"] : "") : $no_pdam_r;
        $xSrvt = "ReqResp?q=inq&t=pdam&arr=5551%20-%20PDAM%20KOTA%20BARU!" . $no_pdam . "!T";
        $response = ApiRepository::hitApi($xSrvt);
        if($response!=null){
            if($response->rc == "99"){
                if($response->data != "Belum ada tagihan"){
                    SessionRepository::delete();
                    $this->cek_tagihan($no_pdam);
                }else{
                    echo json_encode("Belum ada tagihan");
                }    
            }else{
                $hasil = [
                    "data" => explode("|", $response->data)
                ];
                echo json_encode($hasil);
            }
        }else{
            SessionRepository::delete();
            $this->cek_tagihan($no_pdam);
        }
    }

}