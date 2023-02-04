<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringCostComponent extends Model
{
    use HasFactory;

    protected $table = 'recurring_cost_component';

    protected $hidden  = [
        'flags',
        'created_at',
        'updated_at'
    ];

    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id');
    }
}
