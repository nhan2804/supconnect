<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCategory extends Model
{
    use HasFactory;
    protected $table = 'transaction_category';
    protected $primaryKey = 'transaction_category_id';
    public $timestamp = false;
}
