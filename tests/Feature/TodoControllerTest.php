<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\TodoSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    public function testTodo()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $this->post('/api/todos')->assertStatus(403);

        $user = User::where('email', 'felix@localhost')->first();
        $this->actingAs($user)->post('/api/todos')->assertStatus(200);
    }
}
