<?php

namespace App\Http\Controllers;

use App\Models\ProjectTemplate;
use App\Http\Requests\StoreProjectTemplateRequest;
use App\Http\Requests\UpdateProjectTemplateRequest;
use App\Models\RecurringCostComponent;
use App\Models\TemplateComponent;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectTemplateController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $projectTemplates = ProjectTemplate::when($request->search, function ($query) use ($request) {
            $query->where(function ($whenQuery) use ($request) {
                $whenQuery->orWhere('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $request->search . '%');
            });
        })->when($request->battery_storage, function ($query) {
            $query->whereRaw('flags & ?', [ProjectTemplate::FLAG_BATTERY_STORAGE]);
        })->when($request->energy_management, function ($query) {
            $query->whereRaw('flags & ?', [ProjectTemplate::FLAG_ENERGY_MANAGEMENT]);
        })->when($request->photovoltaic, function ($query) {
            $query->whereRaw('flags & ?', [ProjectTemplate::FLAG_PHOTOVOLTAIC]);
        })->with('template_components.components.componenttypes', 'pv_module', 'inverter', 'sub_structure')
            ->orderBy($request->column, $request->orderby)->paginate($per_page);

        return $projectTemplates;
    }

    public function getManager(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $managers = User::where('role', User::ROLE_MANAGER)->paginate($per_page);
        return $managers;
    }

    public function store(StoreProjectTemplateRequest $request)
    {
        $projectTemplate              = new ProjectTemplate();
        $projectTemplate->title       = $request->title;
        $projectTemplate->description = $request->description;

        $projectTemplate->addFlag(ProjectTemplate::FLAG_ACTIVE);

        $projectTemplate->updateFlag(ProjectTemplate::FLAG_BATTERY_STORAGE, $request->battery_storage);

        $projectTemplate->updateFlag(ProjectTemplate::FLAG_ENERGY_MANAGEMENT, $request->energy_management);

        $projectTemplate->updateFlag(ProjectTemplate::FLAG_PHOTOVOLTAIC, $request->photovoltaic);

        if ($projectTemplate->save()) {
            if ($request->has('components')) {
                foreach ($request->components as $value) {
                    $groupComponent               = new TemplateComponent();
                    $groupComponent->template_id  = $projectTemplate->id;
                    $groupComponent->component_id = $value;
                    $groupComponent->save();
                }
            }
            return $projectTemplate;
        }
        return response('Project Template not Added', 500);
    }

    public function show(ProjectTemplate $projectTemplate)
    {
        if ($projectTemplate)
            return $projectTemplate->with(
                'template_components.components.componenttypes',
                'template_components.group',
                'pv_module.componenttypes',
                'inverter.componenttypes',
                'sub_structure.componenttypes'
            )->findOrFail($projectTemplate->id);
        return response('No records found', 500);
    }

    public function edit(ProjectTemplate $projectTemplate)
    {
        //
    }

    public function update(UpdateProjectTemplateRequest $request, ProjectTemplate $projectTemplate)
    {
        $projectTemplate->pv_module     = $request->input('pv_module', $projectTemplate->pv_module);
        $projectTemplate->inverter      = $request->input('inverter', $projectTemplate->inverter);
        $projectTemplate->sub_structure = $request->input('sub_structure', $projectTemplate->sub_structure);
        $projectTemplate->title         = $request->input('title', $projectTemplate->title);
        $projectTemplate->description   = $request->input('description', $projectTemplate->description);

        // $projectTemplate->updateFlag(ProjectTemplate::FLAG_ACTIVE, $request->status); //TODO: IF NEEDED
        // $projectTemplate->updateFlag(ProjectTemplate::FLAG_BATTERY_STORAGE, $request->battery_storage);
        // $projectTemplate->updateFlag(ProjectTemplate::FLAG_ENERGY_MANAGEMENT, $request->energy_management);
        // $projectTemplate->updateFlag(ProjectTemplate::FLAG_PHOTOVOLTAIC, $request->photovoltaic);

        if ($projectTemplate->save()) {
            if ($request->has('components') && sizeof($request->components)) {
                foreach ($request->components as $value) {
                    $templateComponent               = new TemplateComponent();
                    $templateComponent->template_id  = $projectTemplate->id;
                    $templateComponent->component_id = $value['id'];
                    $templateComponent->group_id     = $value['group'];
                    if (!$templateComponent->save())
                        return response('Some thing wrong', 500);
                }
            }
            if ($request->has('delete_components') && sizeof($request->delete_components)) {
                TemplateComponent::where('template_id', $projectTemplate->id)->whereIn('component_id', $request->delete_components)->delete();
            }

            if ($request->has('recurring_costs') && sizeof($request->recurring_costs)) {
                foreach ($request->recurring_costs as $value) {
                    $recurring_cost_Comp                    = new RecurringCostComponent();
                    $recurring_cost_Comp->template_id       = $projectTemplate->id;
                    $recurring_cost_Comp->recurring_cost_id = $value['id'];
                    if (!$recurring_cost_Comp->save())
                        return response('Some thing wrong', 500);
                }
            }
            if ($request->has('delete_recurring_costs') && sizeof($request->delete_recurring_costs)) {
                TemplateComponent::where('template_id', $projectTemplate->id)->whereIn('component_id', $request->delete_recurring_costs)->delete();
            }
            return $projectTemplate;
        }
        return response('Project Template not update', 500);
    }

    public function destroy(ProjectTemplate $projectTemplate)
    {
        //
    }
}
