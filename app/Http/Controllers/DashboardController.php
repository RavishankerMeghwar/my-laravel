<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function homeManager()
    {
        $user = is_manager();
        if ($user) {
            $employees        = User::where('role', 'employee')->count();
            $projects         = Project::count();
            $activeProjects   = Project::whereRaw('flags & ?', [Project::FLAG_ACTIVE])->count();
            $completeProjects = Project::where('project_status','project_completed')->count();
            $cancelProjects   = Project::where('project_status','offer_rejected')->count();
            $components       = Component::all()->count();
            return response()->json([
                'employees'        => $employees, 
                'projects'         => $projects,
                'activeProjects'   => $activeProjects,
                'completeProjects' => $completeProjects,
                'cancelProjects'   => $cancelProjects,
                'components'       => $components
            ]);
        }
    }

    public function homeAdmin(Request $request)
    {
        return $request;
    }
}
