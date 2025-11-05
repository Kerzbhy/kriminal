<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Data extends Model
{
    use HasFactory;

    protected $table = 'data_kriminals';
    protected $fillable = [
        'lokasi',
        'latitude',
        'longitude',
        'total_kejadian',
        'jenis_kejadian',
        'avg_kerugian',
        'jumlah_penduduk',
    ];
}
