<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        // This app registers with first_name/last_name and enforces a strong password.
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '0771234567',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home', absolute: false));
    }
}
