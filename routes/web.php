<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Employee;


Route::get('api/employee', 'EmployeeApiController@index');





Route::get('/', 'EmployeeController@index');

Route::get('date', function (Request $request) {



    $date = new Carbon($request->date);


    $result = Employee::whereDate('start_date', '<=', $date)->get();

  

    dd($result);



});
