<?php

namespace App\Services;

use App\Models\Campaign;
use Carbon\Carbon;

class DuplicateCampaignService
{
    public function isDuplicate(array $data, int $userId): bool
    {
        $hash = md5(strtolower($data['title'] . ' ' . $data['story']));

        return Campaign::where('user_id', $userId)
            ->where('content_hash', $hash)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->exists();
    }
}
