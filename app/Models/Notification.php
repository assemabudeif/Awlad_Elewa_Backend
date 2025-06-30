<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'custom_notifications';

    protected $fillable = [
        'title',
        'body',
        'image',
        'data',
        'type',
        'sent_to',
        'sent_count',
        'status',
        'scheduled_at',
        'sent_at'
    ];

    protected $casts = [
        'data' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    const TYPE_ALL_USERS = 'all_users';
    const TYPE_SPECIFIC_USERS = 'specific_users';
    const TYPE_CATEGORY_FOLLOWERS = 'category_followers';

    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function getFormattedSentAtAttribute()
    {
        return $this->sent_at ? $this->sent_at->format('d/m/Y H:i') : '-';
    }

    public function getFormattedScheduledAtAttribute()
    {
        return $this->scheduled_at ? $this->scheduled_at->format('d/m/Y H:i') : '-';
    }
}
