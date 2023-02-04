<?php

namespace App\Models;

use App\Concerns\Flagable;
use Illuminate\Database\Eloquent\Model;

class ComponentPrice extends Model
{
    use Flagable;

    public const FLAG_ACTIVE = 1;

    protected $hidden = [
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
    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id', 'id');
    }
    
}
