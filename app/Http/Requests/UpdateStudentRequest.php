<?php

namespace App\Http\Requests;

class UpdateStudentRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'age' => 'required|integer',
            'phone' => 'required',
            'course_id' => 'required|exists:courses,id',
        ];
    }
}