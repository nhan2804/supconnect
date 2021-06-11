<?php

namespace App\Http\Controllers\Api\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Lecturer_Degree_Type;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $lecturers = Lecturer::all();

        $class_lecturers = DB::table('student_of_subject_class')
                                ->join('subject_class', 'subject_class','subject_class_id')
                                ->join('lecturer', 'subject_class.lecturer_id', 'lecturer.lecturer_id')
                                ->where('student_id', $request->student_id)
                                ->select('lecturer.*')
                                ->get();

        foreach ($lecturers as $lecturer){
            $degree = Lecturer_Degree_Type::find($lecturer->degree)->abbreviation;
            $lecturer->name = $degree.''.$lecturer->first_name_lecturer.' '.$lecturer->last_name_lecturer;
        }

        foreach($class_lecturers as $class_lecturer){
            $class_lecturer->degree = Lecturer_Degree_Type::find($class_lecturer->degree)->abbreviation;
            $class_lecturer->name = $class_lecturer->degree.''.$class_lecturer->first_name_lecturer.' '.$class_lecturer->last_name_lecturer;
        }
        return response()->json([
            'success'=>true,
            'all_lecturers'=>$lecturers,
            'class_lecturers'=>$class_lecturers,
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
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function show(Lecturer $lecturer)

    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function edit(Lecturer $lecturer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lecturer $lecturer)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lecturer $lecturer)

    {
        //
    }
}
