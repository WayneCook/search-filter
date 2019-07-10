<?php

use Illuminate\Database\Seeder;
use App\Customer;
use App\Employee;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        factory(App\Customer::class, 1000)->create();
        factory(App\Employee::class, 1000)->create();
    }
}
