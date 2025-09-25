<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Models\Item;
use App\Models\ItemRequest;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::with(['college:id,name', 'meetingPoint:id,name,location_id', 'owner:id,name,phone'])
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->string('type')))
            ->when($request->filled('stage'), fn($q) => $q->where('stage', $request->string('stage')))
            ->when($request->filled('college_id'), fn($q) => $q->where('college_id', $request->integer('college_id')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $s = $request->string('q');
                $q->where(fn($qq) => $qq->where('title','like',"%{$s}%")
                                        ->orWhere('description','like',"%{$s}%"));
            })
            ->when($request->filled('location_id'), fn($q) =>
                $q->whereHas('meetingPoint', fn($qq) => $qq->where('location_id', $request->integer('location_id')))
            )
            ->orderByDesc('id')
            ->paginate((int)$request->query('per_page', 20));

        return response()->json(
            $items,
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function filter(Request $request)
    {
        $validated = $request->validate([
            'types'       => 'array',
            'types.*'     => 'in:book,flash',
            'years'       => 'array',
            'years.*'     => 'string',
            'college_id'  => 'nullable|integer|exists:colleges,id',
            'location_id' => 'nullable|integer|exists:locations,id',
            'q'           => 'nullable|string',
            'per_page'    => 'nullable|integer|min:1|max:100',
        ]);

        $types = collect($validated['types'] ?? [])->filter()->values();
        $years = collect($validated['years'] ?? [])->filter()->values();

        $items = Item::with(['college:id,name', 'meetingPoint:id,name,location_id', 'owner:id,name,phone'])
            ->when($types->isNotEmpty(), fn($q) => $q->whereIn('type', $types))
            ->when($years->isNotEmpty(), fn($q) => $q->whereIn('stage', $years))
            ->when($request->filled('college_id'), fn($q) => $q->where('college_id', $request->integer('college_id')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $s = $request->string('q');
                $q->where(fn($qq) => $qq->where('title','like',"%{$s}%")
                                        ->orWhere('description','like',"%{$s}%"));
            })
            ->when($request->filled('location_id'), fn($q) =>
                $q->whereHas('meetingPoint', fn($qq) => $qq->where('location_id', $request->integer('location_id')))
            )
            ->orderByDesc('id')
            ->paginate((int)$request->query('per_page', 20));

        return response()->json(
            $items,
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        // derive owner and location from meeting point if not sent explicitly
        $data['owner_id'] = $user?->id;
        if (!isset($data['location_id']) && isset($data['meeting_point_id'])) {
            $mp = \App\Models\MeetingPoint::find($data['meeting_point_id']);
            if ($mp) {
                $data['location_id'] = $mp->location_id;
            }
        }

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data)->load(['college:id,name','meetingPoint:id,name,location_id','owner:id,name,phone']);

        return response()->json(
            $item,
            201,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function reserve(Request $request, Item $item)
    {
        $request->validate([
            // No extra input right now; placeholder for future fields
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Prevent owner from reserving own item
        if ((int)$item->owner_id === (int)$user->id) {
            return response()->json(['message' => 'You cannot reserve your own item'], 422);
        }

        // Ensure item is available
        if (method_exists($item, 'getAttribute') && $item->getAttribute('status') && $item->status !== 'available') {
            return response()->json(['message' => 'Item is not available'], 422);
        }

        // Create or fetch existing pending request (unique per item+user)
        $itemRequest = ItemRequest::firstOrCreate([
            'item_id'      => $item->id,
            'requester_id' => $user->id,
        ], [
            'status' => 'pending',
        ]);

        return response()->json(
            $itemRequest->load(['item:id,title,type,stage','requester:id,name']),
            201,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'q'        => 'required|string|min:1',
            'type'     => 'nullable|in:book,flash',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $queryString = $validated['q'];

        $items = Item::with(['college:id,name', 'meetingPoint:id,name,location_id', 'owner:id,name,phone'])
            ->when(isset($validated['type']), fn($q) => $q->where('type', $validated['type']))
            ->where(function ($q) use ($queryString) {
                $q->where('title', 'like', "%{$queryString}%");
            })
            ->orderByDesc('id')
            ->paginate((int)($validated['per_page'] ?? 20));

        return response()->json(
            $items,
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function reserved(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $items = Item::with(['college:id,name', 'meetingPoint:id,name,location_id', 'owner:id,name,phone'])
            ->whereHas('itemRequests', function ($q) use ($user) {
                $q->where('requester_id', $user->id)
                  ->where('status', 'pending');
            })
            ->orderByDesc('id')
            ->paginate((int)$request->query('per_page', 20));

        return response()->json(
            $items,
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
