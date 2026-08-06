<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'airline',
        'flight_number',
        'document_name',
        'file_path'
    ];
}