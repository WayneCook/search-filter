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
        // if ($this->filter['data_type'] === 'datetime') {
        //     $operator = camel_case($this->filter['operator']).'Date';
        // } else {

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

    public function between()
    {
        return $this->query->{$this->match.'Between'}($this->filter['column'], [$this->filter['value_1'], $this->filter['value_2']]);
    }

    public function notBetween()
    {
        return $this->query->{$this->match.'notBetween'}($this->filter['column'], [$this->filter['value_1'], $this->filter['value_2']]);
    }

    public function greaterThan()
    {
        return $this->query->{$this->match}($this->filter['column'], '>', $this->filter['value_1']);
    }

    public function lessThan()
    {
        return $this->query->{$this->match}($this->filter['column'], '<', $this->filter['value_1']);
    }

    //Date queries
    public function dateEqual()
    {

        return $this->query->{$this->match.'Date'}($this->filter['column'], '=', $this->filter['value_1']);
    }

    public function dateBefore()
    {

        return $this->query->{$this->match.'Date'}($this->filter['column'], '<', $this->filter['value_1']);
    }

    public function dateAfter()
    {

        return $this->query->{$this->match.'Date'}($this->filter['column'], '>', $this->filter['value_1']);
    }

    public function dateBetween()
    {

        return $this->query->{$this->match.'Between'}('start_date', array($this->filter['value_1'], $this->filter['value_2']));

    }

}
