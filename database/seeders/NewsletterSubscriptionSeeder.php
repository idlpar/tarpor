<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsletterSubscription;
use Illuminate\Support\Str;

class NewsletterSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            ['email' => 'john.doe@example.com', 'is_subscribed' => true],
            ['email' => 'jane.smith@example.com', 'is_subscribed' => true],
            ['email' => 'test.user@example.com', 'is_subscribed' => false],
            ['email' => 'fashion.lover@example.com', 'is_subscribed' => true],
            ['email' => 'style.guru@example.com', 'is_subscribed' => true],
            ['email' => 'daily.deals@example.com', 'is_subscribed' => true],
            ['email' => 'unsubscribed.user@example.com', 'is_subscribed' => false],
            ['email' => 'trendsetter@example.com', 'is_subscribed' => true],
            ['email' => 'new.fashion@example.com', 'is_subscribed' => true],
            ['email' => 'sample.email@example.com', 'is_subscribed' => true],
        ];

        foreach ($subscriptions as $subData) {
            NewsletterSubscription::create(array_merge($subData, [
                'token' => Str::random(32), // Generate a random token
            ]));
        }
    }
}
