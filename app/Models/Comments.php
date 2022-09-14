<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table        = 'tb_comment';
    protected $primaryKey   = 'id';
    protected $fillable     = ['comment', 'id_user', 'id_project', 'created_at', 'updated_at'];
}
