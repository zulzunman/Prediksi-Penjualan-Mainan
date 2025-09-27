<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->string('metode')->default('Regresi Linear');
            $table->integer('periode'); // jumlah bulan ke depan
            $table->json('hasil_prediksi'); // simpan hasil prediksi dalam bentuk JSON
            $table->decimal('mape', 8, 2)->nullable(); // akurasi MAPE
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediksi');
    }
};
