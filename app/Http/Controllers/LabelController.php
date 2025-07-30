<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::orderBy('id', 'desc')->paginate(10);
        $links = [
            'Labels' => route('labels.index')
        ];
        return view('dashboard.admin.labels.index', compact('labels', 'links'));
    }

    public function create()
    {
        $links = [
            'Labels' => route('labels.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.labels.create', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:labels',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $label = Label::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if ($request->has('save_exit')) {
            return redirect()->route('labels.index')->with('success', 'Label created successfully.');
        }

        return redirect()->route('labels.edit', $label)->with('success', 'Label created successfully.');
    }

    public function edit(Label $label)
    {
        $links = [
            'Labels' => route('labels.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.labels.edit', compact('label', 'links'));
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

        if ($request->has('save_exit')) {
            return redirect()->route('labels.index')->with('success', 'Label updated successfully.');
        }

        return redirect()->route('labels.edit', $label)->with('success', 'Label updated successfully.');
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
