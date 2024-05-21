<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeds extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(! User::where('email', '=', 'admin@gmail.com')->first()){
        	User::create(['name'=>'admin','email'=>'admin@gmail.com',  'password'=>bcrypt(123456)]);
		}
    }
}
