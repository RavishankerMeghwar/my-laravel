<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFilterRequest;
use App\Http\Requests\StoreProject;
use App\Http\Requests\UpdateProject;
use Illuminate\Http\Request;

use App\Models\Project;

class ProjectController extends Controller
{
    public function index(ProjectFilterRequest $request)
    {
        $per_page = $request->per_page ?? 10;
        $projects = Project::when($request->search, function ($query) use ($request) {
            $query->where(function($queryWhen) use ($request){
                $queryWhen->orWhere('title', 'LIKE', '%' . $request->search . '%')
                ->orWhere('gender_type', 'LIKE', '%' . $request->search . '%')
                ->orWhere('first_name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('reference', 'LIKE', '%' . $request->search . '%');
            });
        })->when($request->status, function ($query) use ($request) {
            $query->where('project_status', $request->status);
        })->when($request->updated_at, function ($query) use ($request) {
            $query->where('updated_at', $request->updated_at);
        })->when($request->language, function ($query) use ($request) {
            $query->where('language', $request->language);
        })->when($request->active, function ($query) {
            $query->whereRaw('flags & ?', [Project::FLAG_ACTIVE]);
        })->when($request->battery_storage, function ($query) {
            $query->whereRaw('flags & ?', [Project::FLAG_BATTERY_STORAGE]);
        })->when($request->energy_management, function ($query) {
            $query->whereRaw('flags & ?', [Project::FLAG_ENERGY_MANAGEMENT]);
        })->when($request->photovoltaic, function ($query) {
            $query->whereRaw('flags & ?', [Project::FLAG_PHOTOVOLTAIC]);
        })->when($request->manager, function ($query) use ($request) {
            $query->whereHas('users', function ($users) use ($request) {
                $users->where('first_name', 'LIKE',  '%' . $request->manager . '%')
                    ->orWhere('last_name', 'LIKE',  '%' . $request->manager . '%');
            });
        })->when($request->organization, function ($query) use ($request) {
            $query->whereHas('organizations', function ($org) use ($request) {
                $org->Where('title', 'like', '%' . $request->organization . '%');
            });
        })
            ->with(['users', 'organizations', 'template', 'power_consumption'])
            ->orderBy($request->column, $request->orderby)
            ->paginate($per_page);
        return $projects = response()->json($projects);
    }

    public function store(StoreProject $request)
    {
        $project                        = new Project;
        $project->user_id               = request()->user->id;
        $project->organization_id       = $request->organization_id;
        $project->power_consumption_id  = $request->power_consumption_id;
        $project->project_template_id   = $request->project_template_id;
        $project->customer_type         = $request->customer_type;
        $project->gender_type           = $request->gender_type;
        $project->title                 = $request->title;
        $project->first_name            = $request->first_name;
        $project->surname               = $request->surname;
        $project->email                 = $request->email;
        $project->language              = $request->language;
        $project->address               = $request->address;
        $project->reference             = $request->reference;
        $project->commissioning_date    = $request->commissioning_date;
        $project->tax_saving            = $request->tax_saving;
        $project->vat                   = $request->vat;
        $project->street                = $request->street;
        $project->no                    = $request->no;
        $project->postal_code           = $request->postal_code;
        $project->location              = $request->location;
        $project->land                  = $request->land;
        $project->annual_consumption    = $request->annual_consumption;
        $project->phone_type            = $request->phone_type;
        $project->mobile_phone          = $request->mobile_phone;
        $project->private_tel           = $request->private_tel;
        $project->telephone             = $request->telephone;

        $project->addFlag(Project::FLAG_ACTIVE);

        $project->updateFlag(Project::FLAG_BATTERY_STORAGE, $request->battery_storage);

        $project->updateFlag(Project::FLAG_ENERGY_MANAGEMENT, $request->energy_management);

        $project->updateFlag(Project::FLAG_PHOTOVOLTAIC, $request->photovoltaic);

        $project->updateFlag(Project::FLAG_EXTENDED_CALCULATION, $request->extended_calculation);

        if ($project->save()) return $project;
        return response('Project did not created!', 500);
    }

    public function show(Project $project)
    {
        if ($project)
            return $project->with('users', 'organizations', 'template', 'building', 'power_consumption')->findOrFail($project->id);
        return response('No records found', 500);
    }

    public function update(UpdateProject $request, Project $project)
    {
        $project->user_id              = $request->input('user_id', $project->user_id);
        $project->power_consumption_id = $request->input('power_consumption_id', $project->power_consumption_id);
        $project->organization_id      = $request->input('organization_id', $project->organization_id);
        $project->project_template_id  = $request->input('project_template_id', $project->project_template_id);
        $project->customer_type        = $request->input('customer_type', $project->customer_type);
        $project->gender_type          = $request->input('gender_type', $project->gender_type);
        $project->title                = $request->input('title', $project->title);
        $project->first_name           = $request->input('first_name', $project->first_name);
        $project->surname              = $request->input('surname', $project->surname);
        $project->email                = $request->input('email', $project->email);
        $project->telephone            = $request->input('telephone', $project->telephone);
        $project->phone_type           = $request->input('phone_type', $project->phone_type);
        $project->language             = $request->input('language', $project->language);
        $project->project_status       = $request->input('project_status', $project->project_status);
        $project->address              = $request->input('address', $project->address);
        $project->reference            = $request->input('reference', $project->reference);
        $project->commissioning_date   = $request->input('commissioning_date', $project->commissioning_date);
        $project->tax_saving           = $request->input('tax_saving', $project->tax_saving);
        $project->vat                  = $request->input('vat', $project->vat);
        $project->street               = $request->input('street', $project->street);
        $project->no                   = $request->input('no', $project->no);
        $project->postal_code          = $request->input('postal_code', $project->postal_code);
        $project->location             = $request->input('location', $project->location);
        $project->land                 = $request->input('land', $project->land);
        $project->private_tel          = $request->input('private_tel', $project->private_tel);
        $project->mobile_phone         = $request->input('mobile_phone', $project->mobile_phone);
        $project->annual_consumption   = $request->input('annual_consumption', $project->annual_consumption);

        // if ($request->has('status') && $request->status) {
        //     $project->updateFlag(Project::FLAG_ACTIVE, $request->status);
        // }
        if ($request->has('extended_calculation') && $request->filled('extended_calculation')) {
            $project->updateFlag(Project::FLAG_EXTENDED_CALCULATION, $request->extended_calculation);
        }

        if ($project->save()) return $project;
        return response('No records found', 500);
    }

    public function destroy(Project $project)
    {
        if ($project->delete()) return response('Project deleted successfully');
        return  response('Project did not delete', 500);
    }
}
