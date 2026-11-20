<?php

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses()->group('short-urls');

test('super_admin cannot create short urls', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $response = $this->actingAs($superAdmin)->post(route('short-urls.store'), [
        'original_url' => 'https://example.com',
    ]);

    $response->assertStatus(403);
});

test('admin cannot create short urls', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('short-urls.store'), [
        'original_url' => 'https://example.com',
    ]);

    $response->assertStatus(403);
});

test('member cannot create short urls', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company-2'], ['name' => 'Test Company 2']);
    $member = User::create([
        'name' => 'Member',
        'email' => 'member@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $member->assignRole('member');

    $response = $this->actingAs($member)->post(route('short-urls.store'), [
        'original_url' => 'https://example.com',
    ]);

    $response->assertStatus(403);
});

test('sales can create short urls', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company-3'], ['name' => 'Test Company 3']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $response = $this->actingAs($sales)->post(route('short-urls.store'), [
        'original_url' => 'https://example.com',
    ]);

    $response->assertRedirect();
    $shortUrl = ShortUrl::where('original_url', 'https://example.com')->first();
    expect($shortUrl)->not->toBeNull();
    expect($shortUrl->user_id)->toBe($sales->id);
    expect($shortUrl->company_id)->toBe($company->id);
    expect($shortUrl->short_code)->toHaveLength(8);
});

test('super_admin cannot view global short url list', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $response = $this->actingAs($superAdmin)->get(route('short-urls.index'));
    $response->assertStatus(403);
});

test('admin sees only short urls from other companies', function () {
    $company1 = Company::firstOrCreate(['slug' => 'company-1'], ['name' => 'Company 1']);
    $company2 = Company::firstOrCreate(['slug' => 'company-2'], ['name' => 'Company 2']);

    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company1->id,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('admin');

    $otherUser = User::create([
        'name' => 'Other User',
        'email' => 'other@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company2->id,
        'email_verified_at' => now(),
    ]);
    $otherUser->assignRole('member');

    $ownShortUrl = ShortUrl::create([
        'company_id' => $company1->id,
        'user_id' => $admin->id,
        'original_url' => 'https://own-company.com',
        'short_code' => 'own12345',
    ]);

    $otherShortUrl = ShortUrl::create([
        'company_id' => $company2->id,
        'user_id' => $otherUser->id,
        'original_url' => 'https://other-company.com',
        'short_code' => 'other12345',
    ]);

    $response = $this->actingAs($admin)->get(route('short-urls.index'));
    $response->assertStatus(200);
    $response->assertDontSee('own12345');
    $response->assertSee('other12345');
});

test('member sees only short urls not created by themselves', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);

    $member = User::create([
        'name' => 'Member',
        'email' => 'member@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $member->assignRole('member');

    $otherUser = User::create([
        'name' => 'Other Member',
        'email' => 'other-member@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $otherUser->assignRole('member');

    $ownShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $member->id,
        'original_url' => 'https://own-url.com',
        'short_code' => 'ownmem12',
    ]);

    $otherShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $otherUser->id,
        'original_url' => 'https://other-url.com',
        'short_code' => 'othmem12',
    ]);

    $response = $this->actingAs($member)->get(route('short-urls.index'));
    $response->assertStatus(200);
    $response->assertDontSee('ownmem12');
    $response->assertSee('othmem12');
});

test('unauthorized users cannot access other company short urls', function () {
    $company1 = Company::firstOrCreate(['slug' => 'company-1'], ['name' => 'Company 1']);
    $company2 = Company::firstOrCreate(['slug' => 'company-2'], ['name' => 'Company 2']);

    $member1 = User::create([
        'name' => 'Member 1',
        'email' => 'member1@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company1->id,
        'email_verified_at' => now(),
    ]);
    $member1->assignRole('member');

    $member2 = User::create([
        'name' => 'Member 2',
        'email' => 'member2@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company2->id,
        'email_verified_at' => now(),
    ]);
    $member2->assignRole('member');

    $shortUrl = ShortUrl::create([
        'company_id' => $company2->id,
        'user_id' => $member2->id,
        'original_url' => 'https://company2-url.com',
        'short_code' => 'comp2url',
    ]);

    $response = $this->actingAs($member1)->get(route('short-urls.show', $shortUrl));
    $response->assertStatus(403);
});

test('short url redirect requires authentication', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $user = User::create([
        'name' => 'User',
        'email' => 'user@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('member');

    $shortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'original_url' => 'https://example.com',
        'short_code' => 'testcode',
    ]);

    $response = $this->get(route('short-urls.redirect', ['shortCode' => $shortUrl->short_code]));
    $response->assertRedirect(route('login'));
});

test('super_admin cannot redirect short urls', function () {
    $company = Company::firstOrCreate(['slug' => 'system'], ['name' => 'System']);
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $superAdmin->assignRole('super_admin');

    $otherUser = User::create([
        'name' => 'Other User',
        'email' => 'other@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $otherUser->assignRole('member');

    $shortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $otherUser->id,
        'original_url' => 'https://example.com',
        'short_code' => 'testcode',
    ]);

    $response = $this->actingAs($superAdmin)->get(route('short-urls.redirect', ['shortCode' => $shortUrl->short_code]));
    $response->assertStatus(403);
});

test('admin cannot redirect own company short urls', function () {
    $company1 = Company::firstOrCreate(['slug' => 'company-1'], ['name' => 'Company 1']);
    $company2 = Company::firstOrCreate(['slug' => 'company-2'], ['name' => 'Company 2']);

    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company1->id,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('admin');

    $ownShortUrl = ShortUrl::create([
        'company_id' => $company1->id,
        'user_id' => $admin->id,
        'original_url' => 'https://own-company.com',
        'short_code' => 'own12345',
    ]);

    $otherUser = User::create([
        'name' => 'Other User',
        'email' => 'other@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company2->id,
        'email_verified_at' => now(),
    ]);
    $otherUser->assignRole('member');

    $otherShortUrl = ShortUrl::create([
        'company_id' => $company2->id,
        'user_id' => $otherUser->id,
        'original_url' => 'https://other-company.com',
        'short_code' => 'other12345',
    ]);

    $response = $this->actingAs($admin)->get(route('short-urls.redirect', ['shortCode' => $ownShortUrl->short_code]));
    $response->assertStatus(403);

    $response = $this->actingAs($admin)->get(route('short-urls.redirect', ['shortCode' => $otherShortUrl->short_code]));
    $response->assertStatus(302); // Should redirect to original URL
    $response->assertRedirect('https://other-company.com');
});

test('member cannot redirect own short urls', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);

    $member = User::create([
        'name' => 'Member',
        'email' => 'member@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $member->assignRole('member');

    $otherUser = User::create([
        'name' => 'Other Member',
        'email' => 'other-member@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $otherUser->assignRole('member');

    $ownShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $member->id,
        'original_url' => 'https://own-url.com',
        'short_code' => 'ownmem12',
    ]);

    $otherShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $otherUser->id,
        'original_url' => 'https://other-url.com',
        'short_code' => 'othmem12',
    ]);

    $response = $this->actingAs($member)->get(route('short-urls.redirect', ['shortCode' => $ownShortUrl->short_code]));
    $response->assertStatus(403);

    $response = $this->actingAs($member)->get(route('short-urls.redirect', ['shortCode' => $otherShortUrl->short_code]));
    $response->assertStatus(302);
    $response->assertRedirect('https://other-url.com');
});

test('expired short url cannot be redirected', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $user = User::create([
        'name' => 'User',
        'email' => 'user@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $user->assignRole('member');

    $otherUser = User::create([
        'name' => 'Other User',
        'email' => 'other@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $otherUser->assignRole('member');

    $shortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $otherUser->id,
        'original_url' => 'https://example.com',
        'short_code' => 'expired12',
        'expires_at' => now()->subDays(1),
    ]);

    $response = $this->actingAs($user)->get(route('short-urls.redirect', ['shortCode' => $shortUrl->short_code]));
    $response->assertStatus(403);
});

test('short code is unique', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $shortUrl1 = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $sales->id,
        'original_url' => 'https://example1.com',
        'short_code' => 'unique01',
    ]);

    $shortUrl2 = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $sales->id,
        'original_url' => 'https://example2.com',
        'short_code' => 'unique02',
    ]);

    expect($shortUrl1->short_code)->not->toBe($shortUrl2->short_code);
    expect(ShortUrl::where('short_code', 'unique01')->count())->toBe(1);
    expect(ShortUrl::where('short_code', 'unique02')->count())->toBe(1);
});

test('manager can view all short urls in their company', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);

    $manager = User::create([
        'name' => 'Manager',
        'email' => 'manager@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $manager->assignRole('manager');

    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $ownShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $manager->id,
        'original_url' => 'https://manager-url.com',
        'short_code' => 'mgr12345',
    ]);

    $salesShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $sales->id,
        'original_url' => 'https://sales-url.com',
        'short_code' => 'sales123',
    ]);

    $response = $this->actingAs($manager)->get(route('short-urls.index'));
    $response->assertStatus(200);
    $response->assertSee('mgr12345');
    $response->assertSee('sales123');
});

test('sales can view other member short urls but not own', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);

    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $otherSales = User::create([
        'name' => 'Other Sales',
        'email' => 'other-sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $otherSales->assignRole('sales');

    $ownShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $sales->id,
        'original_url' => 'https://own-sales.com',
        'short_code' => 'ownsale1',
    ]);

    $otherShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $otherSales->id,
        'original_url' => 'https://other-sales.com',
        'short_code' => 'othsale1',
    ]);

    $response = $this->actingAs($sales)->get(route('short-urls.index'));
    $response->assertStatus(200);
    $response->assertDontSee('ownsale1');
    $response->assertSee('othsale1');
});

test('pagination works correctly', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $otherUser = User::create([
        'name' => 'Other User',
        'email' => 'other@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $otherUser->assignRole('sales');

    for ($i = 1; $i <= 20; $i++) {
        ShortUrl::create([
            'company_id' => $company->id,
            'user_id' => $otherUser->id,
            'original_url' => "https://example{$i}.com",
            'short_code' => "code{$i}",
        ]);
    }

    $response = $this->actingAs($sales)->get(route('short-urls.index'));
    $response->assertStatus(200);
    $response->assertSee('code1');
    $response->assertSee('code15');
    $response->assertSee('page=2'); // Laravel pagination links
});

test('empty state shows when no short urls available', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $response = $this->actingAs($sales)->get(route('short-urls.index'));
    $response->assertStatus(200);
    $response->assertSee('No short URLs found.');
});

test('unsafe url schemes are rejected', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $unsafeUrls = [
        'javascript:alert(1)',
        'data:text/html,<script>alert(1)</script>',
        'ftp://ftp.example.com/file.txt',
        'file:///etc/passwd',
        'mailto:test@example.com',
    ];

    foreach ($unsafeUrls as $url) {
        $response = $this->actingAs($sales)->post(route('short-urls.store'), [
            'original_url' => $url,
        ]);
        $response->assertSessionHasErrors('original_url');
    }
});

test('valid http and https urls are accepted', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $validUrls = [
        'http://example.com',
        'https://example.com',
        'https://sub.domain.com/path?query=value',
        'http://localhost:8000',
    ];

    foreach ($validUrls as $url) {
        $response = $this->actingAs($sales)->post(route('short-urls.store'), [
            'original_url' => $url,
        ]);
        $response->assertRedirect();
    }
});

test('click tracking increments on successful redirect', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $otherUser = User::create([
        'name' => 'Other User',
        'email' => 'other@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $otherUser->assignRole('sales');

    $shortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $otherUser->id,
        'original_url' => 'https://example.com',
        'short_code' => 'track123',
    ]);

    expect($shortUrl->clicks)->toBe(0);

    $response = $this->actingAs($sales)->get(route('short-urls.redirect', ['shortCode' => $shortUrl->short_code]));
    $response->assertStatus(302);

    $shortUrl->refresh();
    expect($shortUrl->clicks)->toBe(1);
});

test('manager can redirect any short url in their company', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);

    $manager = User::create([
        'name' => 'Manager',
        'email' => 'manager@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $manager->assignRole('manager');

    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $ownShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $manager->id,
        'original_url' => 'https://manager-url.com',
        'short_code' => 'mgr12345',
    ]);

    $salesShortUrl = ShortUrl::create([
        'company_id' => $company->id,
        'user_id' => $sales->id,
        'original_url' => 'https://sales-url.com',
        'short_code' => 'sales123',
    ]);

    $response = $this->actingAs($manager)->get(route('short-urls.redirect', ['shortCode' => $ownShortUrl->short_code]));
    $response->assertStatus(302);
    $response->assertRedirect('https://manager-url.com');

    $response = $this->actingAs($manager)->get(route('short-urls.redirect', ['shortCode' => $salesShortUrl->short_code]));
    $response->assertStatus(302);
    $response->assertRedirect('https://sales-url.com');
});

test('admin can redirect other company short urls', function () {
    $company1 = Company::firstOrCreate(['slug' => 'company-1'], ['name' => 'Company 1']);
    $company2 = Company::firstOrCreate(['slug' => 'company-2'], ['name' => 'Company 2']);

    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company1->id,
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('admin');

    $otherUser = User::create([
        'name' => 'Other User',
        'email' => 'other@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company2->id,
        'email_verified_at' => now(),
    ]);
    $otherUser->assignRole('member');

    $otherShortUrl = ShortUrl::create([
        'company_id' => $company2->id,
        'user_id' => $otherUser->id,
        'original_url' => 'https://other-company.com',
        'short_code' => 'other12345',
    ]);

    $response = $this->actingAs($admin)->get(route('short-urls.redirect', ['shortCode' => $otherShortUrl->short_code]));
    $response->assertStatus(302);
    $response->assertRedirect('https://other-company.com');
});

test('idors are prevented for short url show', function () {
    $company1 = Company::firstOrCreate(['slug' => 'company-1'], ['name' => 'Company 1']);
    $company2 = Company::firstOrCreate(['slug' => 'company-2'], ['name' => 'Company 2']);

    $member1 = User::create([
        'name' => 'Member 1',
        'email' => 'member1@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company1->id,
        'email_verified_at' => now(),
    ]);
    $member1->assignRole('member');

    $member2 = User::create([
        'name' => 'Member 2',
        'email' => 'member2@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company2->id,
        'email_verified_at' => now(),
    ]);
    $member2->assignRole('member');

    $shortUrl = ShortUrl::create([
        'company_id' => $company2->id,
        'user_id' => $member2->id,
        'original_url' => 'https://company2-url.com',
        'short_code' => 'comp2url',
    ]);

    $response = $this->actingAs($member1)->get(route('short-urls.show', $shortUrl));
    $response->assertStatus(403);
});

test('non-existent short url returns 404 on redirect', function () {
    $company = Company::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);
    $sales = User::create([
        'name' => 'Sales',
        'email' => 'sales@test.com',
        'password' => Hash::make('password123'),
        'company_id' => $company->id,
        'email_verified_at' => now(),
    ]);
    $sales->assignRole('sales');

    $response = $this->actingAs($sales)->get(route('short-urls.redirect', ['shortCode' => 'nonexist']));
    $response->assertStatus(404);
});
