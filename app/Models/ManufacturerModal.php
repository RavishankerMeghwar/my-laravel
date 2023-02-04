<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturerModal extends Model
{
    use HasFactory;

    protected $hidden  = [
        'flags',
        'created_at',
        'updated_at'
    ];

    protected $table = "manufacturer_modal";

    public function manufacturer () {
        return $this->hasOne(Manufacturer::class, 'id', 'manufacturer_id');
    }

    public function modal () {
        return $this->hasOne(Modal::class, 'id', 'modal_id');
    }

}
