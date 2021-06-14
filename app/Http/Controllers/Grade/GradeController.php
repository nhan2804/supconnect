<?php

namespace App\Http\Controllers\Grade;

use App\Http\Controllers\Controller;
use App\Models\Grade_Book;
use App\Models\Grade_Book_Details;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function store(Request $r)
    {
        $n = new Grade_Book_Details;
        $n->grade_book_id = $r->grade_book_id;
        $n->student_id = $r->student_id;
        $n->grade = $r->grade;
        if ($n->save()) return response()->json([
            'success' => true,
            'message' => 'Thành công',
            'new' => $n
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Thất bại',
        ]);
    }

    public function getSubjectGradeBookId($subject_class) {
        $gradeCols = Grade_Book::join('grade_type', 'grade_book.grade_type', 'grade_type.type_id')
                        ->where('subject_class', $subject_class)
                        ->select('grade_book_id', 'type_id', 'type_name')
                        ->get();
        
        return response()->json([
            'sucess' => 'true',
            'subject class' => $subject_class,
            'grade columns' => $gradeCols
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $r)
    {
        // query string $id_sv= $r->id_sv,$id_subject=$r->id_subject
        $grade = Grade_Book::select('grade_book.grade_book_id as grade_id', 'student_of_subject_class.subject_class as subject_class', 'student_of_subject_class.student_id', 'grade_book_detail_id', 'grade', 'grade_weight', 'type_name')
            ->join('student_of_subject_class', 'student_of_subject_class.subject_class', 'grade_book.subject_class')
            ->where([['student_of_subject_class.student_id', $id], ['grade_book.subject_class', $r->subject]])
            ->Leftjoin('grade_book_detail', 'grade_book_detail.grade_book_id', 'grade_book.grade_book_id')
            ->join('grade_type', 'type_id', 'grade_type')
            ->get();
        return response()->json([
            'success' => true,
            'grade' => $grade
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $r)
    {
        $n = Grade_Book_Details::findOrFail($id);
        $n->grade_book_id = $r->grade_book_id ? $r->grade_book_id : $n->grade_book_id;
        $n->student_id = $r->student_id ? $r->student_id : $n->student_id;
        $n->grade = $r->grade;

        if ($n->save()) return response()->json([
            'message' => 'Thành công',
            'new' => $n
        ]);
        return response()->json([
            'message' => 'Thất bại',
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
