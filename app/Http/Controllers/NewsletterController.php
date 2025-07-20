<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscription = NewsletterSubscription::where('email', $request->email)->first();

        if ($subscription) {
            if ($subscription->is_subscribed) {
                return back()->withErrors(['email' => 'This email is already subscribed to our newsletter.']);
            } else {
                // Re-subscribe if previously unsubscribed
                $subscription->update([
                    'token' => Str::random(32),
                    'is_subscribed' => true,
                ]);
                return back()->with('newsletter_success', 'You have successfully re-subscribed to our newsletter!');
            }
        } else {
            // New subscription
            NewsletterSubscription::create([
                'email' => $request->email,
                'token' => Str::random(32),
                'is_subscribed' => true,
            ]);
            return back()->with('newsletter_success', 'You have successfully subscribed to our newsletter!');
        }
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string|size:32',
        ]);

        $subscription = NewsletterSubscription::where('email', $request->email)
                                            ->where('token', $request->token)
                                            ->first();

        if (!$subscription) {
            return back()->withErrors(['message' => 'Invalid unsubscribe link or email.']);
        }

        $subscription->update(['is_subscribed' => false]);

        return back()->with('newsletter_success', 'You have successfully unsubscribed from our newsletter.');
    }

    public function showSendForm()
    {
        return view('dashboard.admin.newsletter.send');
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'send_to_all' => 'boolean',
            'specific_emails' => 'nullable|string',
        ]);

        $subject = $request->input('subject');
        $content = $request->input('content');
        $sendToAll = $request->boolean('send_to_all');
        $specificEmails = [];

        if (!$sendToAll && $request->specific_emails) {
            $specificEmails = array_map('trim', explode(',', $request->specific_emails));
            $specificEmails = array_filter($specificEmails, 'filter_var'); // Filter out invalid emails
        }

        if ($sendToAll) {
            $subscribers = NewsletterSubscription::where('is_subscribed', true)->get();
        } elseif (!empty($specificEmails)) {
            $subscribers = NewsletterSubscription::whereIn('email', $specificEmails)
                                                ->where('is_subscribed', true)
                                                ->get();
        } else {
            return back()->withErrors(['specific_emails' => 'Please select to send to all or provide specific emails.']);
        }

        if ($subscribers->isEmpty()) {
            return back()->withErrors(['message' => 'No active subscribers found for the selected criteria.']);
        }

        $sentCount = 0;
        $failedEmails = [];

        foreach ($subscribers as $subscriber) {
            try {
                \Mail::to($subscriber->email)->send(new \App\Mail\NewsletterMail($subject, $content, $subscriber->email, $subscriber->token));
                $sentCount++;
            } catch (\Exception $e) {
                $failedEmails[] = $subscriber->email;
                // Log the error for debugging
                \Log::error('Failed to send newsletter to ' . $subscriber->email . ': ' . $e->getMessage());
            }
        }

        if (empty($failedEmails)) {
            return back()->with('newsletter_success', sprintf('Newsletter sent to %d subscribers successfully!', $sentCount));
        } else {
            return back()->withErrors(['newsletter_error' => sprintf('Newsletter sent to %d subscribers, but failed for: %s', $sentCount, implode(', ', $failedEmails))]);
        }
    }
}
