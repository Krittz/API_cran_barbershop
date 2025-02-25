<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function it_creates_a_user_successfully()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '11999999999',
            'password' => 'password123',
            'password_confirmation' => "password123",
            'role' => 'user'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Usuarios criado com sucesso',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function it_fails_to_create_a_user_with_invalid_data()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function it_updates_a_user_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $updateData = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Dados atualizados com sucesso',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    /** @test */
    public function it_fails_to_update_another_users_data()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($user);

        $updateData = ['name' => 'Hacker Name'];

        $response = $this->putJson("/api/users/{$otherUser->id}", $updateData);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_deletes_a_user_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Usuário desativado com sucesso.',
            ]);

        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function it_fails_to_delete_another_users_account()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($user);

        $response = $this->deleteJson("/api/users/{$otherUser->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function it_lists_users_successfully()
    {
        $admin = User::factory()->create(['role' => 'adm']);
        $this->actingAs($admin);

        User::factory(5)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['data' => []],
            ]);
    }

    /** @test */
    public function it_fails_to_list_users_without_permission()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->getJson('/api/users');

        $response->assertStatus(403);
    }
}
