<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Media;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::when($request->query('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->query('search') . '%')
                  ->orWhere('slug', 'like', '%' . $request->query('search') . '%');
        })
        ->orderBy('id', 'desc')->paginate(10); // Fetch all brands, including soft deleted ones
        if ($request->ajax()) {
            return response()->json([
                'brands' => $brands,
            ]);
        }
        $links = [
            'Brands' => route('brands.index')
        ];
        return view('dashboard.admin.brands.index', compact('brands', 'links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $links = [
            'Brands' => route('brands.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.brands.create', compact('links'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'slug' => 'nullable|string|max:255|unique:brands',
            'logo_new' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_existing' => 'nullable|integer|exists:media,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $brand->description = $request->description;
        $brand->status = $request->status;
        $brand->save();

        // Handle logo upload or association
        if ($request->hasFile('logo_new')) {
            $file = $request->file('logo_new');
            $path = $file->store('brand_logos', 'public'); // Store the file

            $media = Media::create([
                'model_type' => Brand::class,
                'model_id' => $brand->id,
                'uuid' => Str::uuid(),
                'collection_name' => 'brand_logos',
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_name' => basename($path),
                'mime_type' => $file->getMimeType(),
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => $file->getSize(),
                'directory' => 'brand_logos',
                'alt_text' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'caption' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            ]);
            $brand->logo_id = $media->id;
            $brand->save();
        } elseif ($request->filled('logo_existing')) {
            $brand->logo_id = $request->logo_existing;
            $brand->save();
        }

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.')->with('highlight_brand_id', $brand->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::findOrFail($id);
        return view('dashboard.admin.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        $links = [
            'Brands' => route('brands.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.brands.edit', compact('brand', 'links'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'logo_new' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_existing' => 'nullable|integer|exists:media,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $brand->name = $request->name;
            $brand->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
            $brand->description = $request->description;
            $brand->status = $request->status;

            // Handle logo update or association
            if ($request->hasFile('logo_new')) {
                $file = $request->file('logo_new');
                $path = $file->store('brand_logos', 'public');

                $media = Media::create([
                    'model_type' => Brand::class,
                    'model_id' => $brand->id,
                    'uuid' => Str::uuid(),
                    'collection_name' => 'brand_logos',
                    'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'file_name' => basename($path),
                    'mime_type' => $file->getMimeType(),
                    'disk' => 'public',
                    'conversions_disk' => 'public',
                    'size' => $file->getSize(),
                    'directory' => 'brand_logos',
                    'alt_text' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'caption' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                ]);
                $brand->logo_id = $media->id;
            } elseif ($request->filled('logo_existing')) {
                $brand->logo_id = $request->logo_existing;
            } else {
                $brand->logo_id = null;
            }

            $brand->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand updated successfully.',
                    'data' => $brand
                ]);
            }

            return redirect()->route('brands.index')->with('success', 'Brand updated successfully.')->with('highlight_brand_id', $brand->id);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating brand: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error updating brand: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete(); // Soft delete the brand

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully.',
            'brand_id' => $brand->id,
        ]);
    }

    public function restore(string $id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $brand->restore(); // Restore the soft deleted brand

        return response()->json([
            'success' => true,
            'message' => 'Brand restored successfully.',
            'brand_id' => $brand->id,
        ]);
    }

    public function forceDelete(string $id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $brand->forceDelete(); // Permanently delete the brand

        return response()->json([
            'success' => true,
            'message' => 'Brand permanently deleted.',
            'brand_id' => $brand->id,
        ]);
    }

    public function checkSlug(Request $request)
    {
        $slug = Str::slug($request->query('slug'));
        $ignoreId = $request->query('ignore_id');

        $originalSlug = $slug;
        $counter = 1;

        // Check if the slug exists, excluding the current brand being edited
        while (Brand::where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return response()->json(['suggested' => $slug]);
    }
}
