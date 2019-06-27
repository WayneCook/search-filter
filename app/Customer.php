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

    // protected $tableFields = array(
    //     [ 'title' => 'First Name', 'name' => 'first_name', 'type' => 'string'],
    //     [ 'title' => 'Last Name', 'name' => 'last_name', 'type' => 'string'],
    //     [ 'title' => 'Phone Number', 'name' => 'phone_number', 'type' => 'string'],
    //     [ 'title' => 'Organization', 'name' => 'organization', 'type' => 'string'],
    //     [ 'title' => 'Revenue', 'name' => 'revenue', 'type' => 'numeric'],
    // );

    protected $tableFields = array(
        [ 'text' => 'First Name', 'value' => 'first_name', 'type' => 'string'],
        [ 'text' => 'Last Name', 'value' => 'last_name', 'type' => 'string'],
        [ 'text' => 'Phone Number', 'value' => 'phone_number', 'type' => 'string'],
        [ 'text' => 'Organization', 'value' => 'organization', 'type' => 'string'],
        [ 'text' => 'Revenue', 'value' => 'revenue', 'type' => 'numeric'],
    );


}
