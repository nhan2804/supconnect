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


class AuthController extends Controller
{
    public function login(Request $req) {

        $account = Account::where(['username' => $req->username, 'password' => md5($req->password)])->first();
        Auth::login($account);
        $token = Str::random(60);
        $account->api_token = $token;
        $account->save();
        $role = Account_Role::where('account_id', Auth::user()->account_id)->first()->role_id;
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
            'user' => Auth::user(),
            'token' => Auth::user()->api_token
        ]);
    }
}
