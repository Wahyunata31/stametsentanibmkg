<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<<< HEAD:database/migrations/2026_05_20_073231_add_post_date_to_news_table.php
        Schema::table('news', function (Blueprint $table) {
            //
        });
========
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('publish_date');
            $table->string('pdf_file'); // Tempat menyimpan file PDF buletin
            $table->timestamps();
    });
>>>>>>>> c902ccb19bd36fa7ae7a0502054dfa0fe037a2ad:database/migrations/2026_05_21_004553_create_bulletins_table.php
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:database/migrations/2026_05_20_073231_add_post_date_to_news_table.php
        Schema::table('news', function (Blueprint $table) {
            //
        });
========
        Schema::dropIfExists('bulletins');
>>>>>>>> c902ccb19bd36fa7ae7a0502054dfa0fe037a2ad:database/migrations/2026_05_21_004553_create_bulletins_table.php
    }
};
