<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\Flagable;
use App\Concerns\OrganizationTrait;
class Project extends Model
{
    use Flagable, SoftDeletes, OrganizationTrait;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'flags',
        'created_at',
        'deleted_at'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $appends = [
        'active',
        'battery_storage',
        'energy_management',
        'photovoltaic',
        'extended_calculation'
    ];

    public const CUSTOMER_TYPE_COMPANY      = 'company';
    public const CUSTOMER_TYPE_PRIVATE      = 'private';
    
    //gender types
    public const GENDER_TYPE_MR             = 'mr';
    public const GENDER_TYPE_WOMEN          = 'women';
    public const GENDER_TYPE_NOT_SPECIFIED  = 'not_specified';
    
    //phone types
    public const PHONE_TYPE_PRIVATE = 'private_tel';
    public const PHONE_TYPE_MOBILE  = 'mobile_phone';
    public const PHONE_TYPE_STORE   = 'telephone';
    
    //status of project
    public const PROJECT_CREATED   = 'project_created';
    public const OFFER_EXPECTED    = 'offer_expected';
    public const OFFER_SEND        = 'offer_sent';
    public const SOLD              = 'sold';
    public const CUSTOMER_SERVICE  = 'customer_service';
    public const OFFER_MADE        = 'offer_made';
    public const OFFER_CHANGED     = 'offer_changed';
    public const OFFER_REJECTED    = 'offer_rejected';
    public const PROJECT_COMPLETED = 'project_completed';

    //flags
    public const FLAG_ACTIVE               = 1;
    public const FLAG_EXTENDED_CALCULATION = 2;
    public const FLAG_BATTERY_STORAGE      = 4;
    public const FLAG_ENERGY_MANAGEMENT    = 8;
    public const FLAG_PHOTOVOLTAIC         = 16;

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

    public function getExtendedCalculationAttribute()
    {
        return ($this->flags & self::FLAG_EXTENDED_CALCULATION) == self::FLAG_EXTENDED_CALCULATION;
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(ProjectTemplate::class, 'project_template_id', 'id');
    }

    public function power_consumption()
    {
        return $this->belongsTo(PowerConsumption::class, 'power_consumption_id', 'id');
    }

    public function building()
    {
        return $this->hasOne(Building::class, 'project_id', 'id');
    }

    public function pvmodule()
    {
        return $this->hasOne(PvModule::class, 'project_id', 'id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)->withoutOrganization()->first();
    }
}
