<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
  return [
      'first_name' => $faker->firstName($gender = null),
      'last_name' => $faker->lastName,
      'phone_number' => $faker->phoneNumber,
      'email' => $faker->email,
      'organization' => $faker->catchPhrase,
      'address' => $faker->address,
      'revenue' => rand(100, 10000),
  ];
});
