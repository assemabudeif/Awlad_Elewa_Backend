<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the admin's full name
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Check if admin is super admin
     */
    public function isSuperAdmin()
    {
        return $this->id === 1; // First admin is super admin
    }

    /**
     * Get admin role display name
     */
    public function getRoleDisplayName()
    {
        return $this->isSuperAdmin() ? 'مدير رئيسي' : 'مدير';
    }

    /**
     * Get admin role badge class
     */
    public function getRoleBadgeClass()
    {
        return $this->isSuperAdmin() ? 'badge-success' : 'badge-info';
    }
}
