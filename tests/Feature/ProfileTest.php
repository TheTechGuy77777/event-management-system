<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create([
        'role' => 'event_manager',
        'is_active' => true,
        'is_banned' => false,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create([
        'role' => 'event_manager',
        'is_active' => true,
        'is_banned' => false,
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/dashboard/profile', [
            'name' => 'Test User',
            'email' => $user->email,
            'phone' => '08123456789',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('08123456789', $user->phone);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create([
        'role' => 'event_manager',
        'is_active' => true,
        'is_banned' => false,
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/dashboard/profile', [
            'name' => 'Test User',
            'email' => $user->email,
            'phone' => $user->phone,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create([
        'role' => 'event_manager',
        'is_active' => true,
        'is_banned' => false,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete('/dashboard/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create([
        'role' => 'event_manager',
        'is_active' => true,
        'is_banned' => false,
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/dashboard/profile')
        ->delete('/dashboard/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/dashboard/profile');

    $this->assertNotNull($user->fresh());
});
