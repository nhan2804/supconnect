<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'transaction_type';
    protected $primaryKey = 'transaction_type_id';
}
