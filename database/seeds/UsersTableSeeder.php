<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $add=App\User::create([
            'first_name'=>"super  ",
            'last_name'=>"admin ",
            'email'  =>"super_admin@gmail.com",
            'password' =>\Hash::make('123456789'),
         ]);
         $add->attachRole('super_admin');
    }
}
