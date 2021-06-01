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

class StudentController extends Controller
{
    public function index($account_id) {
        $account = Account::find($account_id);
        $role = Account_Role::where('account_id', $account->account_id)->first()->role_id;
        if($role == 1) {
            $student = Student::find($account->user_id);
            $account->firstName = $student->first_name;
            $account->lastName = $student->last_name;
            $account->age = $student->age;
            $account->phoneNumber = $student->phone_number;
            $account->startYear = $student->start_year;
            $account->classId = $student->class_id;
            $account->email = $student->email;
            $account->avatar = $student->avatar;
        }

        return response()->json([
            'success' => true,
            'user' => $account,
        ]);
    }

    public function show($user_id) {
        $student = Student::find($user_id);

        return response()->json([
            'success' => true,
            'user' => $student,
        ]);
    }

    public function update($account_id, Request $req) {
        $account = Account::find($account_id);
        $student = Student::find($account->user_id);
        $student->update($req->all());

        return $this->index($account_id);
    }
}
