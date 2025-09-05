<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LabelController extends Controller
{
    public function index(Request $request)
    {
        $labels = Label::when($request->query('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->query('search') . '%')
                      ->orWhere('description', 'like', '%' . $request->query('search') . '%');
        })
        ->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
            ]);
        }
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
            return redirect()->route('labels.index')->with('success', 'Label created successfully.')->with('highlight_label_id', $label->id);
        }

        return redirect()->route('labels.index')->with('success', 'Label created successfully.')->with('highlight_label_id', $label->id);
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
            return redirect()->route('labels.index')->with('success', 'Label updated successfully.')->with('highlight_label_id', $label->id);
        }

        return redirect()->route('labels.edit', $label)->with('success', 'Label updated successfully.')->with('highlight_label_id', $label->id);
    }

    public function destroy(Label $label)
    {
        $label->delete();

        return response()->json([
            'success' => true,
            'message' => 'Label deleted successfully.',
            'label_id' => $label->id,
        ]);
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
