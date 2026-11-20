<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InvitationSeeder extends Seeder
{
    public function run(): void
    {
        $acmeCompany = Company::where('slug', 'acme')->firstOrFail();
        $globalCompany = Company::where('slug', 'global-tech')->firstOrFail();
        $startupCompany = Company::where('slug', 'startup-xyz')->firstOrFail();

        $adminAcme = User::where('email', 'admin@acme.com')->firstOrFail();
        $managerAcme = User::where('email', 'manager@acme.com')->firstOrFail();
        $adminGlobal = User::where('email', 'admin@global.com')->firstOrFail();
        $adminStartup = User::where('email', 'admin@startup.com')->firstOrFail();

        $invitations = [
            // Acme invitations
            [
                'company_id' => $acmeCompany->id,
                'email' => 'new.sales@acme.com',
                'role' => 'sales',
                'token' => 'invite-acme-new-sales-001',
                'expires_at' => now()->addHours(24),
                'invited_by_id' => $adminAcme->id,
            ],
            [
                'company_id' => $acmeCompany->id,
                'email' => 'new.member@acme.com',
                'role' => 'member',
                'token' => 'invite-acme-new-member-002',
                'expires_at' => now()->addHours(24),
                'invited_by_id' => $adminAcme->id,
            ],
            [
                'company_id' => $acmeCompany->id,
                'email' => 'pending.manager@acme.com',
                'role' => 'manager',
                'token' => 'invite-acme-pending-manager-003',
                'expires_at' => now()->addHours(24),
                'invited_by_id' => $adminAcme->id,
            ],
            // Expired invitation
            [
                'company_id' => $acmeCompany->id,
                'email' => 'expired@acme.com',
                'role' => 'sales',
                'token' => 'invite-acme-expired-004',
                'expires_at' => now()->subHours(1),
                'invited_by_id' => $managerAcme->id,
            ],
            // Accepted invitation (user created)
            [
                'company_id' => $acmeCompany->id,
                'email' => 'accepted@acme.com',
                'role' => 'member',
                'token' => 'invite-acme-accepted-005',
                'expires_at' => now()->addHours(24),
                'invited_by_id' => $adminAcme->id,
                'accepted_at' => now()->subDay(),
            ],

            // Global Tech invitations
            [
                'company_id' => $globalCompany->id,
                'email' => 'new.sales@global.com',
                'role' => 'sales',
                'token' => 'invite-global-new-sales-006',
                'expires_at' => now()->addHours(24),
                'invited_by_id' => $adminGlobal->id,
            ],
            [
                'company_id' => $globalCompany->id,
                'email' => 'new.member@global.com',
                'role' => 'member',
                'token' => 'invite-global-new-member-007',
                'expires_at' => now()->addHours(24),
                'invited_by_id' => $adminGlobal->id,
            ],

            // Startup invitations
            [
                'company_id' => $startupCompany->id,
                'email' => 'new.sales@startup.com',
                'role' => 'sales',
                'token' => 'invite-startup-new-sales-008',
                'expires_at' => now()->addHours(24),
                'invited_by_id' => $adminStartup->id,
            ],
        ];

        foreach ($invitations as $invitation) {
            Invitation::firstOrCreate(
                ['email' => $invitation['email'], 'company_id' => $invitation['company_id']],
                $invitation
            );
        }

        $this->command->info('Invitations seeded: ' . count($invitations));
    }
}