<?php

namespace App\Models\Parent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    use HasFactory;
    protected $table = 'parent_of_student';
    protected $primaryKey = 'relationship_id';
}
