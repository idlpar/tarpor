<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(Faq::all());
        }
        $faqs = Faq::all();
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

        return redirect()->route('faqs.index')->with('success', 'FAQ created successfully.');
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

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}