<?php

namespace App\Models\Record;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordDetail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'roll_call_record_detail';
    protected $primaryKey = 'record_detail_id';
    protected $fillable = ['record_id', 'student_id', 'is_attend', 'leave_of_absence_letter', 'reason
'];
}
