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
        Schema::create('series', function (Blueprint $table) {
            $table->id();

            $table->boolean('adult');
            $table->string('backdrop_path');
            $table->bigInteger('tmdb_id');
            $table->string('name');
            $table->string('original_name');
            $table->text('overview');
            $table->string('poster_path');
            $table->string('media_type');
            $table->string('original_language');
            // genres array - many to many
            $table->decimal('popularity');
            $table->string('first_air_date');
            $table->decimal('vote_average');
            $table->decimal('vote_count');
            // origin country array - many to many
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
        Schema::dropIfExists('series');
    }
};
