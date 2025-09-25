<?php

namespace App\Http\Controllers;

use App\Models\MeetingPoint;
use Illuminate\Http\Request;

class MeetingPointController extends Controller
{
    public function index(Request $request)
    {
        $meetingPoints = MeetingPoint::with(['location:id,name'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'location_id']);

        return response()->json(
            $meetingPoints,
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
        ]);

        $meetingPoint = MeetingPoint::create($data)
            ->load('location:id,name');

        return response()->json(
            $meetingPoint,
            201,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
