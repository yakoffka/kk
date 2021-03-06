<?php

namespace App\Filters\Customevent;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class UserFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        return $builder->whereIn('user_id', $value);
    }
}