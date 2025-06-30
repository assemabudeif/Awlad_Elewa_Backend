<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairOrder extends Model
{
    protected $fillable = ['user_id', 'description', 'photo', 'audio', 'phone1', 'phone2', 'status', 'estimated_cost', 'final_cost', 'notes'];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // التحقق من إمكانية تعديل الطلب
    public function canBeEdited()
    {
        return in_array($this->status, ['pending', 'in_progress']);
    }

    // التحقق من إمكانية إلغاء الطلب
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'in_progress']);
    }

    // الحصول على نص الحالة بالعربية
    public function getStatusTextAttribute()
    {
        $statusText = [
            'pending' => 'قيد الانتظار',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
        ];

        return $statusText[$this->status] ?? $this->status;
    }

    // الحصول على لون الحالة
    public function getStatusColorAttribute()
    {
        $statusColors = [
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];

        return $statusColors[$this->status] ?? 'secondary';
    }

    // فحص وجود الملفات
    public function hasPhoto()
    {
        return !empty($this->photo) && file_exists(storage_path('app/public/' . $this->photo));
    }

    public function hasAudio()
    {
        return !empty($this->audio) && file_exists(storage_path('app/public/' . $this->audio));
    }

    // الحصول على URL الصورة
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    // الحصول على URL الصوت
    public function getAudioUrlAttribute()
    {
        return $this->audio ? asset('storage/' . $this->audio) : null;
    }
}
