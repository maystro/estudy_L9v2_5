<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetails;
use Database\Factories\UserDetailsFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DefaultLevelSeeder::class,
            DefaultSubjectSeeder::class,
            DefaultPlaylistSeeder::class,
            AdminUserSeeder::class,
        ]);

        User::factory(99)->create();
        UserDetails::factory(100)->create();
        Permission::factory(99)->create();
        Role::factory(99)->create();

    }
}
