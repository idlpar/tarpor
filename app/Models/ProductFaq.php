<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFaq extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'faq_id',
        'question',
        'answer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }
}