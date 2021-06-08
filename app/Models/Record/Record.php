<?php

namespace App\Models\Record;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'roll_call_record';
    protected $primaryKey = 'record_id';
}
