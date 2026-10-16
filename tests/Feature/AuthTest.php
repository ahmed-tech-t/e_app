<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected bool $authenticateUser = false;

    public function test_guest_is_redirected_to_login_from_guarded_web_endpoints(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/products')->assertRedirect('/login');
        $this->get('/sales')->assertRedirect('/login');
        $this->get('/purchases')->assertRedirect('/login');
        $this->get('/suppliers')->assertRedirect('/login');
        $this->get('/categories')->assertRedirect('/login');
        $this->get('/stock')->assertRedirect('/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee(__('app.auth.sign_in'));
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
    }

    public function test_authenticated_user_cannot_view_login_page(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user, 'web')->get('/login');

        $response->assertRedirect('/');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->createUser([
            'email' => 'cashier@pos.local',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'cashier@pos.local',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_user_can_login_with_remember_me(): void
    {
        $user = $this->createUser([
            'email' => 'manager@pos.local',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'manager@pos.local',
            'password' => 'secret123',
            'remember' => '1',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $this->createUser([
            'email' => 'cashier@pos.local',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'cashier@pos.local',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest('web');
    }

    public function test_user_can_logout(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user, 'web')->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest('web');
    }
}
