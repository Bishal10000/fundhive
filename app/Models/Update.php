<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Update extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id', 'user_id', 'title', 'content',
        'media', 'likes_count', 'comments_count', 'is_pinned'
    ];

    protected $casts = [
        'media' => 'array',
        'is_pinned' => 'boolean'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}