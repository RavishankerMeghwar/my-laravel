<?php

namespace App\Models;

use App\Concerns\Flagable;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    use Flagable;

    public const FLAG_ACTIVE       = 1;

    protected $hidden = [
        'flags',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'active',
    ];

    public function getActiveAttribute()
    {
        return ($this->flags & self::FLAG_ACTIVE) == self::FLAG_ACTIVE;
    }
    public function tariffdata()
    {
        return $this->hasMany(TariffData::class,'tariff_id','id');
    }

    public function energysupplier()
    {
        return $this->belongsTo(EnergySupplier::class,'energy_supp_id','id');
    }
    
    
}
