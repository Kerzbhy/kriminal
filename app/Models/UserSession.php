<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    // Table yang digunakan (optional, default nama plural dari model)
    protected $table = 'user_sessions';

    // Field yang bisa diisi secara massal
    protected $fillable = [
        'user_id',
        'session_token',
    ];

    // Relasi ke User (opsional, kalau mau akses user dari session)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
