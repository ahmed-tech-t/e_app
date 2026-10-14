<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\CreatesTestData;

abstract class TestCase extends BaseTestCase
{
    use CreatesTestData;
}
