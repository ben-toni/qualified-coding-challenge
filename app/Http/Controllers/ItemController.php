<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cursor = $request->query('cursor');

        $items = Item::with('user')
            ->orderBy('id', 'desc')
            ->cursorPaginate(10, ['*'], 'cursor', $cursor);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $items->items(),
                'next_cursor' => $items->nextCursor()?->encode(),
            ]);
        }

        return Inertia::render('Items/Index', [
            'initialItems' => $items->items(),
            'nextCursor' => $items->nextCursor()?->encode(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Items/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        }

        $request->user()->items()->create([
            'title' => $validated['title'],
            'image_path' => $path,
            'description' => $validated['description'] ?? '',
        ]);

        return redirect()->route('items.index')->with('success', 'Item created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
