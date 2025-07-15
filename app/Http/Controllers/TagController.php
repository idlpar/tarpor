<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function suggest(Request $request)
    {
        $query = trim($request->input('query', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $tags = Tag::where(function ($q) use ($query) {
                $q->where('name', 'like', $query . '%')
                  ->orWhere('name', 'like', '% ' . $query . '%');
            })
            ->distinct('name')
            ->limit(5) // Limit to 5 suggestions
            ->get(['id', 'name', 'slug'])
            ->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => ucfirst((string) $tag->name), // Cast to string for ucfirst
                    'slug' => $tag->slug,
                ];
            });

        return response()->json($tags);
    }

    public function storeMultiple(Request $request)
    {
        $validated = $request->validate([
            'tags' => 'required|array',
            'tags.*' => 'string|max:255',
        ]);

        $storedTags = [];
        foreach ($validated['tags'] as $tagName) {
            $baseName = Str::lower(trim($tagName));
            if (empty($baseName)) continue;

            $tag = Tag::firstOrCreate(['name' => $baseName], ['slug' => Str::slug($baseName)]);

            $storedTags[] = $tag;
        }

        return response()->json([
            'success' => true,
            'tags' => $storedTags
        ]);
    }
}
