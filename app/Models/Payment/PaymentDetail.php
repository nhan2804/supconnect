<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'transaction_history_detail';
    protected $keyPrimary = 'detail_id';
}
