<?php


use App\Employee;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Employee::class, function (Faker $faker) {
  return [
      'first_name' => $faker->firstName($gender = null),
      'last_name' => $faker->lastName,
      'phone_number' => $faker->phoneNumber,
      'email' => $faker->email,
      'organization' => $faker->catchPhrase,
      'address' => $faker->address,
      'start_date' => $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null),
      'revenue' => rand(100, 10000),
  ];
});
