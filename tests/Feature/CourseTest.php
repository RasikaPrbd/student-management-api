<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Student;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_courses(): void
    {
        $user = User::factory()->create();

        Course::create([
            'name' => 'Test Course',
            'description' => 'Test description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/courses');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Courses retrieved successfully')
            ->assertJsonFragment([
                'name' => 'Test Course',
                'description' => 'Test description',
            ]);
    }

    public function test_authenticated_user_can_create_course(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/courses', [
                'name' => 'Software Engineering',
                'description' => 'Test course',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Course created successfully')
            ->assertJsonPath('data.name', 'Software Engineering')
            ->assertJsonPath('data.description', 'Test course');
    }

    public function test_authenticated_user_can_update_course(): void
    {
        $user = User::factory()->create();

        $course = Course::create([
            'name' => 'Old Course',
            'description' => 'Old description',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/courses/{$course->id}", [
                'name' => 'Updated Course',
                'description' => 'Updated description',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Course updated successfully')
            ->assertJsonPath('data.name', 'Updated Course')
            ->assertJsonPath('data.description', 'Updated description');
    }

    public function test_authenticated_user_can_get_single_course(): void
    {
        $user = User::factory()->create();

        $course = Course::create([
            'name' => 'Information Technology',
            'description' => 'IT course',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Course retrieved successfully')
            ->assertJsonPath('data.id', $course->id)
            ->assertJsonPath('data.name', 'Information Technology');
    }

    public function test_authenticated_user_can_get_course_students(): void
    {
        $user = User::factory()->create();

        $course = Course::create([
            'name' => 'Information Technology',
            'description' => 'IT course',
        ]);

        Student::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'age' => 22,
            'phone' => '0771234567',
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/courses/{$course->id}/students");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Course students retrieved successfully')
            ->assertJsonFragment([
                'name' => 'Test Student',
                'email' => 'student@test.com',
            ]);
    }

    public function test_non_existing_course_returns_404(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/courses/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Course not found',
                'data' => null,
            ]);
    }
}