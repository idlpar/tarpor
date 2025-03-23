<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GalleryController extends Controller
{
    public function index()
    {
        $media = Media::where('collection_name', 'product_images')
            ->latest()
            ->whereNull('deleted_at') // Exclude trashed media
            ->paginate(30)
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'url' => url("images/{$item->name}.{$item->extension}"),
//                    'url' => url("images/{$item->file_name}"), // SEO-friendly URL
                    'name' => $item->name,
                    'size' => $item->human_readable_size,
                    'uploaded' => $item->created_at->diffForHumans(),
                    'is_trashed' => $item->deleted_at !== null,
                    'srcset' => [
                        'thumb' => url("storage/{$item->id}/conversions/{$item->file_name}-product_thumb.jpg"),
                        'medium' => url("storage/{$item->id}/conversions/{$item->file_name}-product_medium.jpg"),
                        'large' => url("images/{$item->file_name}")
                    ]
                ];
            });
        return response()->json($media);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $media = auth()->user()->addMedia($file)
                ->toMediaCollection('product_images');

            $uploadedFiles[] = [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'name' => $media->name,
                'size' => $media->human_readable_size,
                'uploaded' => $media->created_at->diffForHumans(),
            ];
        }

        return response()->json($uploadedFiles);
    }

    public function delete(Media $media)
    {
        $media->delete();
        return response()->json(['message' => 'Media moved to trash']);
    }

    public function restore(Media $media)
    {
        $media->restore();
        return response()->json(['message' => 'Media restored']);
    }

    public function emptyTrash()
    {
        Media::onlyTrashed()->forceDelete();
        return response()->json(['message' => 'Trash emptied']);
    }
}
