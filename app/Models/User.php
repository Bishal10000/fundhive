<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'address',
        'bio',
        'social_links',
        'email_verified_at',
        'is_blocked',
        'education',
        'occupation',
        'work_history',
        'is_verified',
        'background_notes',
        'verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'social_links' => 'array',
        'is_blocked' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime'
    ];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function fraudLogs()
    {
        return $this->hasMany(FraudLog::class);
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
}