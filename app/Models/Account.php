<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use HasFactory;
    protected $table = 'account';
    protected $primaryKey = 'account_id';
    public $timestamps  = false;

    public function getAuthIdentifierName() {
        
    }

    public function getAuthIdentifier() {
        
    }

    public function getAuthPassword() {
        
    }

    public function getRememberToken() {
        
    }

    public function setRememberToken($value) {
        
    }

    public function getRememberTokenName() {
        
    }
}
