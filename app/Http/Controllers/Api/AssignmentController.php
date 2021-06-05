<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Assignment;
use App\Models\Student_Of_Subject_Class;
use App\Models\Subject_List;


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
        //
    }

    public function studentAssignment($student_id) {
        $now = date('Y-m-d H:i:s');
        $assignments = Assignment::join('student_of_subject_class', 'assignment.subject_class', 'student_of_subject_class.subject_class' )
                                ->join('subject_class', 'subject_class.subject_class_id', 'assignment.subject_class')
                                ->where('student_of_subject_class.student_id', $student_id)
                                ->select('assignment.*', 'subject_class.subject_id')
                                ->get();

        $result =(array) Arr::sort($assignments, function($assignment)
        {
            return strtotime($assignment->deadline) - strtotime(date('Y-m-d H:i:s'));
        });

        $arr = [];

        foreach($result as $item) {
            array_push($arr, $item);
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
