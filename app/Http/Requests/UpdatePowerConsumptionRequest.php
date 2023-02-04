<?php

namespace App\Http\Requests;
use App\Models\Building;
use Illuminate\Validation\Rule;

class UpdatePowerConsumptionRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'name'          => 'required|string|max:255',
            'building_type' => ['required', Rule::in(
                Building::DETACHED_HOUSE,
                Building::APARTMENT_BUILDING,
                Building::OFFICE_BUILDING,
                Building::INDUSTRIAL_BUILDING,
                Building::TOWN_HOUSE
            )],
            'heating_system' => 'required|string|max:255',
            'water_system'   => 'required|string|max:255'
        ];
    }
}
