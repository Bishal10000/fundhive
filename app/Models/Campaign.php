<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'description',
        'story', 'goal_amount', 'current_amount', 'deadline',
        'featured_image', 'gallery_images', 'video_url', 'status',
        'fraud_score', 'is_flagged', 'flag_reason', 'fraud_features',
        'is_featured', 'is_verified', 'views', 'content_hash',
        'last_checked_for_duplicate'
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'fraud_features' => 'array',
        'goal_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'fraud_score' => 'decimal:4',
        'deadline' => 'date',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'is_flagged' => 'boolean',
        'last_fraud_check' => 'datetime'
    ];

    /* -------------------------
     | Relationships
     --------------------------*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function updates()
    {
        return $this->hasMany(Update::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function fraudLogs()
    {
        return $this->hasMany(FraudLog::class);
    }

    /* -------------------------
     | Accessors
     --------------------------*/
    public function getProgressAttribute(): float
    {
        if ($this->goal_amount == 0) return 0;
        return round(($this->current_amount / $this->goal_amount) * 100, 2);
    }

    // ✅ FIXED DAYS LEFT (INTEGER, NO DECIMALS)
    public function getDaysLeftAttribute(): int
    {
        return max(
            0,
            now()->diffInDays(Carbon::parse($this->deadline), false)
        );
    }

    public function getDonorsCountAttribute(): int
    {
        return $this->donations()->count();
    }

    public function getRiskLevelAttribute(): string
    {
        if ($this->fraud_score < 0.3) return 'low';
        if ($this->fraud_score < 0.7) return 'medium';
        return 'high';
    }

    /* -------------------------
     | Model Events
     --------------------------*/
    protected static function booted()
    {
        static::creating(function ($campaign) {
            $text = strtolower($campaign->title . ' ' . $campaign->story);
            $campaign->content_hash = md5($text);
        });
    }
}
