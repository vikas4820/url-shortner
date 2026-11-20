<?php

namespace Database\Factories;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ShortUrl>
 */
class ShortUrlFactory extends Factory
{
    protected $model = ShortUrl::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'original_url' => fake()->url(),
            'short_code' => $this->generateUniqueShortCode(),
            'clicks' => fake()->numberBetween(0, 1000),
            'expires_at' => fake()->optional(0.3)->dateTimeBetween('now', '+1 year'),
        ];
    }

    private function generateUniqueShortCode(): string
    {
        do {
            $code = Str::random(8);
        } while (ShortUrl::where('short_code', $code)->exists());

        return $code;
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'expires_at' => now()->subDays(1),
        ]);
    }

    public function forCompany($companyId): static
    {
        return $this->state(fn () => [
            'company_id' => $companyId,
        ]);
    }

    public function forUser($userId): static
    {
        return $this->state(fn () => [
            'user_id' => $userId,
        ]);
    }
}
