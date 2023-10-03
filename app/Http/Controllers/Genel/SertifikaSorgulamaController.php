<?php

namespace App\Http\Controllers\Genel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SertifikaSorgulamaController extends Controller
{
    //
    /*
read sertificate data from database
return list of sertifika
*/
function sertifikaListesi($input_params){
    $sertifikaData = $GLOBALS["db"]->getsertifikaData($input_params->tcKimlikNo);
    $returnArray = array();
    for ($i=0; $i < count($sertifikaData); $i++) { 
        $returnArray[$i]["sertifikaID"] = $sertifikaData[$i]["sertifikaID"];
        $returnArray[$i]["sertifikaNumarasi"] = $sertifikaData[$i]["sertifikaNumarasi"];
        $returnArray[$i]["tur"] = $sertifikaData[$i]["tur"];
        $returnArray[$i]["sertifikaAdi"] = $sertifikaData[$i]["sertifikaAdi"];
        $returnArray[$i]["alindigiYer"] = $sertifikaData[$i]["alindigiYer"];
        $returnArray[$i]["sertifikaGecerlilikTarih"] = $sertifikaData[$i]["sertifikaGecerlilikTarih"];
        $returnArray[$i]["sertifikaDetayListesi"] = array(
            array(
                "baslik" => $sertifikaData[$i]["baslik"],
                "aciklama" => $sertifikaData[$i]["aciklama"],
                )
            );
    }
    return $returnArray;
}
/*
sertifikaSorgula function
*/
function sertifikaSorgula($input_params) {
    $result = new stdClass();
    $kurumData = $GLOBALS["db"]->getKurumData($input_params->kurumKodu, $input_params->kullaniciAdi, $input_params->sifre);
    
    //print_r($sertifikalar);
    if($kurumData == false){
        $result->code = "0001";
        $result->message = "KURUM BILGILERI HATALI";
    }else{
        $result->code = "0000";
        $result->message = "ISLEM BASARILI";
        $sertifikalar = sertifikaListesi($input_params);
    }
    $params = array(
        "kurumKodu" => $input_params->kurumKodu,
        "sonucKodu"=> $result->code,
        "sonucAciklamasi"=> $result->message,


        "sertifikaListesi" => 
           $sertifikalar,

    );

    return $params;
} 
}
