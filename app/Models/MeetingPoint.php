<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingPoint extends Model
{
    protected $fillable = ['location_id', 'name', 'description', 'lat', 'lng'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
