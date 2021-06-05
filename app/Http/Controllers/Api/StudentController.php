<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Hash;
use App\Models\Account;
use App\Models\Student;
use App\Models\Account_Role;
use App\Models\Class_List;


class StudentController extends Controller
{
    public function index($account_id) {
        $account = Account::find($account_id);
        $role = Account_Role::where('account_id', $account->account_id)->first()->role_id;
        if($role == 1) {
            $student = Student::find($account->user_id);
            $account->user_id = $student->student_id;
            $account->firstName = $student->first_name;
            $account->lastName = $student->last_name;
            $account->date_of_birth = $student->date_of_birth;
            $account->phoneNumber = $student->phone_number;
            $account->startYear = $student->start_year;
            $account->classId = $student->class_id;
            $account->email = $student->email;
            $account->avatar = $student->avatar;
        }

        $account->class = Class_List::find($account->class_id)->class_name;

        return response()->json([
            'success' => true,
            'user' => $account,
        ]);
    }

    public function show($user_id) {
        $student = Student::where('account_id', $user_id)->first();
        $student->class = Class_List::find($student->class_id)->class_name;

        return response()->json([
            'success' => true,
            'user' => $student,
        ]);
    }

    public function update($account_id, Request $req) {
        $student = Student::where('account_id', $account_id)->first();
        if($student) {
            $student->update($req->all());
            return $this->show($account_id);
        }
        return response()->json([
            'success' => true,
            'message' => 'student_id is not valid'
        ]);

    }
}
