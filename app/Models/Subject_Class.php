<?php

namespace App\Models;

use App\Models\Target\Target;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject_Class extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'subject_class_id';
    protected $table = 'subject_class';
    public function subject()
    {
        return $this->belongsTo(Target::class, 'subject_class');
    }
    public function students()
    {
        return $this->hasMany(Student_Of_Subject_Class::class, 'subject_class', 'subject_class_id');
    }
}
