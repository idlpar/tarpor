<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{

    /**
     * Handle tag suggestions based on user input.
     */
    public function suggest(Request $request)
    {
        // Validate the request
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        // Get the search query
        $query = $request->input('query');

        // Fetch matching tags from the database
        $tags = Tag::where('name', 'like', $query . '%')
            ->distinct('name') // Ensure unique names
            ->limit(10) // Limit the number of suggestions
            ->get(['id', 'name']);

        // Return the suggestions as JSON
        return response()->json($tags);
    }

    /**
     * Store a new tag in the database.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        // Create the tag
        $tag = Tag::create([
            'name' => Str::lower($request->input('name')), // Store in lowercase
            'slug' => Str::slug($request->input('name')), // Generate a URL-friendly slug
            'description' => $request->input('description', null), // Optional description
            'product_count' => 0, // Default product count
        ]);

        // Return the created tag as JSON
        return response()->json($tag, 201);
    }


}
