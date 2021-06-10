<?php

namespace App\Http\Controllers\Api\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Record\Record;
use App\Models\Record\RecordDetail;
use App\Models\Subject_Class;
use App\Models\Subject_List;
use Illuminate\Http\Request;
use DB;

class SubjecClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_subj = Subject_List::with(['list_subject' => function ($query) {
            return $query->where('lecturer_id', 'GVCS002');
        }])->get()->toArray();

        return array_filter($list_subj, function ($e) {
            return count($e['list_subject']);
        });
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $r)
    {

        $now = date('Y-m-d');
        if ($r->date) $now = $r->date;

        // DB::enableQueryLog();
        $students = DB::table('subject_class')->where('subject_class.subject_class_id', $id)->where('lecturer_id', 'GVCS002')
            ->join('student_of_subject_class', 'student_of_subject_class.subject_class', 'subject_class.subject_class_id')
            ->join('student', 'student.student_id', 'student_of_subject_class.student_id')
            ->get();
        // $sb = Subject_Class::where('subject_class_id', $id)->where('lecturer_id', 'GVCS002')->first();
        $check = DB::table('subject_class')->where('subject_class.subject_class_id', $id)->where('lecturer_id', 'GVCS002')
            ->join('roll_call_record', 'roll_call_record.subject_class', 'subject_class.subject_class_id')
            ->where('date', $now)->first();
        //check xem đã vào điểm danh chưa
        if (!$check) {
            $n = new Record;
            $n->date = $now;
            $n->subject_class = $id;
            $n->lesson = 1;
            $n->number_of_attendants = count($students);
            $n->save();
            $id_record = DB::getPdo()->lastInsertId();
            foreach ($students as $k => $v) {
                $n = new RecordDetail;
                $n->record_id
                    = $id_record;
                $n->student_id = $v->student_id;
                $n->is_attend = 1;
                $n->leave_of_absence_letter = 1;
                $n->reason = 1;
                $n->save();
            }
        }
        return $students = DB::table('subject_class')->where('subject_class.subject_class_id', $id)->where('lecturer_id', 'GVCS002')
            ->whereDate('time_start', '<=', $now)
            ->whereDate('time_end', '>=', $now)
            ->join('student_of_subject_class', 'student_of_subject_class.subject_class', 'subject_class.subject_class_id')
            ->join('student', 'student.student_id', 'student_of_subject_class.student_id')
            ->join('roll_call_record_detail', 'student.student_id', 'roll_call_record_detail.student_id')
            ->get();

        // dd(DB::getQueryLog());
        // return $list_std = 
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
    public function update(Request $r, $id)
    {
        $rec = RecordDetail::find($id);
        $is = $rec->is_attend == 1 ? 0 : 1;
        $rec->is_attend = $is;
        $rec->save();
        return response()->json(['message' => 'Thành công'], 200);
    }
    public function edit_record(Request $r, $id)
    {
        $re = Record::find($id);
        $re->lesson = $r->lesson;
        $re->save();
        return response()->json(['message' => 'Thành công'], 200);
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
