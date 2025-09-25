<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:book,flash',
            'stage' => 'required|string',
            'college_id' => 'required|exists:colleges,id',
            'meeting_point_id' => 'required|exists:meeting_points,id',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'boolean',
        ];
    }
}
