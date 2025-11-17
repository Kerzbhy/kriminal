<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataKriminal extends Model
{
    use HasFactory;

    protected $table = 'data_kriminal';
    protected $fillable = [
        'lokasi',
        'latitude',
        'longitude',
        'jumlah_kejadian',
        'jenis_kejahatan',
        'rata_rata_kerugian_juta',
        'jumlah_penduduk',
    ];
}
