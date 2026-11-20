<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortUrlRequest;
use App\Models\ShortUrl;
use App\Services\ShortUrlService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ShortUrlController extends Controller
{
    public function __construct(
        protected ShortUrlService $shortUrlService
    ) {}

    public function index(): View
    {
        Gate::authorize('viewAny', ShortUrl::class);

        $shortUrls = $this->shortUrlService->getShortUrlsForUser(Auth::user());

        return view('short-urls.index', compact('shortUrls'));
    }

    public function create(): View
    {
        Gate::authorize('create', ShortUrl::class);

        return view('short-urls.create');
    }

    public function store(StoreShortUrlRequest $request): RedirectResponse
    {
        Gate::authorize('create', ShortUrl::class);

        $this->shortUrlService->createShortUrl(
            Auth::user(),
            $request->validated('original_url'),
            $request->validated('expires_at')
        );

        return redirect()->route('short-urls.index')
            ->with('success', 'Short URL created successfully.');
    }

    public function show(ShortUrl $shortUrl): View
    {
        Gate::authorize('view', $shortUrl);

        $shortUrl->load('user', 'company');

        return view('short-urls.show', compact('shortUrl'));
    }

    public function redirect(string $shortCode): RedirectResponse
    {
        $shortUrl = $this->shortUrlService->getShortUrlByCode($shortCode);

        if (! $shortUrl) {
            abort(404, 'Short URL not found.');
        }

        Gate::authorize('redirect', $shortUrl);

        if ($this->shortUrlService->isExpired($shortUrl)) {
            abort(403, 'Short URL has expired.');
        }

        $this->shortUrlService->incrementClicks($shortUrl);

        return redirect()->away($shortUrl->original_url);
    }
}
