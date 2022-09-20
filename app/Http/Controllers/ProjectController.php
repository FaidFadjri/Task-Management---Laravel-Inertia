<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Comments;
use App\Models\Files;
use App\Models\Projects;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class ProjectController extends Controller
{
    protected $usersModel;
    protected $token        = "asdasds15a454asdasdnasasbduasdguasdbasdas1das7dasd4asdasd4asd";
    protected $API_URL      = "https://project.akastra.id/";
    protected $filesModel;

    public function __construct()
    {
        $this->usersModel = new Users();
        $this->filesModel = new Files();
    }

    function index(Request $request)
    {
        $query  = $request->query->all(); # get query from url
        $user   = $this->_getSession(); # get user data check if they member or admin

        $progress_list = ['TO DO', 'WORKING ON IT', 'PENDING', 'STUCK', 'COMPLETE'];
        $list_division = $this->usersModel->_getDivision();
        $request_url   = str_replace("/project", "", str_replace("/project?", "&", $this->removeParam($request->getRequestUri(), "page")));

        # getting projects data
        $projects               = Projects::select(DB::raw("progress, priority, project, description, estimation_cost, created_at, tb_project.id as project_id, tb_user.division, tb_user.company, tb_project.image, tb_project.updated_at"))->join('tb_user', 'tb_project.id_user', '=', 'tb_user.id')->orderBy('tb_project.updated_at', 'DESC');
        if ($user['status'] == 'member') {
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
                $projects = $projects->where('project', 'like', "%$search%");
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

                    #--- simpan aktivitas
                    $activity = [
                        'activity' => 'Menambahkan project baru "' . $project['project'] . '"',
                        'id_user'  => $user['id']
                    ];
                    Activities::updateOrCreate($activity);
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

            $files      = $this->filesModel->_getFilesByProject($project_id);

            return response()->json(array(
                'detail'    => $detail,
                'comment'   => $comment,
                'files'     => $files,
                'me'        => $this->_getSession(),
                'api_url'   => $this->API_URL
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

            #---- check if files exist
            if ($request->hasFile('file')) {
                $file     = $request->file('file');
                $response = Http::withToken($this->token)->attach('file', $file, $file->getClientOriginalName())->post($this->API_URL . "add_files", [
                    'project_id' => $project['id']
                ]);
                if ($response->status() == 200) {
                    session()->flash('pesan', 'Files berhasil di upload');
                } else {
                    session()->flash('pesan', 'Gagal upload files');
                }
            }


            if ($update) {

                #---- simpan aktivitas
                $activity = [
                    'activity' => 'Update project "' . $project['project'] . '"',
                    'id_user'  => $this->_getSession()['id']
                ];

                Activities::updateOrCreate($activity);
                session()->flash('pesan', 'Project berhasil di update');
                return redirect()->to(route('project'));
            }
        }
    }

    function _deleteProject($project_id)
    {

        #---- get project
        $project = Projects::find($project_id);
        if ($project->image) {
            if (file_exists(public_path('assets/thumbnail/' . $project->image))) {
                #--- delete thumbnail if exist
                unlink(public_path('assets/thumbnail/' . $project->image));
            }
        }

        #---- get files
        $response = Http::withToken($this->token)->delete($this->API_URL . "delete_files/" . $project_id);
        if ($response) {
            $delete_project = Projects::where('id', '=', $project_id)->delete();

            #---- simpan aktivitas
            $activity = [
                'activity' => 'Delete project "' . $project['project'] . '"',
                'id_user'  => $this->_getSession()['id']
            ];

            Activities::updateOrCreate($activity);


            if ($delete_project) {
                return response()->json("data has been deleted", 200);
            } else {
                return response()->json("server error project not deleted", 500);
            }
        } else {
            return response()->json("api error", 500);
        }
    }


    function _getAccount()
    {
        $id   = $this->_getSession()['id'];
        $user = Users::find($id);
        return response()->json($user, 200);
    }

    function _saveUser(Request $request)
    {
        if ($request->has('user')) {
            $user = $request->get('user');
            Users::where('email', '=', $user['email'])->update($user);
            session()->flash('pesan', 'data berhasil di update');
            return redirect()->to('/');
        }
    }

    function _addComment(Request $request)
    {
        if ($request->has('comment') && $request->has('id_project')) {
            $comment = [
                'comment'   => $request->get('comment'),
                'id_user'   => $this->_getSession()['id'],
                'id_project' => $request->get('id_project')
            ];

            $save = Comments::updateOrCreate($comment);
            if ($save) {
                return response()->json(array(
                    'message' => "Data has been send!",
                    'user'    => $this->_getSession()
                ), 200);
            } else {
                return response()->json(array(
                    'message' => "database error",
                ), 500);
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
