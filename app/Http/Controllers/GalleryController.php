<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GalleryController extends Controller
{
    public function index()
    {
        $media = Media::whereNull('deleted_at')
            ->where('collection_name', 'gallery')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'url' => $item->getFullUrl(),
                    'name' => $item->name,
                    'size' => $item->human_readable_size,
                    'uploaded' => $item->created_at->diffForHumans(),
                    'is_trashed' => false,
                ];
            });

        return response()->json($media);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $media = auth()->user()->addMedia($file)
                ->toMediaCollection('gallery');

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
