<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade_Book_Details extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'grade_book_detail_id';
    protected $table = 'grade_book_detail';
}
