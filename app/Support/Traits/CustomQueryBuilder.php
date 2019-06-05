<?php

namespace App\Support\Traits;

class CustomQueryBuilder {

    private $query,
            $filter,
            $match;

    public function __construct($query, $filter)
    {
        $this->query =$query;
        $this->filter =$filter;
        $this->match = $filter['match'] === 'all' ? 'where' : 'orWhere';
    }

    public function apply()
    {

        if (isset($this->filter['f'])) {

            foreach ($this->filter['f'] as $this->filter) {
                $this->makeFilter();
            }
        }
        return $this->query;
    }

    public function makeFilter()
    {
        $operator = camel_case($this->filter['operator']);
        $this->{$operator}();
    }

    //Operators
    public function contains()
    {
        return $this->query->{$this->match}($this->filter['column'], 'like', '%'.$this->filter['value_1'].'%');
    }

    public function equalTo()
    {
        return $this->query->{$this->match}($this->filter['column'], '=', $this->filter['value_1']);
    }

    


}
