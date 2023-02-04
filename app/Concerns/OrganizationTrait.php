<?php

namespace App\Concerns;

trait OrganizationTrait
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope());
    }
    
}
