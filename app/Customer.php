<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Support\Traits\FilterHandle;
use Illuminate\Http\Request;


class Customer extends Model
{

  use FilterHandle;


    protected $allowedFilters = [
        'id' ,'first_name', 'last_name', 'organization', 'email', 'phone_number', 'revenue',
        'created_at', 'address'
    ];

    protected $orderable = [
        'id', 'first_name', 'organization', 'last_name', 'company', 'email', 'revenue',
        'created_at'
    ];


    protected $tableFields = array(
        [ 'text' => 'First Name', 'value' => 'first_name', 'type' => 'string', 'align' => 'left'],
        [ 'text' => 'Last Name', 'value' => 'last_name', 'type' => 'string', 'align' => 'left'],
        [ 'text' => 'Phone Number', 'value' => 'phone_number', 'type' => 'string', 'align' => 'left'],
        [ 'text' => 'Organization', 'value' => 'organization', 'type' => 'string', 'align' => 'left'],
        [ 'text' => 'Revenue', 'value' => 'revenue', 'type' => 'numeric', 'align' => 'left'],
        [ 'text' => 'Date', 'value' => 'created_at', 'type' => 'datetime', 'align' => 'left'],
    );


}
