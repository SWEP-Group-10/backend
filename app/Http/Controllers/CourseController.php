<?php

namespace App\Http\Controllers;

use App\Domain\TimetableGenerator;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(private TimetableGenerator $generator)
    {
    }

    public function create(Request $request): JsonResponse
    {
        $departments = $request->post('departments');
        $preferredDays = $request->post('preferred_days');

        if (empty($departments)) {
            return response()->json(['message' => 'department list cannot be empty'], 400);
        }

        if (empty($preferredDays)) {
            return response()->json(['message' => 'preferred days cannot be empty'], 400);
        }

        $course = $this->generator->generate(
            $request->code,
            $request->student_count,
            $request->get('departments'),
            $request->get('preferred_days')
        );

        return response()->json([
            'message' => 'timetable generated for course',
            'course' => $course->toArray(),
        ], 201);
    }

    public function all(): JsonResponse
    {
        $courses = Course::all();
        return response()->json($courses);
    }

    public function read(string $code): JsonResponse
    {
        $course = Course::findOrFail($code);
        return response()->json($course);
    }

    public function delete(string $code): JsonResponse
    {
        $course = Course::findOrFail($code);
        $course->delete();

        return response()->json(["message" => "record deleted"], 202);
    }
}
