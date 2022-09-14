<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Activities extends Model
{
    protected $table        = 'users_activity';
    protected $primaryKey   = 'id';
    protected $fillable     = ['activity', 'id_user', 'created_at', 'updated_at'];


    function _getRecentActivities(int $limit = null, String $email = null)
    {
        $builder = Activities::select("*")
            ->join('tb_user', 'users_activity.id_user', '=', 'tb_user.id');

        if ($email) {
            $builder->where('email', '=', $email);
        }

        return $builder->orderBy('users_activity.created_at', 'DESC')->limit($limit)->get()->toArray();
    }

    function _getLastInteract(String $email = null)
    {
        $builder = Activities::select("full_name as name", DB::raw("MAX(created_at) as date"))
            ->join('tb_user', 'users_activity.id_user', '=', 'tb_user.id');
        if ($email) {
            $builder->where('email', '=', $email);
        }
        return $builder->groupBy('id_user')->orderBy('users_activity.created_at', 'DESC')->get()->toArray();
    }
}
