<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Illuminate\Database\Seeder;

class DefaultPlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Playlist::query()->create([
            'title'=>'كتب الشيخ',
            'list_type'=>'MASTER_BOOKS',
            'visible_to'=>'1,2,3,4',
            'user_role'=>'student,teacher',
            'idx'=>1
        ]);
    }
}
