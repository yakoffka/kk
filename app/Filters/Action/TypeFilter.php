<?php

namespace App\Filters\Action;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class TypeFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        return $builder->whereIn('type', $value);
    }
}