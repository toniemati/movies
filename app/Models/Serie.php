<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    use HasFactory;

    protected $fillable = [
        'adult',
        'backdrop_path',
        'tmdb_id',
        'name',
        'original_name',
        'overview',
        'poster_path',
        'media_type',
        'original_language',
        'popularity',
        'first_air_date',
        'vote_average',
        'vote_count',
        'lang',
    ];
}
