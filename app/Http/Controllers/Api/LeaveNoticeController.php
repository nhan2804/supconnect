<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\LeaveNotice;
use App\Models\Student_Of_Subject_Class;
use App\Models\Subject_Class;
use App\Models\Subject_List;
use App\Models\Lecturer;
use App\Models\Lecturer_Degree_Type;
use SebastianBergmann\Environment\Console;

class LeaveNoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $leavenotice = LeaveNotice::where('leave_notice_id', $id)->get();
        return response()->json([
            'success' => true,
            'leavenotices' => $leavenotice
        ]);
    }

    public function studentLeaveNotice($student_id)
    {
        $now = date('Y-m-d H:i:s');
        $leavenotices = LeaveNotice::join('student_of_subject_class', 'leave_notice.subject_class', 'student_of_subject_class.subject_class')
            ->join('subject_class', 'subject_class.subject_class_id', 'leave_notice.subject_class')
            ->where('student_of_subject_class.student_id', $student_id)
            ->where('subject_class.date_start', '<=', $now)
            ->where('subject_class.date_end', '>=', $now)
            ->select('leave_notice.*', 'subject_class.lecturer_id as lecturer_id')
            ->get();
        foreach ($leavenotices as $leavenotice) {
            $lecturer = Lecturer::find($leavenotice->lecturer_id);
            $lecturer_degree = Lecturer_Degree_Type::find($lecturer->degree);
            $leavenotice->lecturer = $lecturer_degree->abbreviation . ' ' . $lecturer->first_name_lecturer . ' ' . $lecturer->last_name_lecturer;
        }


        return response()->json([
            'success' => true,
            'leavenotices' => $leavenotices
        ]);
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
