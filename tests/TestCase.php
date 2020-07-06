<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp() : void
    {
        parent::setUp();

        DB::statement('PRAGMA foreign_keys=on');
    }

    protected function signedIn($user = null)
    {
        $user = $user ? : create('App\User');

        $this->actingAs($user);

        return $this;
    }
}
