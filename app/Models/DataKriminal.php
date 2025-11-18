<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataKriminal extends Model
{
    use HasFactory;

    protected $table = 'data_kriminal';
    protected $fillable = [
        'kecamatan',
        'latitude',
        'longitude',
        'jenis_kejahatan',
        'kerugian_juta', 
    ];
    
    /**
     * Menonaktifkan fitur timestamp otomatis (created_at & updated_at) dari Eloquent.
     * Laravel tidak akan lagi mencoba mengisi kolom-kolom ini saat menyimpan data.
     *
     * @var bool
     */
    public $timestamps = false; // <-- PERUBAHANNYA ADA DI SINI
}
