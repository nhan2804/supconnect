<?php

namespace App\Http\Controllers\Api\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Record\Record;
use App\Models\Record\RecordDetail;
use App\Models\Subject_Class;
use App\Models\Subject_List;
use App\Models\Student;
use App\Models\TimeTable;
use App\Models\Class_List;
use App\Models\Student_Of_Subject_Class;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjecClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $now = date('Y-m-d');
        $subjects = TimeTable::join('subject_class', 'subject_class.subject_class_id', 'timetable.subject_class_id')
            ->where('subject_class.lecturer_id', $req->lecturer_id)
            ->where('subject_class.date_start', '<=', $now)
            ->where('subject_class.date_end', '>=', $now)
            ->get();
        if ($subjects->count() == 0) {
            return response()->json([
                'success' => false,
                'timetables' => $subjects
            ]);
        }
        return response()->json([
            'success' => true,
            'timetables' => $subjects
        ]);
    }
    public function getSubjectClassofLecturer(Request $request)
    {
        $now = date('Y-m-d');
        $subject_lists = SUbject_List::all();
        if ($request->semester == '' || $request->school_year == '') {
            foreach ($subject_lists as $key => $subject_list) {
                $subject_list->subject_classes = Subject_Class::where('lecturer_id', $request->lecturer_id)
                    ->where('subject_id', $subject_list->subject_id)
                    ->where('date_start', '<=', $now)
                    ->where('date_end', '>=', $now)
                    ->get();
                if ($subject_list->subject_classes->count() == 0) {
                    unset($subject_lists[$key]);
                }
            }
        } else {
            foreach ($subject_lists as $key => $subject_list) {
                $subject_list->subject_classes = Subject_Class::where('lecturer_id', $request->lecturer_id)
                    ->where('subject_id', $subject_list->subject_id)
                    ->where('semester', $request->semester)
                    ->where('school_year', $request->school_year)
                    ->get();
                if ($subject_list->subject_classes->count() == 0) {
                    unset($subject_lists[$key]);
                }
            }
        }
        $arr = [];
        foreach ($subject_lists as $subject) {
            array_push($arr, $subject);
        }
        return response()->json([
            'success' => true,
            'subject_lists' => $arr
        ]);
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
    public function show($id_subject_class, Request $r)
    {
        $now = date('Y-m-d');
        if ($r->date) $now = $r->date;

        // DB::enableQueryLog();
        $students = DB::table('subject_class')
            ->where('subject_class.subject_class_id', $id_subject_class)
            ->join('student_of_subject_class', 'student_of_subject_class.subject_class', 'subject_class.subject_class_id')
            ->join('student', 'student.student_id', 'student_of_subject_class.student_id')
            // dòng này dùng để lấy ra lớp sv đang sinh hoạt
            ->join("class_list", 'student.class_id', 'class_list.class_id')
            ->select('student.*', 'class_list.*')

            ->get();

        // $sb = Subject_Class::where('subject_class_id', $id)->where('lecturer_id', 'GVCS002')->first();
        $record = Record::whereDate('date', $now)->where('subject_class', $id_subject_class)->first();

        //check xem đã vào điểm danh chưa
        if (!$record) {
            $record = new Record;
            $record->date = $now;
            $record->subject_class = $id_subject_class;
            $record->lesson = 1;
            $record->number_of_attendants = 0;
            $record->save();
            $id_record = DB::getPdo()->lastInsertId();
            foreach ($students as $k => $v) {
                $record = new RecordDetail;
                $record->record_id = $id_record;
                $record->student_id = $v->student_id;
                $record->is_attend = 0;
                $record->leave_of_absence_letter = 0;
                $record->reason = 0;
                $record->save();
            }
        }


        foreach ($students as $student) {
            // ref đến dòng 118, dùng để lấy lớp sinh hoạt sinh viên nên bỏ, k truy vấn lấy lại
            // $student->class_name = Class_List::find($student->class_id)->class_name;
            // $student->is_attend = 1;
            $student->record_detail_id = $record->record_id;
        }

        // $students = DB::table('subject_class')->where('subject_class.subject_class_id', $id)->where('lecturer_id', 'GVCS002')
        //     ->whereDate('date_start', '<=', $now)
        //     ->whereDate('date_end', '>=', $now)
        //     ->join('student_of_subject_class', 'student_of_subject_class.subject_class', 'subject_class.subject_class_id')
        //     ->join('student', 'student.student_id', 'student_of_subject_class.student_id')
        //     ->join('roll_call_record_detail', 'student.student_id', 'roll_call_record_detail.student_id')
        //     ->join('class_list', 'class_list.class_id' ,'student.class_id')
        //     ->join('roll_call_record', 'roll_call_record.record_id' ,'roll_call_record_detail.record_id')
        //     ->where('roll_call_record.date', $r->date)
        //     ->select('student.*', 'roll_call_record_detail.record_detail_id', 'roll_call_record_detail.is_attend','class_list.class_name')
        //     ->get();
        $subject_class = Subject_Class::where('subject_class_id', $id_subject_class)->first();

        return response()->json([
            'success' => true,
            'subject_class' => $subject_class->subject_class_name,
            'semeter' => $subject_class->semester,
            'year' => explode('-', $now)[0],
            'date' => $now,
            'students' => $students,
        ], 200);
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
        $rec = RecordDetail::findOrFail($id);
        $record = Record::find($rec->record_id);
        if ($r->cardID == '' || $r->cardID == null) {
            $is = $rec->is_attend == 1 ? 0 : 1;
            $rec->is_attend = $is;
            $rec->save();
        } else {
            $rec->is_attend = 1;
            $rec->save();
            // nếu sinh viên quét card điểm danh thì set lại sv có mặt lên +1
            $record->number_of_attendants = $record->number_of_attendants++;
            $record->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'attended',
            'record' => $rec
        ], 200);
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

    public function getStudentsOfClass($class_id)
    {
        $students = Student_Of_Subject_Class::where('subject_class', $class_id)
            ->join('student', 'student.student_id', 'student_of_subject_class.student_id')
            ->select('student.student_id', 'student.first_name', 'student.last_name', 'student.class_id')
            ->get();
        // đoạn này của Phong tôi k rõ, nhưng để foreach mà truy vấn như vậy rất ngốn, nếu có gì đổi đc data từ api trả về thì alo tôi sửa lại
        foreach ($students as $student) {
            $student->student_name = $student->first_name . ' ' . $student->last_name;
            $student->class_name = Class_List::find($student->class_id)->class_name;
            $student->subject_class_id = $class_id;
            $student->checkRollUp = false;
            $student->avatar = Student::find($student->student_id)->avatar;
            unset($student->first_name);
            unset($student->last_name);
            unset($student->class_id);
        }

        if ($students) {
            return response()->json([
                'success' => true,
                'students' => $students
            ]);
        }
        return response()->json([
            'success' => false,
            'students' => []
        ]);
    }
}
