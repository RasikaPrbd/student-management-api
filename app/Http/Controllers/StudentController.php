<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    public function hello()
    {
        return response()->json([
            'message' => 'Hello Student API'
        ]);
    }


    public function index(Request $request)
    {
        $name = $request->name;

        $students = Student::where('name', 'LIKE', "%$name%")->paginate(10);

    return StudentResource::collection($students)
        ->additional([
            'success' => true,
            'message' => 'Students retrieved successfully',
        ]);    
    }

    public function store(StoreStudentRequest $request)
    {
        $student = Student::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully',
            'data' => new StudentResource($student),
        ], 201);
    }

    public function show(Student $student)
    {
        return response()->json([
            'success' => true,
            'message' => 'Student retrieved successfully',
            'data' => new StudentResource($student),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully',
            'data' => new StudentResource($student),
        ]);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully',
            'data' => null,
        ]);
    }

}