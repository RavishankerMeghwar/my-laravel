<?php

namespace App\Models;

use App\Concerns\Flagable;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use Flagable;
    protected $fillable = ['key', 'english', 'german', 'other'];
    protected $appends = ['active'];

    public const FLAG_ACTIVE = 1;

    public function getActiveAttribute()
    {
        return ($this->flags & self::FLAG_ACTIVE) == self::FLAG_ACTIVE;
    }
}
