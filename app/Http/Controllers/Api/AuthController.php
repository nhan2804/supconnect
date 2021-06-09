<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;
use App\Models\Account;
use App\Models\Student;
use App\Models\Account_Role;
use App\Models\Class_List;
use App\Models\User;
use Hash;
use Session;
use DB;

class AuthController extends Controller
{
    public function login(Request $req)
    {

        // $u = new Account();
        // $u->username = 'nhan';

        // $u->password = Hash::make('nhan');

        // $u->save();
        // return;
        // return Hash::make($req->password);
        // return $input;


        // $input = $req->only(
        //     'username',
        //     'password'
        // );

        // $token = null;
        // $token = JWTAuth::attempt($input);
        // DB::enableQueryLog();
        // // return $token;
        // if (!$token) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác',
        //     ], 401);
        // }
        // Auth::user();
        // return DB::getQueryLog();

        // return response()->json(
        //     [
        //         'status' => true,
        //         'token' => $token,
        //         'user' => auth()->user()->password
        //     ],
        //     200
        // );

        // return auth()->user();
        $account = Account::where(['username' => $req->username])->first();
        // Auth::login($account);

        $role = Account_Role::where('account_id', $account->account_id)->first()->role_id;
        if ($role == 1) {
            $student = Student::where('account_id', $account->account_id)->first();
            $account->firstName = $student->first_name;
            $account->lastName = $student->last_name;
            $account->age = $student->age;
            $account->studentId = $student->student_id;
            $account->phoneNumber = $student->phone_number;
            $account->startYear = $student->start_year;
            $account->classId = $student->class_id;
            $account->email = $student->email;
            $account->avatar = $student->avatar;
            $account->class = Class_List::find($account->classId)->class_name;
        }

        return response()->json([
            'status' => true,
            // 'token' => $token,
            'user' => $account
        ], 200);
    }
}
