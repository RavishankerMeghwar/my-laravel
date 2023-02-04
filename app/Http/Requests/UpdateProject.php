<?php

namespace App\Http\Requests;

use App\Models\Project;
use App\Models\User;
use Illuminate\Validation\Rule;

class UpdateProject extends ApiRequest
{

    public function rules()
    {
        return [
            'organization_id'      => 'nullable|exists:organizations,id|integer',
            'project_template_id'  => 'nullable|integer|exists:project_templates,id',
            'power_consumption_id' => 'nullable|integer|exists:power_consumptions,id',
            'gender_type'         => ['bail', 'nullable', Rule::in([
                Project::GENDER_TYPE_MR,
                Project::GENDER_TYPE_WOMEN,
                Project::GENDER_TYPE_NOT_SPECIFIED
            ])],
            'customer_type' => ['bail', 'nullable', Rule::in([
                Project::CUSTOMER_TYPE_COMPANY,
                Project::CUSTOMER_TYPE_PRIVATE
            ])],
            'language' => ['bail', 'nullable', Rule::in([
                User::LANGUAGE_DUTCH,
                user::LANGUAGE_ENGLISH
            ])],
            'project_status' => ['bail', 'nullable', Rule::in([
                Project::PROJECT_CREATED,
                Project::OFFER_EXPECTED,
                Project::OFFER_SEND,
                Project::SOLD,
                Project::CUSTOMER_SERVICE,
                Project::OFFER_MADE,
                Project::OFFER_CHANGED,
                Project::OFFER_REJECTED,
                Project::PROJECT_COMPLETED
            ])],
            'title'                => 'bail|nullable|string|max:255',
            'first_name'           => 'bail|nullable|string|max:255',
            'surname'              => 'bail|nullable|string|max:255',
            'email'                => 'nullable|email|max:255',
            'telephone'            => 'nullable|digits_between:4,20|numeric',
            'address'              => 'nullable|string|max:255',
            'status'               => 'nullable|boolean',
            'reference'            => 'nullable|string|max:255',
            'commissioning_date'   => 'nullable|date_format:d.m.Y',
            'tax_saving'           => 'nullable|numeric',
            'vat'                  => 'nullable|numeric',
            'extended_calculation' => 'nullable|boolean',
            'street'               => 'nullable|string|max:255',
            'no'                   => 'nullable|integer',
            'postal_code'          => 'nullable|numeric',
            'location'             => 'nullable|string|max:255',
            'land'                 => 'nullable|string|max:255',
            'private_tel'          => 'nullable|digits_between:4,20|numeric',
            'mobile_phone'         => 'nullable|digits_between:4,20|numeric',
            'annual_consumption'   => 'nullable|numeric'
        ];
    }
}
