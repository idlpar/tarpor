<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function suggest(Request $request)
    {
        $this->authorize('viewAny', Tag::class);
        $query = trim($request->input('query', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $tags = Tag::whereRaw('LOWER(name) LIKE ?', [Str::lower($query) . '%'])
            ->orWhereRaw('LOWER(name) LIKE ?', ['% ' . Str::lower($query) . '%'])
            ->distinct('name')
            ->limit(10)
            ->get(['id', 'name'])
            ->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => ucfirst($tag->name), // Capitalize for display
                ];
            });

        return response()->json($tags);
    }

    public function storeMultiple(Request $request)
    {
        $this->authorize('create', Tag::class);
        $validated = $request->validate([
            'tags' => 'required|array',
            'tags.*' => 'string|max:255',
        ]);

        $storedTags = [];
        foreach ($validated['tags'] as $tagName) {
            $baseName = Str::lower(trim($tagName));
            if (empty($baseName)) continue;

            $existingTag = Tag::where('name', $baseName)->first();
            if ($existingTag) {
                $storedTags[] = $existingTag;
                continue;
            }

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
