<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\Account;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\TimeTable;
use App\Models\Subject_List;
use App\Models\Student_Of_Subject_Class;

class TimeTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timetable = TimeTable::join('student_of_subject_class', 'student_of_subject_class.subject_class', 'timetable.subject_class_id')
                            ->get();
        return response()->json([
            'success' => true,
            'timetable' => $timetable
        ]);
    }

    public function timetableUser($user_id) {
        $timetables = TimeTable::join('student_of_subject_class', 'student_of_subject_class.subject_class', 'timetable.subject_class_id')
            ->join('subject_class', 'subject_class.subject_class_id', 'timetable.subject_class_id')
            ->where('student_of_subject_class.student_id', $user_id)
            ->select('timetable.timetable_id', 'timetable.day_of_week', 'timetable.lesson', 'timetable.classroom',
            'student_of_subject_class.student_id', 'subject_class.subject_id', 'subject_class.lecturer_id' )
            ->orderBy('timetable.day_of_week')
            ->orderBy('timetable.lesson')
            ->get();

        foreach($timetables as $timetable) {
            $lecturer = Lecturer::find($timetable->lecturer_id);
            $timetable->lecturer = $lecturer->degree .' ' .$lecturer->first_name_lecturer .' ' .$lecturer->last_name_lecturer; 
            $timetable->subject_name = Subject_List::find($timetable->subject_id)->subject_name; 
        }

        return response()->json([
            'success' => true,
            'timetables' => $timetables
        ]);
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
        $timetable = TimeTable::join('student_of_subject_class', 'student_of_subject_class.subject_class', 'timetable.subject_class_id')
                            ->get();
        return response()->json([
            'success' => true,
            'timetable' => $timetable
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
