<?php

namespace App\Http\Controllers;

use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('name')->get(['id','name']);

        return response()->json(
            $locations,
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

 public function show(Location $location)
{
    $location->load(['meetingPoints:id,location_id,name,address']);

    $data = [
        'id' => $location->id,
        'name' => $location->name,
        'meeting_points' => $location->meetingPoints,
    ];

    return response()->json(
        $data,
        200,
        ['Content-Type' => 'application/json; charset=UTF-8'],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}


}

