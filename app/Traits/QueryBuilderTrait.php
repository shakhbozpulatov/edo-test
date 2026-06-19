<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait QueryBuilderTrait
{
    protected function getQuery(Request $request, bool $isSearch = false, array $searchColumns = ['name'], array $extraSearch = [], $searchFunction = null): QueryBuilder
    {
        $query = QueryBuilder::for($this->modelClass);

        if ($isSearch && $request->filled('search')) {
            $search = $request->get('search');
            $searchColumns = array_merge($searchColumns, $extraSearch);

            if (! empty($searchFunction)) {
                $searchFunction($query);
            } else {
                $query->where(function ($query) use ($search, $searchColumns) {
                    foreach ($searchColumns as $column) {
                        $query->orWhere($column, 'ILIKE', "%{$search}%");
                    }
                });
            }
        }

        return $this->defaultQuery($query, $request);
    }

    protected function defaultQuery(QueryBuilder $query, Request $request): QueryBuilder
    {
        $query = $this->defaultAllowFilter($query, $request);
        $query->allowedIncludes(! empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedSorts($request->get('sort'));

        return $query;
    }

    /**
     * Request'dagi ?include= dan ruxsat etilgan fileable keylarni qaytaradi.
     * Controller'da $allowedFileableIncludes property orqali aniqlanadi.
     */
    protected function fileableIncludes(Request $request): array
    {
        $allowed   = $this->allowedFileableIncludes ?? [];
        $requested = array_filter(
            explode(',', $request->get('include', '')),
            fn ($v) => $v !== ''
        );

        return array_values(array_intersect($requested, $allowed));
    }

    protected function defaultAllowFilter(QueryBuilder $query, Request $request): QueryBuilder
    {
        $filters = $request->get('filter');
        $filter = [];

        if (! empty($filters)) {
            foreach ($filters as $k => $item) {
                $filter[] = AllowedFilter::exact($k);
            }
        }

        $query->allowedFilters($filter);

        return $query;
    }
}