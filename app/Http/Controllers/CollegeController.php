<?php

namespace App\Http\Controllers;

use App\Models\College;

class CollegeController extends Controller
{
    public function index()
    {
        $colleges = College::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'code', 'name']);

        return response()->json(
            $colleges,
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
