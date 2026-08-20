<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToUser
{
    protected static function bootBelongsToUser()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', auth()->id());
        });
    }
}