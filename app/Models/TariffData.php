<?php

namespace App\Models;

use App\Concerns\Flagable;
use Illuminate\Database\Eloquent\Model;

class TariffData extends Model
{
    protected $table = "tariffs_data";

    use Flagable;
    public const MON_TO_FRI  = 'mon_to_fri';
    public const SATURDAY    = 'saturday';
    public const SUNDAY      = 'sunday';

    public const FLAG_ACTIVE       = 1;

    protected $hidden = [
        'flags',
        'created_at',
        'updated_at',
        'active'
    ];

    protected $appends = [
        'active',
    ];

    public function getActiveAttribute()
    {
        return ($this->flags & self::FLAG_ACTIVE) == self::FLAG_ACTIVE;
    }
    public function tariffs()
    {
        return $this->belongsTo(Tariff::class);
    }
}
