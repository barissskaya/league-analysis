<?php

namespace App\Traits;

trait HasStatus
{
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopePassive($query)
    {
        return $query->where('status', 0);
    }
}