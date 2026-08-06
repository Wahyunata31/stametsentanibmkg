<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_documents', function (Blueprint $table) {
            $table->id();
            $table->string('airline'); // Nama Maskapai (Garuda, Lion, dll)
            $table->string('flight_number'); // Nomor Penerbangan (contoh: GA654)
            $table->string('document_name'); // Nama tampilan dokumen
            $table->string('file_path'); // Path penyimpanan file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_documents');
    }
};