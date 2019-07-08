<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => '1',
            'name'=>'Admin',
            'user_name'=>'admin',
            'email'=>'sowrovali160@gmail.com',
            'password'=> bcrypt('admin'),


        ]);
        DB::table('users')->insert([
            'role_id' => '2',
            'name'=>'Author',
            'user_name'=>'author',
            'email'=>'author160@gmail.com',
            'password'=> bcrypt('author'),


        ]);
    }
}
