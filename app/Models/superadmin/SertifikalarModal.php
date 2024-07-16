<?php

namespace App\Models\superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikalarModal extends Model
{
    use HasFactory;
    protected $fillable = [
        'ogrenciId', 
        'kursId',
        'kurumId',
        'sertifikaNo',
        // Add other fillable attributes if necessary
    ];
  /*
    public function kurum()
    {
        return $this->belongsTo(KurumModal::class, 'kurumId', 'id');
    }

    public function kurs()
    {
        return $this->belongsTo(KursModal::class, 'kursId', 'id');
    }

    public function ogrenci()
    {
        return $this->belongsTo(OgrencilerModal::class, 'ogrenciId', 'id');
    }
    */
}
