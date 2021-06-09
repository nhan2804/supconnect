<?php

namespace App\Http\Controllers\Api\Target;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Target\Target;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Auth;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::user();
        $now = date('Y-m-d h:i:s');
        $id_sv = Student::where('account_id', 4)->first()->student_id;
        $target = DB::table('subject_class')->join('student_of_subject_class', 'student_of_subject_class.subject_class', 'subject_class.subject_class_id')
            ->LeftJoin('target', 'target.subject_class_id', 'student_of_subject_class.subject_class')->where('student_of_subject_class.student_id', $id_sv)->whereDate('time_start', '<=', $now)
            ->whereDate('time_end', '>=', $now)->get();
        return $target;
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
    public function store(Request $r)
    {
        $id_sv = Student::where('account_id', 4)->first()->student_id;
        $new = new Target;
        $new->student_id = $id_sv;
        $new->subject_class_id = $r->sub_class_id;
        $new->grade_target = $r->grade;
        if ($new->save()) return response()->json(['message' => 'Thành công', 'new' => $new], 200);
        return response()->json(['message' => 'Lỗi xảy ra'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)

    {
        return "chưa có AI";
        // DB::table('subject_class')->join('student_of_subject_class', 'student_of_subject_class.subject_class', 'subject_class.subject_class_id')
        //     ->LeftJoin('target', 'target.subject_class_id', 'student_of_subject_class.subject_class')->where('student_of_subject_class.student_id', $id_sv)->whereDate('time_start', '<=', $now)
        //     ->whereDate('time_end', '>=', $now)->where('target.target_id', '=', null)->get();
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
