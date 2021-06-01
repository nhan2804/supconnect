<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject_List extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'subject_id';
    protected $table = 'subject_list';
}
