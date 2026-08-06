<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('aloptama_performa', function (Blueprint $table) {
            $table->id();
            $table->string('kode_alat')->unique(); // Contoh: radar_cuaca, awos_sentani
            $table->string('nama_alat');           // Contoh: Radar Cuaca
            $table->decimal('sla', 5, 2)->default(100.00); // Menyimpan angka desimal seperti 99.19
            $table->decimal('ola', 5, 2)->default(100.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aloptama_performa');
    }
};
