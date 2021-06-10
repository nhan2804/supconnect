<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'announcement';
    protected $primaryKey = 'announcement_id';
}
