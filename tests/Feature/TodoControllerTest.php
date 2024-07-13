<?php

namespace Tests\Feature;

use App\Models\Todo;
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

    public function testView()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $user = User::where('email', 'felix@localhost')->first();
        $todos = Todo::get();

        // Sudah login dan mmg punya akses ke todos
        $this->actingAs($user)
            ->view('todos', [
                'todos' => $todos
            ])
            ->assertSeeText('Edit')
            ->assertSeeText('Delete')
            ->assertDontSeeText('No Edit')
            ->assertDontSeeText('No Delete');
    }

    public function testViewGuest()
    {
        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $todos = Todo::get();

        // Belum login dan ga punya akses utk edit atau hapus
        $this->view('todos', [
            'todos' => $todos
        ])
            ->assertSeeText('No Edit')
            ->assertSeeText('No Delete');
    }
}
