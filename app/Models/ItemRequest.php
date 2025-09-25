<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    protected $fillable = [
        'item_id',
        'requester_id',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }

    public function requester()
    {
        return $this->belongsTo(\App\Models\User::class, 'requester_id');
    }
}


