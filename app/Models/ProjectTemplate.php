<?php

namespace App\Models;

use App\Concerns\Flagable;
use Illuminate\Database\Eloquent\Model;

class ProjectTemplate extends Model
{
    use Flagable;

    public const FLAG_ACTIVE            = 1;
    public const FLAG_BATTERY_STORAGE   = 2;
    public const FLAG_ENERGY_MANAGEMENT = 4;
    public const FLAG_PHOTOVOLTAIC      = 8;


    protected $hidden = [
        'flags',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'active',
        'battery_storage',
        'energy_management',
        'photovoltaic'
    ];

    public function getActiveAttribute()
    {
        return ($this->flags & self::FLAG_ACTIVE) == self::FLAG_ACTIVE;
    }

    public function getBatteryStorageAttribute()
    {
        return ($this->flags & self::FLAG_BATTERY_STORAGE) == self::FLAG_BATTERY_STORAGE;
    }

    public function getEnergyManagementAttribute()
    {
        return ($this->flags & self::FLAG_ENERGY_MANAGEMENT) == self::FLAG_ENERGY_MANAGEMENT;
    }

    public function getPhotovoltaicAttribute()
    {
        return ($this->flags & self::FLAG_PHOTOVOLTAIC) == self::FLAG_PHOTOVOLTAIC;
    }

    public function template_components()
    {
        return $this->hasMany(TemplateComponent::class, 'template_id');
    }
    public function pv_module()
    {
        return $this->belongsTo(Component::class, 'pv_module', 'id');
    }
    public function inverter()
    {
        return $this->belongsTo(Component::class, 'inverter', 'id');
    }
    public function sub_structure()
    {
        return $this->belongsTo(Component::class, 'sub_structure', 'id');
    }
}
