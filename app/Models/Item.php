<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'title',
        'type',
        'stage',
        'college_id',
        'meeting_point_id',
        'description',
        'image_path',
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
}
