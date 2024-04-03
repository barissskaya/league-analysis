<?php

namespace App\Models;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Leagues extends Model
{
    use HasFactory, HasStatus;

    public $timestamps = false;


    public function leagueDetails(): HasMany
    {
        return $this->hasMany(LeagueDetails::class, 'league_id', 'id')->orderBy('points', 'DESC');
    }

    public function matches(): HasMany
    {
        return $this->hasMany(Matches::class, 'league_id', 'id');
    }

    public function isFinished(): bool
    {
        return $this->status === 0;
    }
}
