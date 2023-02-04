<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComponentFilterRequest;
use Illuminate\Http\Request;
use App\Http\Requests\ComponentRequest;
use App\Http\Requests\UpdateComponentRequest;
use App\Models\Component;
use App\Models\ComponentPrice;
use App\Models\ManufacturerModal;
use Illuminate\Support\Facades\Storage;

class ComponentController extends Controller
{
    public function index(ComponentFilterRequest $request)
    {
        $per_page = $request->per_page ?? 10;
        $components = Component::when($request->search, function ($query) use ($request) {
            $query->where(function ($whenQuery) use ($request) {
                $whenQuery->orWhere('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('item_number', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('unit', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $request->search . '%');
            });
        })->when($request->active, function ($query) {
            $query->whereRaw('flags & ?', [Component::FLAG_ACTIVE]);
        })->when($request->inactive, function ($query) {
            $query->whereRaw('flags & ? != ?', [Component::FLAG_ACTIVE, Component::FLAG_ACTIVE]);
        })->when($request->battery_storage, function ($query) {
            $query->whereRaw('flags & ?', [Component::FLAG_BATTERY_STORAGE]);
        })->when($request->energy_management, function ($query) {
            $query->whereRaw('flags & ?', [Component::FLAG_ENERGY_MANAGEMENT]);
        })->when($request->photovoltaic, function ($query) {
            $query->whereRaw('flags & ?', [Component::FLAG_PHOTOVOLTAIC]);
        })->when($request->componenttype, function ($query) use ($request) {
            $query->whereHas('componenttypes', function ($query) use ($request) {
                $query->Where('title', 'like', '%' . $request->componenttype . '%');
            });
        })->with(
            'componentprice',
            'componenttypes',
            'manufacturerModal.manufacturer',
            'manufacturerModal.modal',
            'organizations'
        )
            ->orderBy($request->column, $request->orderby)
            ->paginate($per_page);

        return $components;
    }


    public function activeComponent(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $component = Component::whereRaw('flags & ?', [Component::FLAG_ACTIVE])
            ->orderBy('created_at', 'desc')
            ->with('componentprice')->paginate($per_page);
        return $component;
    }

    public function store(ComponentRequest $request)
    {
        $component                      = new Component();
        $component->organization_id     = $request->organization_id;
        $component->component_type_id   = $request->component_type_id;
        $component->name                = $request->name;
        $component->item_number         = $request->item_number;
        $component->quantity            = $request->quantity ?? 0;
        $component->description         = $request->description;
        $component->save(); //TODO:
        if ($request->hasFile('image'))
            $image = $request->file('image')->store('components/' . $component->id);
        $component->image = $image ?? null;

        $component->addFlag(Component::FLAG_ACTIVE);

        $component->updateFlag(Component::FLAG_BATTERY_STORAGE, $request->battery_storage);

        $component->updateFlag(Component::FLAG_ENERGY_MANAGEMENT, $request->energy_management);

        $component->updateFlag(Component::FLAG_PHOTOVOLTAIC, $request->photovoltaic);

        if ($request->has('type') && $request->type == 'custom') {
            $component->updateFlag(Component::FLAG_COMPONENT_CUSTOM, $request->is_custom);
            if (!$component->save())
                return response('Component not added', 500);

            return $component;
        } else {
            if ($component->save()) {

                if ($request->has('manufacturer_id') && $request->has('modal_id')) {
                    $manufacturer_modal                  = new ManufacturerModal();
                    $manufacturer_modal->manufacturer_id = $request->manufacturer_id;
                    $manufacturer_modal->modal_id        = $request->modal_id;
                    $manufacturer_modal->component_id    = $component->id;
                    $manufacturer_modal->save();
                }
            }
            $component->name = $manufacturer_modal->modal->title . '-' . $manufacturer_modal->manufacturer->manufacturer;
            if ($component->save())
                return  $component;
        }
        return response('Component not added', 500);
    }

    public function update(UpdateComponentRequest $request, Component $component)
    {
        $component->organization_id     = $request->input('organization_id', $component->organization_id);
        $component->component_type_id   = $request->input('component_type_id', $component->component_type_id);
        $component->name                = $request->input('name', $component->name);
        $component->item_number         = $request->input('item_number', $component->item_number);
        $component->description         = $request->input('description', $component->description);
        $component->price_dependency    = $request->input('price_dependency', $component->price_dependency);
        $component->price_type          = $request->input('price_type', $component->price_type);
        $component->price_definition    = $request->input('price_definition', $component->price_definition);
        $component->price_repetition    = $request->input('price_repetition', $component->price_repetition);
        $component->quantity            = $request->input('quantity', $component->quantity) ?? 0;

        if ($request->hasFile('image')) {
            if ($component->image) Storage::delete($component->image);
            $image = $request->file('image')->store('components/' . $component->id);
            $component->image = $image ?? $component->image;
        }

        if ($request->has('status') && $request->filled('status')) {
            $component->updateFlag(Component::FLAG_ACTIVE, $request->status);
        }
        if ($request->has('battery_storage') && $request->filled('battery_storage')) {
            $component->updateFlag(Component::FLAG_BATTERY_STORAGE, $request->battery_storage);
        }

        if ($request->has('energy_management') && $request->filled('energy_management')) {
            $component->updateFlag(Component::FLAG_ENERGY_MANAGEMENT, $request->energy_management);
        }

        if ($request->has('photovoltaic') && $request->filled('photovoltaic')) {
            $component->updateFlag(Component::FLAG_PHOTOVOLTAIC, $request->photovoltaic);
        }

        if ($request->has('is_custom') && $request->filled('is_custom')) {
            $component->updateFlag(Component::FLAG_COMPONENT_CUSTOM, $request->is_custom);
        }

        if ($request->has('prices') && $request->prices) {

            if ($request->has('id') && $request->id) {
                $componentPrice = ComponentPrice::find($request->id);
            } else {
                $componentPrice = new ComponentPrice();
            }
            $componentPrice->component_id          = $component->id;
            $componentPrice->price_level           = $request->price_level;
            $componentPrice->cost_price            = $request->cost_price;
            $componentPrice->calculation_surcharge = $request->calculation_surcharge;
            $componentPrice->installation_cost     = $request->installation_cost;
            $componentPrice->selling_price         = $request->selling_price;
            $componentPrice->addFlag(ComponentPrice::FLAG_ACTIVE);

            if (!$componentPrice->save())
                return response('Component price not added');
            return $componentPrice;
        }

        if ($component->save()) {

            if ($request->has('manufacturer_id') && $request->has('modal_id')) {
                ManufacturerModal::where('component_id', $component->id)->delete();
                $manufacturer_modal                  = new ManufacturerModal();
                $manufacturer_modal->manufacturer_id = $request->manufacturer_id;
                $manufacturer_modal->modal_id        = $request->modal_id;
                $manufacturer_modal->component_id    = $component->id;
                $manufacturer_modal->save();
            }
        }
        return $component;

        return response('Component not updated', 500);
    }

    public function componentImage(Component $component)
    {
        return Storage::download($component->image);
    }

    public function show(Component $component)
    {
        if ($component)
            return $component->with(
                'componentprice',
                'componenttypes',
                'manufacturerModal.manufacturer',
                'manufacturerModal.modal',
                'manufacturerModal.modal.information'
            )
                ->findOrFail($component->id);
        // $component_name = $component->manufacturerModal->manufacturer->manufacturer . ' ' . $component->manufacturerModal->modal->title;//TODO:
        // return (object) ['component'=>$component, 'name'=> $component_name];
        return response('No records found', 500);
    }

    public function destroy(Component $component)
    {
        if (!$component->delete())
            return response('Component did not delete', 500);

        return response('Component deleted successfully');
    }
}
