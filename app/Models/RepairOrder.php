<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairOrder extends Model
{
    protected $fillable = ['user_id', 'description', 'photo', 'audio', 'phone1', 'phone2', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
