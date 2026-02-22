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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();

            $table->boolean('adult');
            $table->string('backdrop_path');
            $table->bigInteger('tmdb_id');
            $table->string('title');
            $table->string('original_title');
            $table->text('overview');
            $table->string('poster_path');
            $table->string('media_type');
            $table->string('original_language');
            // genres array - many to many
            $table->decimal('popularity');
            $table->string('release_date');
            $table->boolean('video');
            $table->decimal('vote_average');
            $table->decimal('vote_count');
            $table->string('lang');
            $table->unique(['tmdb_id', 'lang']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
