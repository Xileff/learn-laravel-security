<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

        // Session::regenerate();

        $user = Auth::user();
        self::assertNotNull($user);
        self::assertEquals('felix@localhost', $user->email);
    }

    public function testGuest()
    {
        $user = Auth::user();
        self::assertNull($user);
    }

    public function testLogin()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/users/login?email=felix@localhost&password=rahasia')
            ->assertRedirect('/users/current');

        $this->get('/users/login?email=salah@localhost&password=rahasia')
            ->assertSeeText('Wrong credentials');
    }

    public function testCurrent()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/users/current')
            ->assertSeeText('Hello Guest');

        $user = User::where('email', 'felix@localhost')->firstOrFail();
        $this->actingAs($user)
            ->get('/users/current')
            ->assertSeeText('Hello felix');
    }
}
