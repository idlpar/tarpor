<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::when($request->query('search'), function ($query) use ($request) {
            $query->where('question', 'like', '%' . $request->query('search') . '%')
                  ->orWhere('answer', 'like', '%' . $request->query('search') . '%');
        })
        ->orderBy('id', 'desc')->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'faqs' => $faqs,
            ]);
        }
        $links = [
            'FAQs' => route('faqs.index')
        ];
        return view('dashboard.admin.faqs.index', compact('faqs', 'links'));
    }

    public function create()
    {
        $links = [
            'FAQs' => route('faqs.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.faqs.create', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        Faq::create($request->all());

        return redirect()->route('faqs.index')->with('success', 'FAQ created successfully.')->with('highlight_faq_id', $faq->id);
    }

    public function edit(Faq $faq)
    {
        $links = [
            'FAQs' => route('faqs.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.faqs.edit', compact('faq', 'links'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faq->update($request->all());

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.')->with('highlight_faq_id', $faq->id);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully.',
            'faq_id' => $faq->id,
        ]);
    }
}