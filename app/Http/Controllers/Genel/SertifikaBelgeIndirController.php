<?php

namespace App\Http\Controllers\Genel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SertifikaBelgeIndirController extends Controller
{
    //
    /* get belge data from database 
read pdf file and return content
*/
function getBelgeDataIndir($input_params){
    $sertifikaData = $GLOBALS["db"]->getBelgeDataIndir($input_params->tcKimlikNo, $input_params->sertifikaID, $input_params->dilKey);
    if($sertifikaData == false){
        return false;
    } else {
        $pdfContent = file_get_contents($sertifikaData[0]["belge"]);
        $returnArray = array(
            "belge" => $pdfContent,
        );
        return $returnArray;
    }

}
/*
sertifikaBelgeIndir function
*/
function sertifikaBelgeIndir($input_params){
    $result = new stdClass();
    $kurumData = $GLOBALS["db"]->getKurumData($input_params->kurumKodu, $input_params->kullaniciAdi, $input_params->sifre);
    
    //print_r($sertifikalar);
    if($kurumData == false){
        $result->code = "0001";
        $result->message = "KURUM BILGILERI HATALI";
    }else{
        $result->code = "0000";
        $result->message = "ISLEM BASARILI";
        $sertifika = getBelgeDataIndir($input_params);
    }
    if($sertifika == false){
        $result->code = "0003";
        $result->message = "BELGE BULUNAMADI";
    }

    $params = array(
        "kurumKodu" => $input_params->kurumKodu,
        "sonucKodu"=> $result->code,
        "sonucAciklamasi"=> $result->message,

        "belge" => $sertifika["belge"],
        "detayListesi" => array()
    );



    return $params;
}
}
