<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Account_Balance;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentDetail;
use App\Models\PaymentCategory;
use App\Models\Student;
use App\Models\Department;
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
        $student = Student::find($request->student_id);
        $balance = Account_Balance::where('account_id', $student->account_id)->first()->balance;
        $payments = Payment::where('user_id', $request->student_id)->get();
        foreach ($payments as $payment) {
            $payment->type_name = DB::table('transaction_type')
                ->where('transaction_type_id', $payment->transaction_type_id)->first()->transaction_type_name;
            if ($payment->transaction_type_id == 2) {
                $payment->type_name = $this->getCateName($payment);
            }
            $payment->amount = number_format($payment->amount, 0, ",", ".");
        }

        return response()->json([
            'success' => true,
            'balance' => number_format($balance, 0, ",", "."),
            'student_name' => $student->first_name . ' ' . $student->last_name,
            'student_id' => $student->student_id,
            'payments' => $payments
        ], 200);
    }

    private function getCateName($payment)
    {
        $name = DB::table('transaction_category')
            ->where('transaction_category_id', $payment->transaction_category)->pluck('transaction_category_name')[0];
        return  $name;
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

    /**
     * Request params:
     *  - acc_id : account_id
     *  - type: type of the transaction, recharge or pay
     *  - amount: how much currency is recharged or spent
     *  - category: category of the transaction. Payment only
     *  - description: description of the transaction, obviously
     */
    public function store(Request $request)
    {
        /**
         * Get the student that processing the transaction
         */
        $student = Student::where('account_id', $request->acc_id)->first();
        /**
         * Get the balance of the account
         */
        $acc_balance = Account_Balance::where('account_id', $request->acc_id)->first();

        /**
         * Prepare new record for `Transaction_history`
         */
        $newPayment = new Payment;
        $newPayment->user_id = $student->student_id;
        $newPayment->transaction_type_id = $request->type;
        $newPayment->date = date('Y-m-d h:i:s');
        $newPayment->amount = $request->amount;

        /**
         * store old balance
         */
        $oldBalance = $acc_balance->balance;

        /**
         * Update the new Balance
         */
        $newBalance = 0;
        // recharge
        if ($newPayment->transaction_type_id == 1) {
            $newBalance = $oldBalance + $newPayment->amount;
        }
        // withdraw
        if ($newPayment->transaction_type_id == 2) {
            $newBalance = $oldBalance - $newPayment->amount;

            if ($oldBalance < $newPayment->amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số dư của bạn không đủ'
                ]);
            }
        }

        $id_trans = DB::getPdo()->lastInsertId();

        $acc_balance->balance = $newBalance;

        /**
         * Get the payment category
         */
        $paymentCate = PaymentCategory::find($request->category);

        /**
         * If the payment history and account's balance is updated successfully,
         * then create the new `transaction_history_detail` of that record,
         * then response. Otherwise return 500 err
         */
        if ($newPayment->save() && $acc_balance->update([
            'amount' => $newBalance
        ])) {

            $detail = new PaymentDetail();
            $detail->amount = $newPayment->amount;
            $detail->description = $request->description;
            $detail->transaction_history_id = $newPayment->transaction_history_id;
            $detail->transaction_category_id = $request->category;

            $detail->save();
            return response()->json([
                'success' => true,
                'type' => $paymentCate->transaction_category_name,
                'department' => Department::find($paymentCate->department_id)->department_name,
                'date' => $newPayment->date,
                'message' => 'Giao dịch thành công',
                'detail' => $detail
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch thất bại'
            ], 500);
        }
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
