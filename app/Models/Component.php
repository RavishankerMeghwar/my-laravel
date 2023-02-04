<?php

namespace App\Models;

use App\Concerns\Flagable;
use App\Concerns\OrganizationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Component extends Model
{
    use Flagable, OrganizationTrait;

    //flags
    public const FLAG_ACTIVE            = 1;
    public const FLAG_BATTERY_STORAGE   = 2;
    public const FLAG_ENERGY_MANAGEMENT = 4;
    public const FLAG_PHOTOVOLTAIC      = 8;
    public const FLAG_COMPONENT_CUSTOM  = 16;

    //price parameter -price dependency -price-type
    public const AMPERE                    = 'ampere';
    public const NUMBER_OF_GENERAL_COUNTER = ' number_general_counter';
    public const NUMBER_OF_PARTY           = 'number_of_party';
    public const WORKING_HOUR              = 'working_hour';
    public const GENERATOR_AREA            = 'generator_area';
    public const WEIGHT                    = 'weight';
    public const CUBIC_METER               = 'cubic_meter';
    public const METER                     = 'meter';
    public const POWER                     = 'power';
    public const PALETTE                   = 'palette';
    public const LUMP_SUM                  = 'lump_sum';
    public const PER_MODULE                = 'per_module';
    public const SQUARE_METER              = 'square_meter';
    public const TARGET_PRICE              = 'target_price';
    public const ROLE                      = 'role';
    public const PIECE                     = 'piece';
    public const PROPORTIONAL              = 'proportional';
    public const PLUGGED                   = 'plugged';
    
    //price definition
    public const NORMAL_NOT_IN_ECONOMY = 'normal_not_in_economy';
    public const NORMAL_IN_ECONOMY     = 'normal_in_economy';
    //Price repitition
    public const MONTHLY               = 'monthly';
    public const QUARTERLY             = 'quarterly';
    public const YEARLY                = 'yearly';

    protected $hidden = [
        'flags',
        'created_at',
        'updated_at'
    ];
    
    protected $appends = [
        'active',
        'battery_storage',
        'energy_management',
        'photovoltaic',
        'is_custom'
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

    public function getIsCustomAttribute()
    {
        return ($this->flags & self::FLAG_COMPONENT_CUSTOM) == self::FLAG_COMPONENT_CUSTOM;
    }

    public function componenttypes()
    {
     return $this->belongsTo(ComponentType::class, 'component_type_id', 'id');
    }

    public function manufacturers()
    {
     return $this->belongsTo(Manufacturer::class, 'manufacturer_id', 'id');
    }

    public function modals()
    {
     return $this->belongsTo(Modal::class, 'modal_id', 'id');
    }
    
    public function manufacturerModal()
    {
        return $this->hasOne(ManufacturerModal::class,'component_id','id');
    }

    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function componentprice()
    {
        return $this->hasMany(ComponentPrice::class, 'component_id', 'id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)->withoutOrganization()->first();
    }

}
