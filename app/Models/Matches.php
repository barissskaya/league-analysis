<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matches extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function scopeLeagueById($query, $id)
    {
        return $query->where('league_id', $id);
    }

    public function scopeWeekByNumber($query, $number)
    {
        return $query->where('week', $number);
    }

    public function scopeNotPlayed($query)
    {
        return $query->where('played', 0);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Teams::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Teams::class, 'away_team_id');
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(Leagues::class, 'league_id');
    }
}
