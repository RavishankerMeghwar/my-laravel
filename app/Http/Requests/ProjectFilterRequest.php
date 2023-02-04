<?php

namespace App\Http\Requests;

class ProjectFilterRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'per_page'   => 'nullable|integer',
            'active'     => 'nullable|max:255',
            'updated_at' => 'nullable|max:255',
            'search'     => 'nullable|max:255',
            'column'     => 'required|string|max:255', 
            'orderby'    => 'required|string|max:255'
        ];
    }
}
