<?php

namespace App\Models;

use App\Concerns\Flagable;
use App\Concerns\OrganizationTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Flagable, SoftDeletes, OrganizationTrait;

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'flags',
        'reset_token',
        'verification_code',
        'created_at',
        'updated_at',
        'deleted_at',
        'email_verified',
        'profile_image'
    ];

    protected $appends = [
        'active',
        'email_verified',
        'is_approved'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //roles
    public const ROLE_MANAGER       = 'manager';
    public const ROLE_EMPLOYEE      = 'employee';
    public const ROLE_SUPER_ADMIN   = 'superadmin';
    public const ROLE_STOCK_MANAGER = 'stock_manager';
    public const ROLE_PLUMBER       = 'plumber';
    //languages
    public const LANGUAGE_DUTCH   = 'dutch';
    public const LANGUAGE_ENGLISH = 'english';
    public const LANGUAGE_OTHER   = 'other';
    //flags
    public const FLAG_ACTIVE         = 1;
    public const FLAG_EMAIL_VERIFIED = 2;
    public const FLAG_STATUS         = 4;

    // Flagable methods
    public function getActiveAttribute()
    {
        return ($this->flags & self::FLAG_ACTIVE) == self::FLAG_ACTIVE;
    }

    public function getEmailVerifiedAttribute()
    {
        return ($this->flags & self::FLAG_EMAIL_VERIFIED) == self::FLAG_EMAIL_VERIFIED;
    }

    public function getIsApprovedAttribute()
    {
        return ($this->flags & self::FLAG_STATUS) == self::FLAG_STATUS;
    }

    // Other methods
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)->withoutOrganization()->first();;
    }
}
