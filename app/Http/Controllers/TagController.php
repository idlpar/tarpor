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
        $query = strtolower($request->input('query', ''));

        $tags = Tag::where('name', 'like', $query . '%')
            ->orWhere('name', 'like', '% ' . $query . '%')
            ->distinct('name')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($tags);
    }


    /**
     * Store a new tag in the database.
     */

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'tags' => 'required|array',
            'tags.*' => 'string|max:255',
        ]);

        $storedTags = [];

        foreach ($request->input('tags') as $tagName) {
            $baseName = strtolower(trim($tagName));
            if (empty($baseName)) continue;

            // Check if exact tag exists
            $existingTag = Tag::where('name', $baseName)->first();

            if ($existingTag) {
                $storedTags[] = $existingTag;
                continue;
            }

            // Check for similar tags
            $similarCount = Tag::where('name', 'like', $baseName . '%')->count();
            $finalTagName = $similarCount > 0 ? $baseName . '-' . ($similarCount + 1) : $baseName;

            $tag = Tag::create([
                'name' => $finalTagName,
                'slug' => Str::slug($finalTagName),
                'product_count' => 0
            ]);

            $storedTags[] = $tag;
        }

        return response()->json([
            'success' => true,
            'tags' => $storedTags
        ]);
    }

}
