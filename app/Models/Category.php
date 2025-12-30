<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'description', 'is_active'];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function getActiveCampaignsCountAttribute()
    {
        return $this->campaigns()->where('status', 'active')->count();
    }
}