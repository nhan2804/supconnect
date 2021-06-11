<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account_Balance extends Model
{
    use HasFactory;
    protected $table = 'balance_of_account';
    protected $primaryKey = 'account_balance_id';
    public $timestamps = false;
    protected $fillable = [
        'balance'
    ];
}
