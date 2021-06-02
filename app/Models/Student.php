<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $table = 'student';
    protected $primaryKey = 'student_id';
    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'phone_number',
        'start_year',
        'class_id',
        'email',
        'avatar',
    ];
}
