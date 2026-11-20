<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmAcceptInvitationRequest;
use App\Http\Requests\StoreInvitationRequest;
use App\Models\Invitation;
use App\Services\InvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function __construct(
        protected InvitationService $invitationService
    ) {}

    public function index(): View
    {
        Gate::authorize('viewAny', Invitation::class);

        $user = Auth::user();
        $invitations = $this->invitationService->getCompanyInvitations($user);

        return view('invitations.index', compact('invitations'));
    }

    public function create(): View
    {
        Gate::authorize('create', Invitation::class);

        $user = Auth::user();
        $availableRoles = $this->invitationService->getInvitableRoles($user);

        return view('invitations.create', compact('availableRoles'));
    }

    public function store(StoreInvitationRequest $request): RedirectResponse
    {
        Gate::authorize('create', Invitation::class);

        $invitation = $this->invitationService->createInvitation($request->validated());

        $invitationUrl = route('invitations.accept', ['token' => $invitation->token]);

        return redirect()->route('invitations.index')
            ->with('success', 'Invitation created successfully.')
            ->with('invitation_url', $invitationUrl);
    }

    public function show(Invitation $invitation): View
    {
        Gate::authorize('view', $invitation);

        $invitation = $this->invitationService->getInvitationDetails($invitation, Auth::user());

        return view('invitations.show', compact('invitation'));
    }

    public function accept(string $token): View
    {
        $invitation = $this->invitationService->getInvitationByToken($token);

        if (! $invitation) {
            abort(404, 'Invalid invitation token.');
        }

        if (! $this->invitationService->isInvitationValid($invitation)) {
            if ($this->invitationService->isInvitationAccepted($invitation)) {
                return view('invitations.accepted');
            }

            return view('invitations.expired', compact('invitation'));
        }

        return view('invitations.accept', compact('invitation'));
    }

    public function confirmAccept(ConfirmAcceptInvitationRequest $request, string $token): RedirectResponse
    {
        $invitation = $this->invitationService->getInvitationByToken($token);

        if (! $invitation || ! $this->invitationService->isInvitationValid($invitation)) {
            abort(404, 'Invalid or expired invitation token.');
        }

        $this->invitationService->acceptInvitation($invitation, $request->validated());

        return redirect()->route('login')->with('success', 'Account created successfully. Please log in.');
    }
}
