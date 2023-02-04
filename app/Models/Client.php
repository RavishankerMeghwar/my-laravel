<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\Flagable;
class Client extends Model
{
    use Flagable, SoftDeletes;

    protected $hidden = [
        'flags',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $appends = [
        'active'
    ];

    public const CUSTOMER_TYPE_COMPANY      = 'company';
    public const CUSTOMER_TYPE_PRIVATE      = 'private';
    
    public const GENDER_TYPE_MR             = 'mr';
    public const GENDER_TYPE_WOMEN          = 'women';
    public const GENDER_TYPE_NOT_SPECIFIED  = 'not_specified';

    public const FLAG_ACTIVE = 1;

    public function getActiveAttribute()
    {
        return ($this->flags & self::FLAG_ACTIVE) == self::FLAG_ACTIVE;
    }
}
