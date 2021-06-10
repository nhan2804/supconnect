<?php

namespace App\Http\Controllers\Api\Record;

use App\Http\Controllers\Controller;
use App\Models\Record\Record;
use App\Models\Record\RecordDetail;
use App\Models\Student;
use App\Models\Subject_Class;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $student = Student::where('student_id', $request->user_id)->first();

        //get only record of that student from a specified subject class
        $records = Record::join('roll_call_record_detail', 'roll_call_record.record_id', 'roll_call_record_detail.record_id')
                    ->where('subject_class', $request->subject_class)
                    ->where('student_id', $student->student_id)
                    ->orderBy('date', 'asc')
                    ->get();
        $absencerecords = Record::join('roll_call_record_detail', 'roll_call_record.record_id', 'roll_call_record_detail.record_id')
        ->where('subject_class', $request->subject_class)
        ->where('student_id', $student->student_id)
        ->where('roll_call_record_detail.is_attend', 0)
        ->orderBy('date', 'asc')
        ->get();

        $subject_class = Subject_Class::find($request->subject_class)->subject_class_name;
        return response()->json([
            'success' => true,
            'subject_class_name'=> $subject_class,
            'record' => $records,
            'total_count' => $records->count(),
            'absence_count'=>$absencerecords->count(),

        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        // RecordDetail::create([
        //     'record_id' => 1,
        //     'student_id' => $id_sv,
        //     'is_attend' => 1,
        //     'leave_of_absence_letter' => 1,
        //     'reason' => 'None with' . $id_sv
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
