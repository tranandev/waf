<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        App\User::create([
        	'username' => 'admin',
        	'email' =>'an97bka@gmail.com',
        	'password' => bcrypt('12345678')
        ]);
    }
}
