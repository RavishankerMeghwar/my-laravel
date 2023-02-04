<?php

namespace App\Models;

use App\Concerns\Flagable;
use Illuminate\Database\Eloquent\Model;

class TemplateComponent extends Model
{
    use Flagable;

    protected $table = 'template_component';

    public const FLAG_ACTIVE = 1;

    protected $hidden  = [
        'flags',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'active'
    ];

    public function getActiveAttribute()
    {
        return ($this->flags & self::FLAG_ACTIVE) == self::FLAG_ACTIVE;
    }

    public function components()
    {
        return $this->belongsTo(Component::class, 'component_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
    
}
