<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'owner_id',
        'title',
        'type',
        'stage',
        'college_id',
        'location_id',
        'meeting_point_id',
        'description',
        'image_url',
        'is_active',
    ];

    public function college()
    {
        return $this->belongsTo(\App\Models\College::class);
    }

    public function meetingPoint()
    {
        return $this->belongsTo(\App\Models\MeetingPoint::class);
    }

    public function itemRequests()
    {
        return $this->hasMany(\App\Models\ItemRequest::class);
    }

    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }
}
