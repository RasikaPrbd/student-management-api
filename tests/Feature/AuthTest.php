<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;


class AuthTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_api_hello(): void
    {
        $response = $this->getJson('/api/hello');

        $response->assertStatus(200);
    }

    public function test_protected_route_requires_authentication(): void
    {
        $response = $this->getJson('/api/students');

        $response->assertStatus(401);
    }

    public function test_register_requires_valid_data(): void
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'rasika@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid email or password',
                'data' => null,
            ]);
    }

    public function test_normal_user_cannot_delete_course(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $course = Course::create([
            'name' => 'Test Course',
            'description' => 'Authorization test',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/courses/{$course->id}");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Only admins are allowed',
                'data' => null,
            ]);
    }

    public function test_admin_can_delete_course(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $course = Course::create([
            'name' => 'Admin Test Course',
            'description' => 'Admin authorization test',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Course deleted successfully',
                'data' => null,
            ]);
    }
}
