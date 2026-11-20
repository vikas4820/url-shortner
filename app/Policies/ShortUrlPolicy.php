<?php

namespace App\Policies;

use App\Models\ShortUrl;
use App\Models\User;

class ShortUrlPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('super_admin')) {
            return false;
        }

        return $user->hasPermissionTo('view_all_short_urls')
            || $user->hasPermissionTo('view_short_url_details');
    }

    public function view(User $user, ShortUrl $shortUrl): bool
    {
        if ($user->hasRole('super_admin')) {
            return false;
        }

        // Admin: can view short URLs from OTHER companies only
        if ($user->hasRole('admin')) {
            return $shortUrl->company_id !== $user->company_id;
        }

        // Manager: can view ALL short URLs in their own company
        if ($user->hasRole('manager')) {
            return $shortUrl->company_id === $user->company_id;
        }

        // Sales & Member: can view short URLs in their company NOT created by themselves
        if ($user->hasPermissionTo('view_short_url_details')) {
            if ($shortUrl->company_id !== $user->company_id) {
                return false;
            }

            return $shortUrl->user_id !== $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_short_url');
    }

    public function update(User $user, ShortUrl $shortUrl): bool
    {
        if ($user->hasRole('super_admin')) {
            return false;
        }

        if ($user->hasPermissionTo('create_short_url')) {
            return $shortUrl->user_id === $user->id && $shortUrl->company_id === $user->company_id;
        }

        return false;
    }

    public function delete(User $user, ShortUrl $shortUrl): bool
    {
        if ($user->hasRole('super_admin')) {
            return false;
        }

        if ($user->hasPermissionTo('create_short_url')) {
            return $shortUrl->user_id === $user->id && $shortUrl->company_id === $user->company_id;
        }

        return false;
    }

    public function redirect(User $user, ShortUrl $shortUrl): bool
    {
        if ($shortUrl->isExpired()) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return false;
        }

        // Admin: can redirect short URLs from OTHER companies only
        if ($user->hasRole('admin')) {
            return $shortUrl->company_id !== $user->company_id;
        }

        // Manager: can redirect ALL short URLs in their own company
        if ($user->hasRole('manager')) {
            return $shortUrl->company_id === $user->company_id;
        }

        // Sales & Member: can redirect short URLs in their company NOT created by themselves
        if ($user->hasPermissionTo('access_short_url_redirect')) {
            if ($shortUrl->company_id !== $user->company_id) {
                return false;
            }

            return $shortUrl->user_id !== $user->id;
        }

        return false;
    }
}
