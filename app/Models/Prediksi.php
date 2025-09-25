<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediksi extends Model
{
    protected $table = 'prediksi';

    protected $fillable = [
        'barang_id',
        'metode',
        'periode',
        'hasil_prediksi',
        'mape',
        'dataset_info'
    ];

    protected $casts = [
        'hasil_prediksi' => 'array',
        'mape' => 'decimal:2',
        'periode' => 'integer',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
