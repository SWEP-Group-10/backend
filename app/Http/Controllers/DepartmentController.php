<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $dept = new Department;
        $dept->name = $request->name;
        $dept->save();

        return response()->json(["message" => "department record created"], 201);
    }

    public function all(): JsonResponse
    {
        $departments = Department::all();
        return response()->json($departments);
    }

    public function read(int $id): JsonResponse
    {
        $dept = Department::findOrFail($id);
        return response()->json($dept);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $dept = Department::findOrFail($id);
        $dept->name = is_null($request->name) ? $dept->name : $request->name;
        $dept->save();

        return response()->json(["message" => "record updated successfully"]);
    }

    public function delete(int $id): JsonResponse
    {
        $dept = Department::findOrFail($id);
        $dept->delete();;

        return response()->json(["message" => "record deleted"], 202);
    }
}
