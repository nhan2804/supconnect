<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;
use Hash;
use App\Models\Account;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Lecturer_Degree_Type;
use App\Models\Account_Role;
use App\Models\Class_List;
use App\Models\User;
use App\Models\Faculty;
use Session;
use DB;


class AuthController extends Controller
{
    public function login(Request $req)
    {
        // return Auth::user();
        $account = Account::where(['username' => $req->username, 'password' => md5($req->password)])->first();

        $student = null;
        $lecturer = null;
        $role = Account_Role::where('account_id', $account->account_id)->first()->role_id;
        if ($role == 1) {
            $student = Student::where('account_id', $account->account_id)->first();
            $student->class = Class_List::find($student->class_id)->class_name;
        } else if($role == 2) {
            $lecturer = Lecturer::where('account_id', $account->account_id)->first();
            $lecturer->degree = Lecturer_Degree_Type::find($lecturer->degree)->degree_type_name;
            $lecturer->faculty = Faculty::find($lecturer->faculty_id)->faculty_name;
        }

        return response()->json([
            'success' => true,
            'role' => $role,
            'password' => md5($req->password),
            'student' => $student,
            'lecturer' => $lecturer,
        ]);
    }

    public function logout() {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'logout success'
        ]);
    }
}
