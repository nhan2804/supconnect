<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject_Type extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'type_id';
    protected $table = 'subject_type';
}
