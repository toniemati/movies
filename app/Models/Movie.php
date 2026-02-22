<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'adult',
        'backdrop_path',
        'tmdb_id',
        'title',
        'original_title',
        'overview',
        'poster_path',
        'media_type',
        'original_language',
        'popularity',
        'release_date',
        'video',
        'vote_average',
        'vote_count',
        'lang',
    ];
}
