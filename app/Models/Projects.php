<?php

namespace App\Models;

use App\Controllers\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Projects extends Model
{
    protected $table        = 'tb_project';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'title', 'description', 'progress', 'due_date', 'estimation_cpus', 'estimation_cost', 'estimation_revenue', 'actual_cpus', 'actual_cost',
        'actual_revenue', 'id_user', 'project_type', 'priority', 'created_at', 'updated_at', 'image'
    ];


    function _getProjects(String $email = null, int $limit = null)
    {
        $builder = Projects::select("*")
            ->join('tb_user', 'tb_project.id_user', '=', 'tb_user.id')
            ->where('project_type', 'project');
        if ($email) {
            $builder->where('email', '=', $email);
        }

        if ($limit) {
            $builder->limit($limit);
        }
        return $builder->orderBy('created_at', 'DESC')->get()->toArray();
    }

    function _getUserPerformance(String $email = null)
    {
        $builder = Projects::select(DB::raw('tb_user.full_name as name, COUNT(CASE WHEN progress = "Complete" THEN title END) as task_complete, tb_user.image'))
            ->join('tb_user', 'tb_project.id_user', '=', 'tb_user.id')->orderBy('task_complete', 'DESC');
        if ($email) {
            $builder->where('email', $email);
        }
        return $builder->groupBy('tb_project.id_user')->get()->toArray();
    }

    function _getPerformanceChart($id_user = null)
    {
        $builder = Projects::select(DB::raw("COUNT(CASE WHEN progress = 'On Progress' THEN progress END) as progress , COUNT(CASE WHEN progress = 'To Do' THEN progress END) as todo, COUNT(CASE WHEN progress = 'Stuck' THEN progress END) as stuck, COUNT(CASE WHEN progress = 'Pending' THEN progress END) as pending, COUNT(CASE WHEN progress = 'Complete' THEN progress END) as complete"));
        if ($id_user) {
            $builder->where('id_user', $id_user);
        }
        return $builder->whereYear('created_at', date('Y'))->first()->toArray();
    }
}
