<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DarkModeCookieTest extends TestCase
{
    use RefreshDatabase;

    public function test_dark_mode_cookie_renders_dark_class(): void
    {
        $response = $this->withUnencryptedCookie('dark_mode', 'true')->get('/');
        $response->assertStatus(200);
        $this->assertStringContainsString('class="dark bg-gray-900"', $response->getContent());
        $this->assertStringContainsString('background-color: #111827; color-scheme: dark;', $response->getContent());
    }

    public function test_light_mode_cookie_renders_no_dark_class(): void
    {
        $response = $this->withUnencryptedCookie('dark_mode', 'false')->get('/');
        $response->assertStatus(200);
        $this->assertStringNotContainsString('class="dark bg-gray-900"', $response->getContent());
        $this->assertStringContainsString('class="bg-gray-50"', $response->getContent());
        $this->assertStringContainsString('background-color: #f9fafb; color-scheme: light;', $response->getContent());
    }
}
