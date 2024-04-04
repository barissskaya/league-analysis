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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->index();
            $table->foreignId('home_team_id')->index();
            $table->foreignId('away_team_id')->index();
            $table->integer('home_goal')->default(0);
            $table->integer('away_goal')->default(0);
            $table->tinyInteger('week');
            $table->boolean('played')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
