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
use App\Models\Subject_List;
use App\Models\Grade_Type;
use App\Models\Grade_Book;
use App\Models\Grade_Book_Details;
use App\Models\Student_Of_Subject_Class;


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

    public function gradeStudent($student_id) {

        $now = date('Y-m-d');

        $subjectResults = Student_Of_Subject_Class::join('subject_class', 'subject_class.subject_class_id', 'student_of_subject_class.subject_class' )
                                    ->where('subject_class.date_start', '<=', $now)
                                    ->where('subject_class.date_end', '>=', $now)
                                    ->where('student_id', $student_id)  
                                    ->select('student_of_subject_class.junction_id', 'student_of_subject_class.subject_class', 'subject_class.subject_id')
                                    ->get();
        $marks = [];
        foreach($subjectResults as $class) {

            $grades = Grade_Book::join('grade_book_detail', 'grade_book_detail.grade_book_id', 'grade_book.grade_book_id')
                        ->where('grade_book_detail.student_id', $student_id)
                        ->select('grade_book_detail.grade_book_detail_id', 'grade_book.grade_type' ,'grade_book_detail.grade', 'grade_book.grade_weight', 'grade_book.subject_class')
                        ->get();

            $class->number_credit = Subject_List::find($class->subject_id)->credit;
            unset($class->subject_id);
            $class->point_Rollup = 0;
            $class->point_Assign = 0;
            $class->point_midTerm = 0;
            $class->point_EndTerm = 0;
            $class->point_10 = 0;

            foreach($grades as $grade) {
                if($grade->subject_class == $class->subject_class) {
                    if($grade->grade_type == 1) {
                        $class->point_Rollup = $grade->grade;
                    } else if ($grade->grade_type == 2) {
                        $class->point_Assign = $grade->grade;
                    } else if ($grade->grade_type == 3) {
                        $class->point_midTerm = $grade->grade;
                    } else if ($grade->grade_type == 4) {
                        $class->point_EndTerm = $grade->grade;
                    }
                    $class->point_10 += $grade->grade * $grade->grade_weight;
                }
            }

            if($class->point_10 >= 8.5) {
                $class->point_word = 'A';
            } else if($class->point_10 >= 7) {
                $class->point_word = 'B';
            } else if($class->point_10 >= 5.5) {
                $class->point_word = 'C';
            } else if($class->point_10 >= 4) {
                $class->point_word = 'D';
            } else {
                $class->point_word = 'F';
            }
            
        }

        return response()->json([
            'success' => true,
            'subjectResults' => $subjectResults
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
