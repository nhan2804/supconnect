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


class AuthController extends Controller
{
    public function login(Request $req) {

        $account = Account::where(['username' => $req->username, 'password' => md5($req->password)])->first();
        $account->save();
        Auth::login($account);
        $role = Account_Role::where('account_id', Auth::user()->account_id)->first()->role_id;
        if($role == 1) {
            $student = Student::where('account_id', $account->account_id)->first();
            $account->studentId = $student->student_id;
            $account->firstName = $student->first_name;
            $account->lastName = $student->last_name;

            $account->dateOfBirth = $student->date_of_birth;

            $account->phoneNumber = $student->phone_number;
            $account->startYear = $student->start_year;
            $account->classId = $student->class_id;
            $account->email = $student->email;
            $account->avatar = $student->avatar;
            $account->class = Class_List::find($account->classId)->class_name;
        }

        return response()->json([
            'success' => true,
            'user' => Auth::user(),
        ]);
    }
}
