<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Course;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_students(): void
    {
        $user = User::factory()->create();

        Student::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'age' => 22,
            'phone' => '0771234567',
            'course_id' => null,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/students');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Students retrieved successfully')
            ->assertJsonFragment([
                'name' => 'Test Student',
                'email' => 'student@test.com',
            ]);
    }
    
    public function test_authenticated_user_can_create_student(): void
    {
        $user = User::factory()->create();

        $course = Course::create([
            'name' => 'Test Course',
            'description' => 'Test description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/students', [
                'name' => 'John Doe',
                'email' => 'john@test.com',
                'age' => 22,
                'phone' => '0771234567',
                'course_id' => $course->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Student created successfully')
            ->assertJsonPath('data.name', 'John Doe')
            ->assertJsonPath('data.email', 'john@test.com');
    }

    public function test_authenticated_user_can_update_student(): void
    {
        $user = User::factory()->create();

        $course = Course::create([
            'name' => 'Test Course',
            'description' => 'Test description',
        ]);

        $student = Student::create([
            'name' => 'Old Name',
            'email' => 'old@test.com',
            'age' => 20,
            'phone' => '0771111111',
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/students/{$student->id}", [
                'name' => 'Updated Name',
                'email' => 'updated@test.com',
                'age' => 22,
                'phone' => '0772222222',
                'course_id' => $course->id,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Student updated successfully')
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.email', 'updated@test.com');
    }

    public function test_normal_user_cannot_delete_student(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $student = Student::create([
            'name' => 'Delete Test Student',
            'email' => 'delete@test.com',
            'age' => 22,
            'phone' => '0771234567',
            'course_id' => null,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/students/{$student->id}");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Only admins are allowed',
                'data' => null,
            ]);
    }

    public function test_admin_can_delete_student(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $student = Student::create([
            'name' => 'Admin Delete Student',
            'email' => 'admindelete@test.com',
            'age' => 22,
            'phone' => '0771234567',
            'course_id' => null,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Student deleted successfully',
                'data' => null,
            ]);
    }

    public function test_non_existing_student_returns_404(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/students/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Resource not found',
                'data' => null,
            ]);
    }
}