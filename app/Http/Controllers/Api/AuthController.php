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
use App\Models\Parent\Parents;
use App\Models\Parent\ParentStudent;
use Session;
use DB;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        // return 9;
        $account = Account::where([
            'username' => $request->username,
            'password' => md5($request->password)
        ])->first();

        // return response()->json([
        //     'account' => $account,
        //     'username' => $request->username,
        //     'password' => $request->password
        // ]);

        // return $account;
        Auth::login($account);

        $student = null;
        $lecturer = null;
        $parent = null;
        $role = Account_Role::where('account_id', $account->account_id)->first()->role_id;

        if ($role == 1) {
            $student = Student::where('account_id', $account->account_id)->first();
            $student->class = Class_List::find($student->class_id)->class_name;
        } else if ($role == 2) {
            $lecturer = Lecturer::where('account_id', $account->account_id)->first();
            $lecturer->degree = Lecturer_Degree_Type::find($lecturer->degree)->degree_type_name;
            $lecturer->faculty = Faculty::find($lecturer->faculty_id)->faculty_name;
        } else if ($role == 3) {
            $parent = Parents::where('account_id', $account->account_id)->first();
            $student = Student::join('parent_of_student', 'student.student_id', 'parent_of_student.student_id')
                ->where('parent_id', $parent->parent_id)
                ->select('student.*')
                ->get();
        }
        return response()->json([
            'success' => true,
            'role' => $role,
            'student' => $student,
            'lecturer' => $lecturer,
            'parent' => $parent
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'logout success'
        ]);
    }
}
