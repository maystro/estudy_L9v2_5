<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class DefaultLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Level::insert([
            [
                'title'=>'المستوى الأول',
                'idx'=>1,
                'is_public'=>true
            ],
            [
                'title'=>'المستوى الثاني',
                'idx'=>2,
                'is_public'=>true
            ],
            [
                'title'=>'المستوى الثالث',
                'idx'=>3,
                'is_public'=>true
            ],
            [
                'title'=>'المستوى الرابع',
                'idx'=>4,
                'is_public'=>true
            ],
            [
                'title'=>'عام',
                'idx'=>5,
                'is_public'=>true
            ],
        ]);
    }
}
