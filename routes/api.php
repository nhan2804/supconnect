<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\Chat\ChatController;
use App\Http\Controllers\Api\Chat\ChatDetailController;
use App\Http\Controllers\Api\Target\TargetController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\Record\RecordController;
use App\Http\Controllers\Api\Lecturer\SubjecClassController;
use App\Http\Controllers\Api\Lecturer\LecturerController;
use App\Http\Controllers\Api\Lecturer\AnnouncementController;

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

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

Route::prefix('lecturer')->group(function () {
    Route::resource('/', LecturerController::class);
    Route::resource('subject-class', SubjecClassController::class);
    Route::put('subject-class/edit-record/{id}', [SubjecClassController::class, 'edit_record']);
    Route::resource('announcement', AnnouncementController::class);
});
// Chat
Route::resource('todo', TodoController::class);
Route::resource('chat', ChatController::class);
Route::resource('chat-details', ChatDetailController::class);
Route::resource('target', TargetController::class);
Route::resource('payment', PaymentController::class);
// extra route to get details of the paymet
Route::get('paymentdetail', [PaymentController::class, 'detail']);

Route::resource('record', RecordController::class);

Route::resource('lecturer', LecturerController::class);
//endchat
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
Route::get('/logout', 'App\Http\Controllers\Api\AuthController@logout');

Route::prefix('student')->group(function () {
    Route::get('/timetable/user/{user_id}', 'App\Http\Controllers\Api\TimeTableController@timetableUser');
    Route::get('/timetable', 'App\Http\Controllers\Api\TimeTableController@index');

    Route::get('/assignment/list/{student_id}', 'App\Http\Controllers\Api\AssignmentController@studentAssignment');
    Route::get('/assignment/{student_id}', 'App\Http\Controllers\Api\AssignmentController@show');

    Route::get('/subjects/{student_id}', 'App\Http\Controllers\Api\StudentController@getSubject');

    Route::get('/card/{id}', 'App\Http\Controllers\Api\StudentController@cardID');

    Route::get('/announcement', 'App\Http\Controllers\Api\AnnouncementController@index');
    Route::get('/announcement/{id}', 'App\Http\Controllers\Api\AnnouncementController@show');

    Route::get('/grade/{student_id}', 'App\Http\Controllers\Api\StudentController@gradeStudent');

    Route::get('/{account_id}', 'App\Http\Controllers\Api\StudentController@show');
    Route::put('/{student_id}', 'App\Http\Controllers\Api\StudentController@update');

    Route::get('/leavenotice/list/{student_id}', 'App\Http\Controllers\Api\LeaveNoticeController@studentLeaveNotice');
    Route::get('/leavenotice/{student_id}', 'App\Http\Controllers\Api\LeaveNoticeController@show');
});

Route::get('test', function () {
    echo md5("12345678");
});
