<?php

namespace App\Models\Target;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdviceTarget extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'advice_id';
    protected $table = 'advice_for_target';
}
