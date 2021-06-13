<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'lecturer_id';
    protected $table = 'lecturer';
    protected $fillable = [
        'first_name_lecturer',
        'last_name_lecturer',
        'date_of_birth',
        'phone_number_lecturer',
        'email',
    ];
}
