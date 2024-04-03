<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teams extends Model
{
    use HasFactory;
    public $timestamps = false;


    public function homeMatches(): HasMany
    {
        return $this->hasMany(Matches::class, 'home_team_id');
    }


    public function awayMatches(): HasMany
    {
        return $this->hasMany(Matches::class, 'away_team_id');
    }
}
