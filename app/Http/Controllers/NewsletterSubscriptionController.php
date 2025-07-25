<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NewsletterSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscribers = NewsletterSubscription::orderByDesc('created_at')->paginate(10);
        $links = [
            'Newsletter Subscribers' => route('admin.newsletter.subscribers.index')
        ];
        return view('dashboard.admin.newsletter.subscribers.index', compact('subscribers', 'links'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewsletterSubscription $subscriber)
    {
        $links = [
            'Newsletter Subscribers' => route('admin.newsletter.subscribers.index'),
            'Edit Subscriber' => null
        ];
        return view('dashboard.admin.newsletter.subscribers.edit', compact('subscriber', 'links'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsletterSubscription $subscriber)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', Rule::unique('newsletter_subscriptions')->ignore($subscriber->id)],
            'is_subscribed' => 'required|boolean',
        ]);

        $subscriber->update($validated);

        return redirect()->route('admin.newsletter.subscribers.index')->with('success', 'Subscriber updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsletterSubscription $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.subscribers.index')->with('success', 'Subscriber deleted successfully.');
    }

    /**
     * Toggle the subscription status of the specified resource.
     */
    public function toggleStatus(NewsletterSubscription $subscriber)
    {
        $subscriber->is_subscribed = !$subscriber->is_subscribed;
        $subscriber->save();

        return back()->with('success', 'Subscription status updated successfully.');
    }
}
