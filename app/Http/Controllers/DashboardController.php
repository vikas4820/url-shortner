<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $stats = [
            'total_users' => $user->hasPermissionTo('manage_users')
                ? $user->company->users()->count()
                : 0,
            'pending_invitations' => $user->hasPermissionTo('manage_users')
                ? Invitation::where('company_id', $user->company_id)
                    ->where('accepted_at', null)
                    ->where('expires_at', '>', now())
                    ->count()
                : 0,
            'total_short_urls' => match (true) {
                $user->hasPermissionTo('view_all_short_urls') => $user->company->shortUrls()->count(),
                $user->hasPermissionTo('view_short_url_details') => ShortUrl::where('company_id', $user->company_id)
                    ->where('user_id', '!=', $user->id)
                    ->count(),
                default => 0,
            },
        ];

        return view('dashboard', compact('stats', 'user'));
    }
}
