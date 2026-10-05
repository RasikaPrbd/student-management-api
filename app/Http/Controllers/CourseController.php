<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use App\Http\Resources\CourseResource;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully',
            'data' => new CourseResource($course),
        ], 201);
    }

    public function students($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
                'data' => null,
            ], 404);
        }

        return StudentResource::collection($course->students)
            ->additional([
                'success' => true,
                'message' => 'Course students retrieved successfully',
            ]);
    }

    public function show($id)
    {
        $course = Course::with('students')->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course retrieved successfully',
            'data' => new CourseResource($course),
        ]);
    }

    public function index()
    {
        $courses = Course::all();

        return CourseResource::collection($courses)
            ->additional([
                'success' => true,
                'message' => 'Courses retrieved successfully',
            ]);
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
                'data' => null,
            ], 404);
        }

        $course->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully',
            'data' => new CourseResource($course),
        ]);
    }

    public function destroy(Request $request, Course $course)
    {
        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully',
            'data' => null,
        ]);
    }
}
