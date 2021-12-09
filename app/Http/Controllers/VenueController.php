<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $venue = new Venue;
        $venue->code = Str::slug($request->name);
        $venue->name = $request->name;
        $venue->size = $request->size;
        $venue->save();

        return response()->json([
            "message" => "venue record created",
            "slug" => $venue->code,
        ], 201);
    }

    public function all(): JsonResponse
    {
        $venues = Venue::all();
        return response()->json($venues);
    }

    public function read(string $code): JsonResponse
    {
        $venue = Venue::findOrFail($code);

        return response()->json($venue);
    }

    public function update(Request $request, string $code): JsonResponse
    {
        $venue = Venue::findOrFail($code);
        $venue->name = is_null($request->name) ? $venue->name : $request->name;
        $venue->size = is_null($request->size) ? $venue->size : $request->size;
        $venue->availability = is_null($request->availability) ? $venue->availability : $request->availability;
        $venue->save();

        return response()->json(["message" => "record updated successfully"]);
    }

    public function delete(string $code): JsonResponse
    {
        $venue = Venue::findOrFail($code);
        $venue->delete();

        return response()->json(["message" => "record deleted"], 202);
    }
}
