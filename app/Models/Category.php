<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = ['name', 'icon'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getIconUrlAttribute()
    {
        if ($this->icon && Storage::disk('public')->exists($this->icon)) {
            return asset('storage/' . $this->icon);
        }
        return null;
    }

    public function hasIcon()
    {
        return $this->icon && Storage::disk('public')->exists($this->icon);
    }
}
