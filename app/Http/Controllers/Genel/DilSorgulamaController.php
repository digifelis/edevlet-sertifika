<?php

namespace App\Http\Controllers\Genel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;





class DilSorgulamaController extends Controller
{
    //    
    /**
     * dilSorgula
     *
     * @param  mixed $input_params
     * @return void
     */
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
}
