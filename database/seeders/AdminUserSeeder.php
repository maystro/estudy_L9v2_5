<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
       $adminUser =  User::create([
           'name' => 'admin',
           'email' => 'admin@gmail.com',
           'password' => Hash::make('123456'),
        ]);

        Permission::insert([
            ['user_id'=>$adminUser->id,'title'=>'read'],
            ['user_id'=>$adminUser->id,'title'=>'create'],
            ['user_id'=>$adminUser->id,'title'=>'update'],
            ['user_id'=>$adminUser->id,'title'=>'delete'],
        ]);

        Role::insert([
            ['user_id'=>$adminUser->id,'title'=>'admin'],
        ]);

    }
}
