<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table        = 'tb_file';
    protected $primaryKey   = 'id';
    protected $fillable     = ['file_name', 'file_type', 'id_project'];


    function _getFilesByProject($project_id)
    {
        $builder = Files::select("*")->where('id_project', '=', $project_id)->get()->toArray();
        return $builder;
    }
}
