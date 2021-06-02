<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_List extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'class_id';
    protected $table = 'class_list';
}
