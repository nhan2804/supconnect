<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;
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
Route::resource('todo', TodoController::class);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'App\Http\Controllers\Api\AuthController@login');

Route::prefix('student')->group(function() {
    Route::get('/timetable/user/{user_id}', 'App\Http\Controllers\Api\TimeTableController@timetableUser');
    Route::get('/timetable', 'App\Http\Controllers\Api\TimeTableController@index');

    Route::get('/assignment/list/{student_id}', 'App\Http\Controllers\Api\AssignmentController@studentAssignment');

    Route::get('/assignment/{student_id}', 'App\Http\Controllers\Api\AssignmentController@show');

    Route::get('/announcement', 'App\Http\Controllers\Api\AnnouncementController@index');
    Route::get('/announcement/{id}', 'App\Http\Controllers\Api\AnnouncementController@show');

    Route::get('/grade/{student_id}', 'App\Http\Controllers\Api\StudentController@gradeStudent');

    Route::get('/{account_id}', 'App\Http\Controllers\Api\StudentController@show');
    Route::put('/{account_id}', 'App\Http\Controllers\Api\StudentController@update');

});

Route::get('test', function() {
    echo md5("12345678");
});




