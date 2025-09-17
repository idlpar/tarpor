<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewsletterSubscription;
use App\Mail\NewsletterMail;
use Illuminate\Support\Facades\Mail;

class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send {subject} {content}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a newsletter to all subscribed users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subject = $this->argument('subject');
        $content = $this->argument('content');

        $subscribers = NewsletterSubscription::where('is_subscribed', true)->get();

        if ($subscribers->isEmpty()) {
            $this->info('No subscribed users found.');
            return Command::SUCCESS;
        }

        $this->info(sprintf('Sending newsletter to %d subscribers...', $subscribers->count()));

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterMail($subject, $content, $subscriber->email, $subscriber->token));
                $this->info(sprintf('Sent to %s', $subscriber->email));
            } catch (\Exception $e) {
                $this->error(sprintf('Failed to send to %s: %s', $subscriber->email, $e->getMessage()));
            }
        }

        $this->info('Newsletter sending complete.');
        return Command::SUCCESS;
    }
}