<?php

namespace App\Concerns;

trait Flagable {

    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootFlagable()
    {
        static::addGlobalScope(new FlagableScope);
    }

    public function addFlag($flag) {
        $this->flags |= $flag;
    }

    public function removeFlag($flag) {
        $this->flags &= (~$flag);
    }
    
    public function updateFlag($flag, $value) {
        if(!!$value) {
            $this->flags |= $flag;
        } else {
            $this->flags &= (~$flag);
        }
    }
    
}