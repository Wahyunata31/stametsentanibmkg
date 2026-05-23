<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $fillable = ['title', 'cover_image', 'publish_date', 'pdf_file'];
}