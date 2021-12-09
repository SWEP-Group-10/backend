<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/departments', 'App\Http\Controllers\DepartmentController@create');
Route::get('/departments', 'App\Http\Controllers\DepartmentController@all');
Route::get('/departments/{id}', 'App\Http\Controllers\DepartmentController@read')
    ->where('id', '[0-9]+');
Route::put('/departments/{id}', 'App\Http\Controllers\DepartmentController@update')
    ->where('id', '[0-9]+');
Route::delete('/departments/{id}', 'App\Http\Controllers\DepartmentController@delete')
    ->where('id', '[0-9]+');

Route::post('/venues', 'App\Http\Controllers\VenueController@create');
Route::get('/venues', 'App\Http\Controllers\VenueController@all');
Route::get('/venues/{code}', 'App\Http\Controllers\VenueController@read');
Route::put('/venues/{code}', 'App\Http\Controllers\VenueController@update');
Route::delete('/venues/{code}', 'App\Http\Controllers\VenueController@delete');

Route::post('/courses', 'App\Http\Controllers\CourseController@create');
Route::get('/courses', 'App\Http\Controllers\CourseController@all');
Route::get('/courses/{code}', 'App\Http\Controllers\CourseController@read');
