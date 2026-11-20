<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\User;
use App\Traits\HasInvitableRoles;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvitationService
{
    use HasInvitableRoles;

    public function createInvitation(array $data): Invitation
    {
        $user = Auth::user();
        $availableRoles = $this->getInvitableRoles($user);

        if (! in_array($data['role'], $availableRoles)) {
            throw new \InvalidArgumentException('Invalid role for this user.');
        }

        $existingInvitation = Invitation::where('email', $data['email'])
            ->where('company_id', $user->company_id)
            ->where('accepted_at', null)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            throw new \InvalidArgumentException('An active invitation already exists for this email.');
        }

        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser) {
            throw new \InvalidArgumentException('A user with this email already exists.');
        }

        return Invitation::create([
            'company_id' => $user->company_id,
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => Str::random(64),
            'expires_at' => now()->addHours(24),
            'invited_by_id' => $user->id,
        ]);
    }

    public function getInvitationByToken(string $token): ?Invitation
    {
        return Invitation::where('token', $token)->first();
    }

    public function acceptInvitation(Invitation $invitation, array $data): User
    {
        if (! $invitation->isValid()) {
            throw new \InvalidArgumentException('Invalid or expired invitation token.');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $invitation->email,
            'password' => $data['password'],
            'company_id' => $invitation->company_id,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($invitation->role);
        $invitation->update(['accepted_at' => now()]);

        return $user;
    }

    public function getCompanyInvitations(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return Invitation::where('company_id', $user->company_id)
            ->with('invitedBy')
            ->latest()
            ->paginate($perPage);
    }

    public function getInvitationDetails(Invitation $invitation, User $user): Invitation
    {
        if ($invitation->company_id !== $user->company_id && ! $user->hasRole('super_admin')) {
            abort(403);
        }

        return $invitation->load('invitedBy', 'company');
    }

    public function isInvitationValid(Invitation $invitation): bool
    {
        return $invitation->isValid();
    }

    public function isInvitationAccepted(Invitation $invitation): bool
    {
        return $invitation->isAccepted();
    }

    public function isInvitationExpired(Invitation $invitation): bool
    {
        return $invitation->isExpired();
    }
}
