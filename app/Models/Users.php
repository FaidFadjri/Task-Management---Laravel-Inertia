<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table        = 'users';
    protected $primaryKey   = 'id';
    protected $fillable     = ['username', 'email', 'password', 'full_name', 'division', 'gender', 'company', 'image', 'role', 'created_at', 'updated_at'];
}
