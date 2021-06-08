<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment\PaymentDetail;

class Payment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'transaction_history';
    protected $primaryKey = 'transaction_history_id';
    public function detail()
    {
        return $this->hasOne(PaymentDetail::class, 'transaction_history_id', 'transaction_history_id');
    }
    public function type()
    {
        return $this->belongsTo(PaymentType::class, 'transaction_type_id');
    }
}
