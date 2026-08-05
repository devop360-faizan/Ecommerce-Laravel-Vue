<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Filterable
 * Applies a filter class to an Eloquent query.
 * Usage: Model::filter($request->all())->paginate()
 */
trait Filterable
{
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter => $value) {
            if (! empty($value) && method_exists($this, $method = 'filterBy' . ucfirst($filter))) {
                $query = $this->$method($query, $value);
            }
        }

        return $query;
    }
}
