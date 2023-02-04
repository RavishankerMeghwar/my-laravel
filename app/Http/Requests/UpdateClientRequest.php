<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'gender_type'        => ['bail', 'nullable', Rule::in([
                Client::GENDER_TYPE_MR,
                Client::GENDER_TYPE_WOMEN,
                Client::GENDER_TYPE_NOT_SPECIFIED
            ])],
            'customer_type'      => ['bail', 'nullable', Rule::in([
                Client::CUSTOMER_TYPE_COMPANY,
                Client::CUSTOMER_TYPE_PRIVATE
            ])],
            'language'           =>  ['bail', 'nullable', Rule::in(config('languages'))],
            'middle_name'        => 'bail|nullable|string|max:255',
            'surname'            => 'bail|nullable|string|max:255',
            'phone'              => 'bail|nullable|max:255',
            'exterior'           => 'bail|nullable|max:255',      
            'number'             => 'bail|nullable|integer',    
            'zip_code'           => 'bail|nullable|max:255',      
            'place'              => 'bail|nullable|max:255',   
            'country'            =>  ['bail', 'nullable', Rule::in(config('countries'))],     
            'private_telephone'  => 'bail|nullable|max:255',       
            'business_telephone' => 'bail|nullable|max:255',            
            'billing_address'    => 'bail|nullable|string|max:255',
            'status'             => 'nullable|boolean'
        ];
    }
}
