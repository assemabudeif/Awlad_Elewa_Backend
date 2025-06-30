<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'user_id', 'product_id', 'quantity', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // حساب سعر الوحدة من السعر الإجمالي المحفوظ
    public function getUnitPriceAttribute()
    {
        return $this->quantity > 0 ? $this->price / $this->quantity : 0;
    }

    // حساب الإجمالي (للوضوح فقط، لأن price يحتوي على الإجمالي فعلاً)
    public function getTotalPriceAttribute()
    {
        return $this->price;
    }
}
