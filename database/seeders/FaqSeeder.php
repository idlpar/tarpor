<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'How do I place an order?',
                'answer' => 'To place an order, simply browse our collections, add your desired items to the cart, and proceed to checkout. Follow the prompts to enter your shipping and payment information.'
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept major credit cards (Visa, MasterCard, American Express), PayPal, and other local payment options as available at checkout.'
            ],
            [
                'question' => 'How can I track my order?',
                'answer' => 'Once your order is shipped, you will receive an email with a tracking number and a link to track your package. You can also find tracking information in your account order history.'
            ],
            [
                'question' => 'What is your return policy?',
                'answer' => 'We offer a 30-day return policy for unworn and unwashed items with original tags attached. Please visit our Returns & Exchanges page for detailed instructions.'
            ],
            [
                'question' => 'Do you offer international shipping?',
                'answer' => 'Yes, we offer international shipping to many countries. Shipping costs and delivery times vary by destination and will be calculated at checkout.'
            ],
            [
                'question' => 'How do I choose the right size?',
                'answer' => 'We provide a detailed size guide on each product page to help you find the perfect fit. We recommend comparing your measurements to our guide.'
            ],
            [
                'question' => "Can I change or cancel my order after it's placed?",
                'answer' => 'We process orders quickly, so changes or cancellations may not always be possible. Please contact our customer service immediately if you need to modify your order.'
            ],
            [
                'question' => 'How do I contact customer service?',
                'answer' => 'You can reach our customer service team via email at support@tarpor.com or through the contact form on our website. We aim to respond within 24-48 hours.'
            ],
            [
                'question' => 'Are your products ethically sourced?',
                'answer' => 'We are committed to ethical sourcing and sustainable practices. We work with suppliers who adhere to fair labor standards and environmentally responsible production methods.'
            ],
            [
                'question' => 'How often do you release new collections?',
                'answer' => 'We regularly update our collections with new designs and seasonal trends. Follow us on social media or subscribe to our newsletter to stay informed about new arrivals.'
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::create($faqData);
        }
    }
}
