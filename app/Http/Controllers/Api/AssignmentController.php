<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Assignment;
use App\Models\Student_Of_Subject_Class;
use App\Models\Subject_List;
use SebastianBergmann\Environment\Console;

class AssignmentController extends Controller
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
        $now = date('Y-m-d H:i:s');
        $assignment = new Assignment();
        $assignment->announcement_type = 6;
        $assignment->subject_class = $request->subject_class;
        $assignment->title = $request->title;
        $assignment->description = $request->description;
        if($request->deadline != '') {
            $assignment->deadline = date('Y-m-d H:i:s', strtotime($request->deadline));
        } else {
            $assignment->deadline = $now;
        }
        $assignment->create_date = $now;
        if($assignment->save()) {
            return response()->json([
                'success' => true,
                'message' => 'create new assignment succesful'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'create new assignment failed'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $assignment = Assignment::where('assignment_id', $id)->get();
        return response()->json([
            'success' => true,
            'assignments' => $assignment
        ]);
    }

    public function studentAssignment($student_id)
    {
        $now = date('Y-m-d H:i:s');
        $assignments = Assignment::join('student_of_subject_class', 'assignment.subject_class', 'student_of_subject_class.subject_class')
            ->join('subject_class', 'subject_class.subject_class_id', 'assignment.subject_class')
            ->where('student_of_subject_class.student_id', $student_id)
            ->where('subject_class.date_start', '<=', $now)
            ->where('subject_class.date_end', '>=', $now)
            ->select('assignment.*', 'subject_class.subject_class_name as class_name')
            ->get();

        $result = (array) Arr::sort($assignments, function ($assignment) {
            return strtotime($assignment->deadline) - strtotime(date('Y-m-d H:i:s'));
        });

        $arr = [];

        foreach ($result as $item) {
            array_push($arr, $item);
        }

        if($assignments->count() == 0) {
            return response()->json([
                'success' => false,
                'assignments' => $arr
            ]);
        }

        return response()->json([
            'success' => true,
            'assignments' => $arr
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
