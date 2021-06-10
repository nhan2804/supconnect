<?php

namespace App\Http\Controllers\Api\Record;

use App\Http\Controllers\Controller;
use App\Models\Record\Record;
use App\Models\Record\RecordDetail;
use App\Models\Student;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $student = Student::where('account_id', $request->acc_id)->first();

        //get only record of that student from a specified subject class
        $records = Record::join('roll_call_record_detail', 'roll_call_record.record_id', 'roll_call_record_detail.record_id')
                    ->where('subject_class', $request->subject_class)
                    ->where('student_id', $student->student_id)
                    ->get();

        //get details of those records
            foreach($records as $record) {
                $record->subject_class_name = $this->getSubjectClassName($record);
            }

        return response()->json([
            'success' => true,
            'record' => $records
        ], 200);
    }

    private function getSubjectClassName($record) {
        $subject = DB::table('subject_class')->where('subject_class_id', $record->subject_class)
                    ->first()->subject_id;

        $name = DB::table('subject_list')->where('subject_id', $subject)->first()->subject_name;
        return $name;
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
