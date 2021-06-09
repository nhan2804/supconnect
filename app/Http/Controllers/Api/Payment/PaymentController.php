<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentDetail;
use App\Models\Student;
use Illuminate\Http\Request;
use DB;

class PaymentController extends Controller
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
        $id_sv = Student::where('account_id', 4)->first()->student_id;
        $new = new Payment;
        $new->user_id = $id_sv;
        $new->transaction_type_id = 2;
        $new->date = date('Y-m-d h:i:s');
        $new->amount = $r->amount;

        $new->save();
        $new_d = new PaymentDetail;

        $new_d->transaction_history_id =
            DB::getPdo()->lastInsertId();
        $new_d->transaction_category_id = $r->transaction_category_id;
        $new_d->amount = $r->amount;
        $new_d->description = $r->description;

        if ($new_d->save()) return response()->json(['message' => "Thành công"], 200);
        return response()->json(['message' => "Có lỗi xảy ra"], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pay = Payment::with(['detail', 'type'])->find($id);
        return response()->json($pay, 200);
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
