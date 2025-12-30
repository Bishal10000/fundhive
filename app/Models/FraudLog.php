<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FraudLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id', 'user_id', 'fraud_score',
        'features_used', 'prediction_details', 'status',
        'admin_notes'
    ];

    protected $casts = [
        'fraud_score' => 'decimal:4',
        'features_used' => 'array',
        'prediction_details' => 'array'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}