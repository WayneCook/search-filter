<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\Request;
use App\Employee;


class employeeApiController extends Controller
{

    public function index()
    {
        return Employee::advancedFilter();
    }


}
