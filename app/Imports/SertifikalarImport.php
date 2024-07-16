<?php

namespace App\Imports;


use App\Models\superadmin\SertifikalarModal;
use App\Models\superadmin\OgrencilerModal;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;

use App\Services\RabbitMQService;

class SertifikalarImport implements ToModel, WithHeadingRow, WithChunkReading
{
    private $rowCount = 0;
    private $totalCount = 0;
    private $eklenemeyenler = [];
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        // dd($row);
        $this->totalCount++;
        /* get ogrenciId according tckimlikno */
        /* ögrenci bu kurumda varmı yokmu */
        $ogrenci = OgrencilerModal::where('tcKimlikNo', $row['tckimlikno'])->where('kurumId', Auth::user()->userInstitution)->first();
        /* öğrenci var */
        if($ogrenci != null) {
            /* öğrencinin bu sertifikası var mı */
          $sertifika = SertifikalarModal::where('ogrenciId', $ogrenci->id)
          ->where('kurumId', Auth::user()->userInstitution)
          ->where('kursId', $row['kursid'])
          ->first();
            /* öğrencinini bu sertifikası yok */
          if($sertifika == null) {
              $this->rowCount++;
              /* 
              ogrenci 7
              kurs 11
              kurum 2
              */
                echo($ogrenci->id.'-'. $row['kursid'].'-'. Auth::user()->userInstitution);
              //  exit;
              
              $sertifikalar =  new SertifikalarModal([
                  'ogrenciId' => $ogrenci->id, 
                  'kursId' => $row['kursid'], // Assuming 'kursid' is the column heading
                  'kurumId' => Auth::user()->userInstitution,
              ]);
              $sertifikalar->save(); // Save the instance to the database

              $insertedId = $sertifikalar->id; // Get the inserted ID
              

              $input_params = DB::table('sertifikalar_modals')
              ->join('kurum_modals', 'kurum_modals.id', '=', 'sertifikalar_modals.kurumId')
              ->join('kurs_modals', 'kurs_modals.id', '=', 'sertifikalar_modals.kursId')
              ->join('ogrenciler_modals', 'ogrenciler_modals.id', '=', 'sertifikalar_modals.ogrenciId')
              ->select('sertifikalar_modals.*', 'kurum_modals.kurumAdi', 'kurum_modals.kurumKodu',
              'kurs_modals.*', 
              'ogrenciler_modals.tcKimlikNo', 'ogrenciler_modals.ogrenciAdi', 'ogrenciler_modals.ogrenciSoyadi')
              ->where('sertifikalar_modals.kurumId', '=', Auth::user()->userInstitution)
              ->where('sertifikalar_modals.kursId', '=', $row['kursid'])
              ->where('sertifikalar_modals.ogrenciId', '=', $ogrenci->id)
              ->get();
              $input_params = $input_params[0];
              $input_params->lastInsertId = $insertedId;
              $input_params->ogrenciId = $ogrenci->id;
              $input_params->kursId = $row['kursid'];
              $input_params->kurumId = Auth::user()->userInstitution;
              //$input_params = json_encode($input_params);
              $this->publish($input_params);
              return $sertifikalar;
              
          } else {
            /* öğrencinini bu sertifikası var */
              $this->eklenemeyenler[] = $row['tckimlikno'];
          }





        } else {
            /* öğrenci yok */
            $this->eklenemeyenler[] = $row['tckimlikno'];

        }


    }

    /**
     * Set chunk size for chunk reading
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000; // Adjust the chunk size as needed
    }
    /**
     * Get the count of imported rows
     *
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
    
    /**
     * getTotalRowCount
     *
     * @return int
     */
    public function getTotalRowCount(): int
    {
        return $this->totalCount;
    }
    /* eklenmeyenler */    
    /**
     * getEklenemeyenler
     *
     * @return array
     */
    public function getEklenemeyenler(): array
    {
        return $this->eklenemeyenler;
    }

    public function publish($message)
    {
        $rabbitMQService = new RabbitMQService();
        $rabbitMQService->publishMessage($message, ENV('RABBITMQ_QUEUE'));
    }

}




