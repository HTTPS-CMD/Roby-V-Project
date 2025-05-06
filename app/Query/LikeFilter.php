<?php

namespace App\Query;

use Illuminate\Database\Eloquent\Builder;

class LikeFilter implements \Spatie\QueryBuilder\Filters\Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where($property, 'like', "%$value%");
    }
}
