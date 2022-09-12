<?php

namespace App\Http\Controllers\pages;

use App\Controllers\Project;
use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Projects;
use App\Models\Users;
use CodeIgniter\Entity\Cast\DatetimeCast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $projectModel;
    protected $activityModel;
    public function __construct()
    {
        $this->projectModel  = new Projects();
        $this->activityModel = new Activities();
    }

    public function index()
    {

        $user = $this->_getSession();
        if ($user['role'] == 'admin') {
            $recent_projects    = $this->projectModel->_getProjects(null, 3);
            $user_performance   = $this->projectModel->_getUserPerformance();
            $recent_activities  = $this->activityModel->_getRecentActivities(5);
            $last_interact      = $this->activityModel->_getLastInteract();
            $perfomance_chart   = $this->projectModel->_getPerformanceChart();

            $max_performance = false;
            if ($user_performance) {
                $max_performance   = max($user_performance);
            }
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
        return view('pages.dashboard', $components);
    }


    protected function _getSession()
    {
        return session()->get('user');
    }
}
