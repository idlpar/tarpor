<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    // Create or fetch a tag
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $name = strtolower($request->name);

        // Check if the tag already exists
        $tag = Tag::where('name', $name)->first();

        if (!$tag) {
            // Create a new tag
            $tag = Tag::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        return response()->json([
            'id' => $tag->id,
            'name' => ucfirst($tag->name), // Capitalize for display
        ]);
    }

    // Fetch all tags
    public function index()
    {
        $tags = Tag::all()->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => ucfirst($tag->name), // Capitalize for display
            ];
        });

        return response()->json($tags);
    }
    // In your TagController
    public function suggestions(Request $request)
    {
        $query = $request->input('query');
        $tags = Tag::where('name', 'like', "%{$query}%")->get();
        return response()->json($tags);
    }
}
