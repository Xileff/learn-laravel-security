<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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

        // $this->get('/users/current')
        //     ->assertSeeText('Hello Guest');
        $this->get('/users/current')
            ->assertStatus(302)
            ->assertRedirect('/login');

        $user = User::where('email', 'felix@localhost')->firstOrFail();
        $this->actingAs($user)
            ->get('/users/current')
            ->assertSeeText('Hello felix');
    }

    public function testTokenGuard()
    {
        $this->seed([UserSeeder::class]);

        $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/users/current')
            ->assertStatus(401);

        $this->withHeaders(['API-Key' => 'secret'])
            ->get('/api/users/current')
            ->assertSeeText('Hello felix');
    }

    public function testUserProvider()
    {
        $this->seed([UserSeeder::class]);

        $this->withHeaders(['Accept' => 'application/json'])
            ->get('/simple-api/users/current')
            ->assertStatus(401);

        $this->withHeaders(['API-Key' => 'secret'])
            ->get('/simple-api/users/current')
            ->assertSeeText('Hello felix');
    }
}
