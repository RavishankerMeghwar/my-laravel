<?php

namespace App\Concerns;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Http\Request;

class OrganizationScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var array
     */
    protected $extensions = ['withoutOrganization'];

    public function apply(Builder $builder, Model $model)
    {
        if (request()->user)
            $builder->Where('organization_id', request()->user->organization_id);
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }


    public function addWithoutOrganization(Builder $builder)
    {
        $builder->macro('withoutOrganization', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
