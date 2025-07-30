<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::orderBy('id', 'desc')->paginate(10);
        $links = [
            'Collections' => route('collections.index')
        ];
        return view('dashboard.admin.collections.index', compact('collections', 'links'));
    }

    public function create()
    {
        $links = [
            'Collections' => route('collections.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.collections.create', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:collections',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $collection = Collection::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if ($request->has('save_exit')) {
            return redirect()->route('collections.index')->with('success', 'Collection created successfully.');
        }

        return redirect()->route('collections.edit', $collection)->with('success', 'Collection created successfully.');
    }

    public function edit(Collection $collection)
    {
        $links = [
            'Collections' => route('collections.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.collections.edit', compact('collection', 'links'));
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

        if ($request->has('save_exit')) {
            return redirect()->route('collections.index')->with('success', 'Collection updated successfully.');
        }

        return redirect()->route('collections.edit', $collection)->with('success', 'Collection updated successfully.');
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
