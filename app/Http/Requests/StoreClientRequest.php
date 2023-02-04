<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Validation\Rule;

class StoreClientRequest extends ApiRequest
{
   
    public function rules()
    {
        return [
            'gender_type'        => ['bail', 'required', Rule::in([
                Client::GENDER_TYPE_MR,
                Client::GENDER_TYPE_WOMEN,
                Client::GENDER_TYPE_NOT_SPECIFIED
            ])],
            'customer_type'      => ['bail', 'required', Rule::in([
                Client::CUSTOMER_TYPE_COMPANY,
                Client::CUSTOMER_TYPE_PRIVATE
            ])],
            'language'           =>  ['bail', 'required', Rule::in(config('languages'))],
            'middle_name'        => 'bail|required|string|max:255',
            'surname'            => 'bail|required|string|max:255',
            'email'              => 'bail|required|email|max:255|unique:clients,email',
            'phone'              => 'bail|required|max:255',
            'exterior'           => 'bail|required|max:255',      
            'number'             => 'bail|required|integer',    
            'zip_code'           => 'bail|required|max:255',      
            'place'              => 'bail|required|max:255',   
            'country'            =>  ['bail', 'required', Rule::in(config('countries'))],     
            'private_telephone'  => 'bail|required|max:255',       
            'business_telephone' => 'bail|required|max:255',            
            'billing_address'    => 'bail|required|string|max:255'
        ];
    }
}
