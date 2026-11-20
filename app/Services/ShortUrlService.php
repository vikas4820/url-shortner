<?php

namespace App\Services;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ShortUrlService
{
    public function createShortUrl(User $user, string $originalUrl, ?string $expiresAt = null): ShortUrl
    {
        $shortCode = $this->generateUniqueShortCode();

        return ShortUrl::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $originalUrl,
            'short_code' => $shortCode,
            'expires_at' => $expiresAt,
        ]);
    }

    public function generateUniqueShortCode(): string
    {
        do {
            $shortCode = Str::random(8);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        return $shortCode;
    }

    public function getShortUrlsForUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        $query = ShortUrl::with('user', 'company');

        if ($user->hasRole('admin')) {
            $query->where('company_id', '!=', $user->company_id);
        } elseif ($user->hasRole('manager')) {
            $query->where('company_id', $user->company_id);
        } elseif ($user->hasPermissionTo('view_short_url_details')) {
            $query->where('company_id', $user->company_id)
                ->where('user_id', '!=', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getShortUrlByCode(string $shortCode): ?ShortUrl
    {
        return ShortUrl::where('short_code', $shortCode)->first();
    }

    public function incrementClicks(ShortUrl $shortUrl): void
    {
        $shortUrl->increment('clicks');
    }

    public function isExpired(ShortUrl $shortUrl): bool
    {
        return $shortUrl->isExpired();
    }
}
