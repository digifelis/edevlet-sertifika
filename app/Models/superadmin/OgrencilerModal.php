<?php

namespace App\Models\superadmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OgrencilerModal extends Model
{
    use HasFactory;
    protected $fillable = [
        'ogrenciAdi',
        'ogrenciSoyadi',
        'tcKimlikNo',
        'kurumId',
    ];
}
