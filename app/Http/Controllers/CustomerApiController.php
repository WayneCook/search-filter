<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\Request;
use App\Customer;


class customerApiController extends Controller
{

    public function index()
    {
        return Customer::advancedFilter();
    }

  
}
