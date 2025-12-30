<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id', 'user_id', 'transaction_id', 'amount',
        'platform_fee', 'currency', 'payment_method', 'status',
        'donor_name', 'donor_email', 'is_anonymous', 'message',
        'payment_details'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'payment_details' => 'array'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}