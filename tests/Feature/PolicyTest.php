<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Database\Seeders\TodoSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    public function testPolicy()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $user = User::where('email', 'felix@localhost')->first();
        $todo = Todo::first();
        Auth::login($user);

        self::assertTrue(Gate::allows('view', $todo));
        self::assertTrue(Gate::allows('update', $todo));
        self::assertTrue(Gate::allows('delete', $todo));
        self::assertTrue(Gate::allows('create', Todo::class));
    }
}
