<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Projects;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new Users();
    }

    function index(Request $request)
    {
        $query  = $request->query->all(); # get query from url
        $user   = $this->_getSession(); # get user data check if they member or admin

        $progress_list = ['To Do', 'On Progress', 'Pending', 'Stuck', 'Complete'];
        $list_division = $this->usersModel->_getDivision();
        $request_url   = str_replace("/project", "", str_replace("/project?", "&", $this->removeParam($request->getRequestUri(), "page")));

        # getting projects data
        $projects               = Projects::select(DB::raw("progress, priority, title, description, estimation_cost, created_at, tb_project.id as project_id, tb_user.division, tb_user.company, tb_project.image, tb_project.updated_at"))->join('tb_user', 'tb_project.id_user', '=', 'tb_user.id');
        if ($user['role'] == 'member') {
            $projects           = $projects->where('id_user', $user['id']);
        }

        # handle progress query
        if (isset($query['progress'])) {
            $filter_progress = $query['progress'];
            if ($filter_progress) {
                $projects        = $projects->where('progress', '=', $filter_progress);
            }
        }

        # handle priority query
        if (isset($query['priority'])) {
            $filter_priority = $query['priority'];
            if ($filter_priority) {
                $projects        = $projects->where('priority', '=', $filter_priority);
            }
        }

        # handle project type query
        if (isset($query['project_type'])) {
            $filter_project_type = $query['project_type'];
            if ($filter_project_type) {
                $projects            = $projects->where('project_type', '=', $filter_project_type);
            }
        }

        # handle date range
        if (isset($query['start_date'])) {
            $start_date = $query['start_date'];
            if ($start_date) {
                $projects = $projects->whereDate('created_at', '>=', $start_date);
            }
        }

        if (isset($query['end_date'])) {
            $end_date = $query['end_date'];
            if ($end_date) {
                $projects = $projects->whereDate('created_at', '<=', $end_date);
            }
        }


        # handle company and division filter
        if (isset($query['company'])) {
            $company = $query['company'];
            if ($company) {
                $projects = $projects->where('company', '=', $company);
            }
        }

        if (isset($query['division'])) {
            $division = $query['division'];
            if ($division) {
                $projects = $projects->where('division', '=', $division);
            }
        }

        if (isset($query['search'])) {
            $search = $query['search'];
            if ($search) {
                $projects = $projects->where('title', 'like', "%$search%");
            }
        }

        $projects = $projects->paginate(4)->toArray();
        if (!$projects['data']) {
            session()->flash('error', '404');
        } else {
            session()->remove('error');
        }

        $components['user']          = $user;
        $components['projects']      = $projects;
        $components['progress_list'] = $progress_list;
        $components['division']      = $list_division;
        $components['request_url']   = $request_url;
        $components['params']        = $query;
        return view('pages.project', $components);
    }


    function search(Request $request)
    {
        if (!$request->has('keyword')) {
            return redirect()->to(route('project'));
        }
        $keyword = $request->get('keyword');
        return redirect()->to("/project?search=$keyword");
    }

    //---- non pages function
    function _addProject(Request $request)
    {
        if ($request->has('project')) {
            $project  = $request->get('project');
            $user     = $this->_getSession();


            if ($request->hasFile('thumbnail')) { # if thumbnail exist
                $thumbnail = $request->file('thumbnail');
                $mimeType  = $thumbnail->getMimeType();
                if ($mimeType == 'image/jpeg' || $mimeType == 'image/jpg' || $mimeType == 'image/png') { #--- create random name
                    $filename = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $thumbnail->getClientOriginalName());
                    $thumbnail->move(public_path('assets/thumbnail/'), $filename);
                    $project['image'] = $filename;
                }
            }

            if ($project) {
                $project['id_user'] = $user['id'];
                $save = Projects::updateOrCreate($project);
                if ($save) {
                    session()->flash('pesan', 'Project berhasil di simpan');
                    return redirect()->to(route('project'));
                }
            }
        }
    }

    function _detailProject(Request $request)
    {
        if ($request->has('id')) {
            $project_id = $request->get('id');
            $detail     = Projects::select(DB::raw("*, tb_project.id as project_id"))
                ->join('tb_user', 'tb_project.id_user', '=', 'tb_user.id')->where('tb_project.id', '=', $project_id)->first()->toArray();
            if (!$detail) {
                return response()->json("detail not found", 404);
            }


            #--- ambil comment
            $comment    = Comments::select('*')
                ->join('tb_user', 'tb_comment.id_user', '=', 'tb_user.id')->where('id_project', $project_id)->get()->toArray();
            return response()->json(array(
                'detail'    => $detail,
                'comment'   => $comment,
                'me'        => $this->_getSession()
            ), 200);
        } else {
            return response()->json("id is undefined", 404);
        }
    }

    function _updateProject(Request $request)
    {
        if ($request->has('project')) {
            $project = $request->get('project');
            $update  = Projects::where('id', $project['id'])->update($project);
            if ($update) {
                session()->flash('pesan', 'Project berhasil di update');
                return redirect()->to(route('project'));
            }
        }
    }

    function removeParam($url, $param)
    {
        $url = preg_replace('/(&|\?)' . preg_quote($param) . '=[^&]*$/', '', $url);
        $url = preg_replace('/(&|\?)' . preg_quote($param) . '=[^&]*&/', '$1', $url);
        return $url;
    }

    protected function _getSession()
    {
        return session()->get('user');
    }
}
