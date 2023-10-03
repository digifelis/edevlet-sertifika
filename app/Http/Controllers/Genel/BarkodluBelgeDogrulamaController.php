<?php

namespace App\Http\Controllers\Genel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BarkodluBelgeDogrulamaController extends Controller
{
    //
    /* read pdf file and return base64 binary data */
/**
 * pdfGetir
 *
 * @param  mixed $input_param
 * @return void
 */
function pdfGetir($input_param){
    // Specify the path to the PDF file
    $filePath = $input_param;
    // Read the PDF file contents
    $pdfContent = file_get_contents($filePath);
    // Convert the PDF content to Base64 binary
    $base64Binary = base64_encode($pdfContent);
    // Output the Base64 binary data
	return $pdfContent;
    //return $base64Binary;
}
/* get certificate data from database */
/**
 * sertifikaData
 *
 * @param  mixed $input_params
 * @return void
 */
function sertifikaData($input_params){
    $sertifikaData = $GLOBALS["db"]->getBelgeData($input_params->tcKimlikNo, $input_params->barkodNo);
    if($sertifikaData == false){
        return false;
    } else {
        $t=date('d-m-Y');
        $belgeData = pdfGetir($sertifikaData[0]["belge"]);
        $returnArray = array(
            "belge" => $belgeData,
            "tcKimlikNo" => $sertifikaData[0]["tcKimlikNo"],
            "ad" => $sertifikaData[0]["ad"],
            "soyad" => $sertifikaData[0]["soyad"],
            "belgeOlusturulmaTarihi" => $t, // day of today
        );
        return $returnArray;
    }
}
/* barkodluBelgeDogrulama function */
/**
 * barkodluBelgeDogrulama
 *
 * @param  mixed $input_params
 * @return void
 */
function barkodluBelgeDogrulama($input_params){
    $result = new stdClass();
    $kurumData = $GLOBALS["db"]->getKurumData($input_params->kurumKodu, $input_params->kullaniciAdi, $input_params->sifre);
    
    //print_r($sertifikalar);
    if($kurumData == false){
        $result->code = "0001";
        $result->message = "KURUM BILGILERI HATALI";
    }else{
        $result->code = "0000";
        $result->message = "ISLEM BASARILI";
        $sertifika = sertifikaData($input_params);
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
        "tcKimlikNo" => $input_params->tcKimlikNo,
        "ad" => $sertifika["ad"],
        "soyad" => $sertifika["soyad"],
        "belgeOlusturulmaTarihi" => $sertifika["belgeOlusturulmaTarihi"],
    );

    /*
    $decodedData = base64_decode($sertifika["belge"]);
    $filePath = 'files1/'.$input_params->barkodNo.'.pdf';
    file_put_contents($filePath, $decodedData);
    */
    return $params;
}
}
