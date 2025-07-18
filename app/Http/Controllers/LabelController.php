<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::latest()->paginate(10);
        return view('dashboard.admin.labels.index', compact('labels'));
    }

    public function create()
    {
        return view('dashboard.admin.labels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:labels',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Label::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('labels.index')->with('success', 'Label created successfully.');
    }

    public function edit(Label $label)
    {
        return view('dashboard.admin.labels.edit', compact('label'));
    }

    public function update(Request $request, Label $label)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:labels,slug,' . $label->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $label->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('labels.index')->with('success', 'Label updated successfully.');
    }

    public function destroy(Label $label)
    {
        $label->delete();

        return redirect()->route('labels.index')->with('success', 'Label deleted successfully.');
    }

    public function checkSlug(Request $request)
    {
        $slug = Str::slug($request->input('name'));
        $labelId = $request->input('id');

        $originalSlug = $slug;
        $count = 1;

        while (Label::where('slug', $slug)
            ->when($labelId, function ($query) use ($labelId) {
                return $query->where('id', '!=', $labelId);
            })
            ->exists()) {
            $slug = $originalSlug . '_' . $count++;
        }

        return response()->json(['slug' => $slug]);
    }
}
