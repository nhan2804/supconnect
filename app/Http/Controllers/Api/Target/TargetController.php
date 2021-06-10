<?php

namespace App\Http\Controllers\Api\Target;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use stdClass;
use App\Models\Student;
use App\Models\Target\Target;
use App\Models\Target\AdviceTarget;
use App\Models\User;
use App\Models\Class_List;
use App\Models\Subject_List;
use App\Models\Subject_Class;
use App\Models\Grade_Type;
use App\Models\Grade_Book;
use App\Models\Grade_Book_Details;
use App\Models\Student_Of_Subject_Class;
use DB;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $now = date('Y-m-d h:i:s');
        $grades = Grade_Book::where('subject_class', $req->input('subject_class_id'))
                            ->orderBy('grade_weight', 'asc')
                            ->get();

        foreach($grades as $grade) {
            $student_grade = Grade_Book_Details::where('grade_book_id', $grade->grade_book_id)
                            ->where('student_id', $req->input('student_id'))->first();
            if($student_grade != null) {
                $grade->grade = $student_grade->grade;
            } else {
                $grade->grade = -1;
            }
        }

        $result = [];
        foreach($grades as $grade) {
            $record = new stdClass();
            $record->grade_type_name = Grade_Type::where('type_id', $grade->grade_type)->first()->type_name;
            $record->grade_type = $grade->grade_type;
            if($grade->grade != -1) {
                $record->status = 1;
                $record->grade = $grade->grade;
            } else {
                $record->status = 0;
                $mark = AdviceTarget::join('target','advice_for_target.target_id', 'target.target_id')
                        ->where('target.subject_class_id', $req->input('subject_class_id'))
                        ->where('target.student_id', $req->input('student_id'))
                        ->where('advice_for_target.grade_type', $grade->grade_type)
                        ->first();
                if($mark == null) {
                    $record->grade = 0;
                } else {
                    $record->grade = $mark->grade_recommend;
                }
            }
            array_push($result,  $record);
        }

        $temp = $this->show(0, $req)->original['targets'];
        $grade_target = 0;
        $currentGrade = 0;
        foreach($temp as $item) {
            if($item->subject_class_id == $req->input('subject_class_id')) {
                $grade_target = $item->grade_target;
                $currentGrade = $item->currentGrade;
            }
        }

        return response()->json([
            'success' =>  true,
            'subject_name' => Subject_Class::find($req->input('subject_class_id'))->subject_class_name,
            'grade_target' => $grade_target,
            'currentGrade' => $currentGrade,
            'grades' => $result
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $now = date('Y-m-d h:i:s');
        $id_sv = Student::where('account_id', 4)->first()->student_id;
        return $list_target = DB::table('subject_class')->join('student_of_subject_class', 'student_of_subject_class.subject_class', 'subject_class.subject_class_id')
            ->LeftJoin('target', 'target.subject_class_id', 'student_of_subject_class.subject_class')->where('student_of_subject_class.student_id', $id_sv)->whereDate('time_start', '<=', $now)
            ->whereDate('time_end', '>=', $now)->where('target.target_id', '=', null)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
       $target = Target::where([
           ['student_id', '=', $req->student_id],
           ['subject_class_id', '=', $req->subject_class_id],
       ])->first();
       if($target != null) {
            $target->grade_target = $req->grade_target;
       } else {
            $target = new Target();
            $target->student_id = $req->student_id;
            $target->subject_class_id = $req->subject_class_id;
            $target->grade_target = $req->grade_target;
        }
       
        $target->save();

        $validAdvice = AdviceTarget::where('target_id', $target->target_id)->get();
        foreach($validAdvice as $item) {
            $item->delete();
        }

        $grades = Grade_Book::where('subject_class', $req->subject_class_id)
                            ->orderBy('grade_weight', 'asc')
                            ->get();

        $grade_target = floatval($req->grade_target);

        foreach($grades as $grade) {
            $student_grade = Grade_Book_Details::where('grade_book_id', $grade->grade_book_id)
                            ->where('student_id', $req->student_id)->first();
            if($student_grade != null) {
                $grade->grade = $student_grade->grade;
                $grade_target = $grade_target - $grade->grade*$grade->grade_weight;
            } else {
                $grade->grade = -1;
            }
        }


        $result = [];
        foreach($grades as $grade) {
            $record = new stdClass();
            $record->grade_type_name = Grade_Type::where('type_id', $grade->grade_type)->first()->type_name;
            $record->grade_type = $grade->grade_type;
            if($grade->grade != -1) {
                $record->status = 1;
                $record->grade = $grade->grade;
            } else {
                $record->status = 0;
                if(floatval($grade->grade_weight * 10) < $grade_target) {
                    $temp = floatval($grade->grade_weight * 10);
                    $grade_target = $grade_target - $temp;
                    $record->grade = 10;
                } else {
                    $record->grade = round($grade_target/$grade->grade_weight, 1);
                }

                $adviceTarget = new AdviceTarget();
                $adviceTarget->target_id = $target->target_id;
                $adviceTarget->grade_recommend = $record->grade;
                $adviceTarget->grade_type = $grade->grade_type;
                $adviceTarget->grade_weight = $grade->grade_weight;
                $adviceTarget->save();
            }
            array_push($result,  $record);
        }

        return response()->json([
            'success' => true,
            'message' => 'advise successful'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $req)
    {   
        $now = date('Y-m-d');   
    
        $targets = Target::where('student_id', $req->input('student_id'))->get();
        foreach($targets as $target) {
            $grades = Grade_Book::join('grade_book_detail', 'grade_book_detail.grade_book_id', 'grade_book.grade_book_id')
                        ->where('grade_book_detail.student_id', $req->input('student_id'))
                        ->where('grade_book.subject_class', $target->subject_class_id)
                        ->select('grade_book_detail.grade', 'grade_book.grade_weight')
                        ->get();
            $currentGrade = 0;
            foreach($grades as $grade) {
                $currentGrade += $grade->grade * $grade->grade_weight;
            }
            $target->currentGrade = $currentGrade;
        }
        
        return response()->json([
            'success' => true,
            'targets' => $targets
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
