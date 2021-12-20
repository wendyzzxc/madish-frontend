<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //GD11
        DB::table('users')->insert([
            'name' => 'Wendy',
            'email' => '10376@students.uajy.ac.id',
            'password' => '$2a$12$Df2hjUJFaxZaK2.0CfZsWu7.RQekTUcfZGqrayWEgLP2qEjrNHFzi',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
