<?php

namespace App\Models;

use App\Concerns\Flagable;
use Illuminate\Database\Eloquent\Model;

class Modal extends Model
{
    use Flagable;

    public const FLAG_ACTIVE = 1;

    protected $hidden  = [
        'flags',
        'image',
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

    public function manufacturers()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id', 'id');
    }
    
    public function information()
    {
        return $this->hasMany(ModalInformation::class, 'modal_id', 'id');
    }
}
