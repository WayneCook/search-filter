<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\Request;
use App\Customer;


class customerApiController extends Controller
{


  protected $allowedFilters = [
      'id' ,'first_name', 'last_name', 'oranization', 'email', 'phone_number', 'revenue',
      'created_at', 'address'
  ];

  protected $orderable = [
      'id', 'first_name', 'last_name', 'company', 'email', 'revenue',
      'created_at'
  ];

  protected $tableFields = array(
      [ 'title' => 'First Name', 'name' => 'first_name', 'type' => 'string'],
      [ 'title' => 'Last Name', 'name' => 'last_name', 'type' => 'string'],
      [ 'title' => 'Phone Number', 'name' => 'phone_number', 'type' => 'string'],
      [ 'title' => 'Organization', 'name' => 'organization', 'type' => 'string'],
      [ 'title' => 'Revenue', 'name' => 'revenue', 'type' => 'numeric'],
  );

    public function index()
    {
        return Customer::advancedFilter();
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
