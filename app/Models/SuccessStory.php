<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    protected $fillable = ['campaign_id', 'story', 'image'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
