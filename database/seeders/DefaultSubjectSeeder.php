<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class DefaultSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Subject::query()->insert([
            [
                'idx'=>1,
                'title'=>'عام',
            ],
            [
                'idx'=>2,
                'title'=>'الفقة',
            ],
            [
                'idx'=>3,
                'title'=>'العقيدة',
            ],
        ]
        );
    }
}
