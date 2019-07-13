<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Traits\FilterHandle;
use Illuminate\Http\Request;

class Employee extends Model
{

    use FilterHandle;


    protected $allowedFilters = [
        'id' ,'first_name', 'last_name', 'position', 'email', 'phone_number', 'revenue',
        'start_date', 'address'
    ];

    protected $orderable = [
        'id', 'first_name', 'position', 'last_name', 'company', 'email', 'revenue',
        'start_date'
    ];


    protected $tableFields = array(
        [ 'text' => 'First Name', 'value' => 'first_name', 'type' => 'string', 'align' => 'left'],
        [ 'text' => 'Last Name', 'value' => 'last_name', 'type' => 'string', 'align' => 'left'],
        [ 'text' => 'Phone Number', 'value' => 'phone_number', 'type' => 'string', 'align' => 'left'],
        [ 'text' => 'Position', 'value' => 'position', 'type' => 'string', 'align' => 'left'],
        [ 'text' => 'Revenue', 'value' => 'revenue', 'type' => 'numeric', 'align' => 'left'],
        [ 'text' => 'Start date', 'value' => 'start_date', 'type' => 'datetime', 'align' => 'left'],
    );
}
