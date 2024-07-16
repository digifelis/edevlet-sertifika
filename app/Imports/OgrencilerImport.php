<?php

namespace App\Imports;

use App\Models\superadmin\OgrencilerModal;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class OgrencilerImport implements ToModel, WithHeadingRow, WithChunkReading
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

            $ogrenci = OgrencilerModal::where('tcKimlikNo', $row['tckimlikno'])->where('kurumId', Auth::user()->userInstitution)->first();
            if($ogrenci == null) {
                $this->rowCount++;
                return new OgrencilerModal([
                    'ogrenciAdi' => $row['ogrenciadi'], // Assuming 'ogrenci_adi' is the column heading in your Excel file
                    'ogrenciSoyadi' => $row['ogrencisoyadi'], // Assuming 'ogrenci_soyadi' is the column heading
                    'tcKimlikNo' => $row['tckimlikno'], // Assuming 'tc_kimlik_no' is the column heading
                    'kurumId' => Auth::user()->userInstitution,
                ]);
            } else {
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

}




