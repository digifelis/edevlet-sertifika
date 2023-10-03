<?php

namespace App\Http\Controllers\Genel;

use Illuminate\Http\Request;
use SoapServer;

class ServiceController 
{

    public function dilSorgula(){
        $params = array(
           "kurumKodu" => "1111",
           "sonucKodu"=> "0000",
           "sonucAciklamasi"=> "ISLEM BASARILI",
           "dilListesi" => array(
               array(
                   "dilKey" => "tr",
                   "dilValue" => "Türkçe"
               )
           ),
       );
    return $params;
}
    public function createSoapServer(){
        ini_set("soap.wsdl_cache_enabled", "0"); 
        $server = new SoapServer(
        public_path("Universite.wsdl"),
          array('soap_version' => SOAP_1_1)
         );
        $server->addFunction('ServiceController.dilSorgula'); 
        $server->handle(); 
    }

}
