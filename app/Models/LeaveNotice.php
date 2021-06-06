<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveNotice extends Model
{
    use HasFactory;
    protected $primaryKey = 'leave_notice_id';
    protected $table = 'leave_notice';
    public $timestamps = false;
}
