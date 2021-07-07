<?php

namespace App\Models\Parent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;
    protected $table = 'parent';
    protected $primaryKey = 'parent_id';
}
