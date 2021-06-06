<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'chat_history_id';
    protected $table = 'chat_history';
    public function user()
    {
        return $this->belongsTo(Student::class, 'user_1', 'account_id');
    }
}
