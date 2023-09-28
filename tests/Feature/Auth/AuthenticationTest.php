<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create([
            'name' => 'superAdmin',
            'email' => 'superadmin@admin.com',
            'password' => bcrypt('12345678'),
            'userType' => 'superadmin',
            'userStatus' => 'active'
        ]);

        $response = $this->post('/login', [
            'email' => "superadmin@admin.com",
            'password' => '12345678',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => "user@admin.com",
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
