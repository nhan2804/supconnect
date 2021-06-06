<?php

namespace App\Models\Target;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject_Class;

class Target extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'target_id';
    protected $table = 'target';

    public function subject()
    {
        return $this->belongsTo(Subject_Class::class, 'subject_class', 'subject_class_id');
    }
}
