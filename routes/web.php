<?php

Route::get('api/customers', 'CustomerApiController@index');





Route::get('/', 'CustomerController@index');

// Route::get('/', function () {
//
//   return view('welcome');
//
// });
