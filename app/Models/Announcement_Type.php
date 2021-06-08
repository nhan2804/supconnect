<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement_Type extends Model
{
    use HasFactory;
    protected $primaryKey = 'announcement_type_id';
    protected $table = 'announcement_type';
    public $timestamps = false;
}
