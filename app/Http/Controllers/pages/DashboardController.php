<?php

namespace App\Http\Controllers\pages;

use App\Controllers\Project;
use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Projects;
use App\Models\Users;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $projectModel;
    protected $activityModel;
    protected $userModel;
    public function __construct()
    {
        $this->projectModel  = new Projects();
        $this->activityModel = new Activities();
        $this->userModel     = new Users();
    }

    public function index(Request $request)
    {

        $user = $this->_getSession();
        if ($user['status'] == 'admin') {
            $recent_projects    = $this->projectModel->_getProjects(null, 3);
            $user_performance   = $this->projectModel->_getUserPerformance();
            $recent_activities  = $this->activityModel->_getRecentActivities(5);
            $last_interact      = $this->activityModel->_getLastInteract();
            $member             = $this->userModel->_getMember();
            $components['member']  = $member; #--- send member data

            $max_performance = false;
            if ($user_performance) {
                $max_performance   = max($user_performance);
            }


            $query  = $request->query->all(); # get query from url
            $perfomance_chart   = $this->projectModel->_getPerformanceChart(isset($query['user_id']) ? $query['user_id'] : null);
            $components['user_id'] = isset($query['user_id']) ? $query['user_id'] : null;
        } else {
            $recent_projects    = $this->projectModel->_getProjects($user['email'], 3);
            $user_performance   = $this->projectModel->_getUserPerformance($user['email']);
            $recent_activities  = $this->activityModel->_getRecentActivities(5, $user['email']);
            $last_interact      = $this->activityModel->_getLastInteract($user['email']);
            $perfomance_chart   = $this->projectModel->_getPerformanceChart($user['id']);

            $max_performance = false;
            if ($user_performance) {
                $max_performance   = max($user_performance);
            }
        }



        $components['recent_projects']      = $recent_projects;
        $components['recent_activities']    = $recent_activities;
        $components['performance']          = $user_performance;
        $components['max_performance']      = $max_performance;
        $components['last_interact']        = $last_interact;
        $components['performance_chart']    = $perfomance_chart;


        // dd($this->projectModel->_getPerformanceChart($user['id']));
        // dd($this->projectModel->distinct('progress')->groupBy('progress')->get()->toArray());
        return view('pages.dashboard', $components);
    }


    protected function _getSession()
    {
        return session()->get('user');
    }
}
