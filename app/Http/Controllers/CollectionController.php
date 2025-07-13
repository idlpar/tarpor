<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::latest()->paginate(10);
        return view('dashboard.admin.collections.index', compact('collections'));
    }

    public function create()
    {
        return view('dashboard.admin.collections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:collections',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Collection::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('collections.index')->with('success', 'Collection created successfully.');
    }

    public function edit(Collection $collection)
    {
        return view('dashboard.admin.collections.edit', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:collections,slug,' . $collection->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $collection->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('collections.index')->with('success', 'Collection updated successfully.');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();

        return redirect()->route('collections.index')->with('success', 'Collection deleted successfully.');
    }

    public function checkSlug(Request $request)
    {
        $slug = Str::slug($request->input('name'));
        $collectionId = $request->input('id');

        $originalSlug = $slug;
        $count = 1;

        while (Collection::where('slug', $slug)
            ->when($collectionId, function ($query) use ($collectionId) {
                return $query->where('id', '!=', $collectionId);
            })
            ->exists()) {
            $slug = $originalSlug . '_' . $count++;
        }

        return response()->json(['slug' => $slug]);
    }
}
