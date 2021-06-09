<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer_Degree_Type extends Model{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'degree_id_type';
    protected $table = 'lecturer_degree_type';
}

