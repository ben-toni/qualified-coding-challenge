<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;

mutates(\App\Http\Controllers\AccountController::class);

describe('Users', function () {
    test('Can access the edit page', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->get(route('account.edit'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Account/Edit')
                    ->has('user')
                    ->where('user.first_name', $user->first_name)
                    ->where('user.last_name', $user->last_name)
                    ->where('user.email', $user->email)
            );
    });

    test('Can update their details', function () {
        $user = User::factory()->create([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'password' => fake()->password(),
        ]);

        actingAs($user)
            ->fromRoute('account.edit')
            ->patch(route('account.update'), $newData = [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->safeEmail(),
                'password' => 'newPassword123#',
            ])
            ->assertSessionDoesntHaveErrors()
            ->assertSessionHas('success', __('account.updated'))
            ->assertRedirectToRoute('account.edit');

        expect($user->refresh())
            ->first_name->toBe($newData['first_name'])
            ->last_name->toBe($newData['last_name'])
            ->email->toBe($newData['email']);

        expect(Hash::check($newData['password'], $user->password))->toBeTrue();
    });

    test("Can't update their email to one that already exists", function () {
        $user1 = User::factory()->create([
            'email' => fake()->email(),
        ]);

        $oldEmail = fake()->email();

        $user2 = User::factory()->create([
            'email' => $oldEmail,
        ]);

        actingAs($user2)
            ->fromRoute('account.edit')
            ->patch(route('account.update'), [
                'email' => $user1->email,
            ])
            ->assertSessionHasErrors([
                'email' => __('validation.unique', ['attribute' => 'email']),
            ])
            ->assertRedirectToRoute('account.edit');

        expect($user2->refresh()->email)->toBe($oldEmail);
    });
});

describe('Guests', function () {
    test("Can't access the edit page", function () {
        get(route('account.edit'))
            ->assertRedirectToRoute('login');
    });

    test("Can't update details", function () {
        patch(route('account.update'))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('login');
    });
});
