<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediksi extends Model
{
    use HasFactory;

    protected $table = 'prediksi';

    protected $fillable = [
        'barang_id',
        'metode',
        'periode',
        'hasil_prediksi',
        'mape',
    ];

    protected $casts = [
        'hasil_prediksi' => 'array', // otomatis decode JSON ke array
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
