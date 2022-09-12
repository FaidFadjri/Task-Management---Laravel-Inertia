<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Activities extends Model
{
    protected $table        = 'users_activity';
    protected $primaryKey   = 'id';
    protected $fillable     = ['activity', 'user_id', 'created_at', 'updated_at'];


    function _getRecentActivities(int $limit = null, String $email = null)
    {
        $builder = Activities::select("*")
            ->join('users', 'users_activity.user_id', '=', 'users.id');

        if ($email) {
            $builder->where('email', '=', $email);
        }

        return $builder->orderBy('users_activity.created_at', 'DESC')->limit($limit)->get()->toArray();
    }

    function _getLastInteract(String $email = null)
    {
        $builder = Activities::select("full_name as name", DB::raw("MAX(created_at) as date"))
            ->join('users', 'users_activity.user_id', '=', 'users.id');
        if ($email) {
            $builder->where('email', '=', $email);
        }
        return $builder->groupBy('user_id')->orderBy('users_activity.created_at', 'DESC')->get()->toArray();
    }
}
