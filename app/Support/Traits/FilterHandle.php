<?php

namespace App\Support\Traits;
use Illuminate\Validation\ValidationException;

trait FilterHandle {

    public function scopeAdvancedFilter($query)
    {

        return $this->process($query, request()->all())
            ->orderBy(
                request('order_column', 'created_at'),
                request('order_direction', 'desc')
            )
            ->paginate(request('limit', 10));
    }

    public function process($query, $data)
    {

        $v = validator()->make($data, [
            'order_column' => 'sometimes|required|in:'.$this->orderableColumns(),
            'order_direction' => 'sometimes|required|in:asc,desc',
            'limit' => 'sometimes|required|integer|min:1',

            // advanced filter
            'match' => 'sometimes|required|in:any,all',
            'f' => 'sometimes|required|array',
            'f.*.column' => 'required|in:'.$this->whiteListColumns(),
            'f.*.operator' => 'required_with:f.*.column|in:'.$this->allowedOperators(),
            'f.*.value_1' => 'required',
            'f.*.value_2' => 'required_if:f.*.operator,between,not_between'
        ]);

        if($v->fails()) {
            // debug
            return dd($v->messages()->all());

            throw new ValidationException;
        }

        return (new CustomQueryBuilder($query, $data))->apply();
    }

    protected function whiteListColumns()
    {
        return implode(',', $this->allowedFilters);
    }

    protected function orderableColumns()
    {
        return implode(',', $this->orderable);
    }

    protected function allowedOperators()
    {
        return implode(',', [
            'equal_to',
            'not_equal_to',
            'less_than',
            'greater_than',
            'between',
            'not_between',
            'contains',
            'includes',
            'starts_with',
            'ends_with',
            'in_the_past',
            'in_the_next',
            'in_the_peroid',
            'less_than_count',
            'greater_than_count',
            'equal_to_count',
            'not_equal_to_count'
        ]);
    }
}
