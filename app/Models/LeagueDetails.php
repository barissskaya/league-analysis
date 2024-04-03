<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeagueDetails extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function scopeLeagueById($query, $id)
    {
        return $query->where('league_id', $id);
    }

    /**
     * Get the phone associated with the user.
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(Leagues::class, 'league_id');
    }

    /**
     * Get the phone associated with the user.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Teams::class, 'team_id');
    }

}
