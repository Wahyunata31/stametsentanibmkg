<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    // Tambahkan baris ini. Tanpa ini, database akan selalu kosong.
    protected $fillable = [
        'title', 
        'content', 
        'post_date', 
        'image'
    ];
}