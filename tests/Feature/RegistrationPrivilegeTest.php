<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationPrivilegeTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_cannot_self_assign_admin_privileges(): void
    {
        // Attacker injects is_admin/is_active into the registration payload.
        $this->post('/register', [
            'first_name' => 'Mal',
            'last_name' => 'Actor',
            'email' => 'mal@example.com',
            'phone' => '0771112222',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'is_admin' => '1',
            'is_active' => '0',
        ]);

        $user = User::where('email', 'mal@example.com')->first();

        $this->assertNotNull($user);
        $this->assertFalse((bool) $user->is_admin, 'is_admin must not be mass-assignable');
        $this->assertTrue((bool) $user->is_active, 'is_active should default to true');
    }
}
