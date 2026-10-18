<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\CreatesTestData;

abstract class TestCase extends BaseTestCase
{
    use CreatesTestData;

    protected bool $authenticateUser = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        if ($this->authenticateUser) {
            try {
                $user = $this->createUser();
                $this->actingAs($user, 'web');
            } catch (\Throwable) {
                // Ignore if database is not migrated yet
            }
        }
    }
}
