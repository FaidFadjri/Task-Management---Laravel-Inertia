<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    function index(Request $request)
    {
        $query = $request->query->all();
        $user                       = $this->_getSession();

        if (!$query['search']) {
            $projects                   = Projects::where('user_id', $user['id'])->paginate(4)->toArray();
            $pagination                 = true;
        } else {
            $projects                   = Projects::select('*')->where('title', 'like', '%' . $query['search'] . '%');
            if ($user['role'] == 'member') {
                $projects = $projects->where('user_id', '=', $user['id'])->get()->toArray();
            }
            $pagination                 = false;
        }

        $components['user']         = $user;
        $components['projects']     = $projects;
        $components['pagination']   = $pagination;

        // dd($projects);
        return view('pages.project', $components);
    }

    protected function _getSession()
    {
        return session()->get('user');
    }
}
