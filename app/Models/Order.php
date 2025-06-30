<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'status', 'total_price', 'address', 'payment_method', 'phone1', 'phone2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // حساب الإجمالي الفعلي من عناصر الطلب
    public function getCalculatedTotalAttribute()
    {
        return $this->orderItems->sum('price');
    }

    // التحقق من صحة الإجمالي المحفوظ
    public function getTotalDiscrepancyAttribute()
    {
        return abs($this->total_price - $this->calculated_total);
    }
}
