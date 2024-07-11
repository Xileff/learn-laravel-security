<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testAuth()
    {
        $this->seed(UserSeeder::class);

        $isAuth = Auth::attempt([
            'email' => 'felix@localhost',
            'password' => 'rahasia'
        ], true);
        self::assertTrue($isAuth);

        $user = Auth::user();
        self::assertNotNull($user);
        self::assertEquals('felix@localhost', $user->email);
    }

    public function testGuest()
    {
        $user = Auth::user();
        self::assertNull($user);
    }
}
