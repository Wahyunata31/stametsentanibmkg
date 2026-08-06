<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('aloptama_kendala', function (Blueprint $table) {
            $table->id();
            $table->string('kode_alat'); // Berelasi dengan kode_alat di tabel performa
            $table->text('deskripsi_kendala');
            $table->string('status')->default('Dalam Proses'); // 'Dalam Proses' atau 'Selesai'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aloptama_kendala');
    }
};