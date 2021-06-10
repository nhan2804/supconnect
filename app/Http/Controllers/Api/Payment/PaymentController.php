<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentDetail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $student = Student::where('account_id', $request->acc_id)->first();

        $payments = Payment::where('user_id', $student->student_id)->get();

        foreach($payments as $payment) {
            $payment->type_name = DB::table('transaction_type')
                ->where('transaction_type_id', $payment->transaction_type_id )->first()->transaction_type_name;
            if($payment->transaction_type_id == 2) {
                $payment->category_name = $this->getCateName($payment);
            }
        }

        return response()->json([
            'success'=>true,
            'payments' => $payments
        ], 200);
    }

    private function getCateName($payment) {
        $cateID = PaymentDetail::where('transaction_history_id', $payment->transaction_history_id)
                                                    ->first()->transaction_category_id;
        $name = DB::table('transaction_category')
                    ->where('transaction_category_id', $cateID)->pluck('transaction_category_name')[0];
        return  $name;
    }

    public function detail(Request $request) {
        $detail = PaymentDetail::where('transaction_history_id', $request->id)->first();

        return response()->json([
            'success'=>true,
            'detail'=>$detail
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
        $student = Student::where('account_id', $request->acc_id)->first();
        
        $newPayment = new Payment;
        $newPayment->user_id = $student->student_id;
        $newPayment->transaction_type_id = $request->type;
        $newPayment->date = date('Y-m-d h:i:s');
        $newPayment->amount = $request->amount;

        $newPayment->save();

        $newDetail = new PaymentDetail;

        $newDetail->transaction_history_id = $newPayment->transaction_history_id;
        $newDetail->transaction_category_id = $request->category;
        $newDetail->amount = $request->amount;
        $newDetail->description = $request->description;

        if ($newDetail->save()) 
            return response()->json([
                'success' => true,
                'payment' => $newPayment,
                'detail' => $newDetail
            ], 200);

        return response()->json([
            'success' => "false"
        ], 500);
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
